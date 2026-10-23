<?php
/**
 * Controlador de Sorteio e Geração de Chaves
 * Padrão IDÊNTICO ao site principal (controllers/chaveamentoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/inicio.php');
    exigirPerfil([]);
    $tipo = $_POST["tipo"] ?? "";
    $idCategoria = (int)($_POST["id_categoria"] ?? 0);

    if ($tipo === "gerar_chave") {
        $categoriaModel = new Categoria();
        $inscricaoModel = new Inscricao();
        $chaveModel = new Chave();
        $lutaModel = new Luta();

        $categoria = $categoriaModel->buscarPorId($idCategoria);
        if (!$categoria) {
            exibirMensagemEredirecionar("Categoria não encontrada.", "../views/campeonatos.php");
        }

        $idCampeonato = (int)$categoria['id_campeonato'];

        // Filtra apenas atletas com status 'aprovada'
        $inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato, $idCategoria);
        $atletasAptos = array_values(array_filter($inscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada'));

        if (count($atletasAptos) < 2) {
            exibirMensagemEredirecionar("São necessários no mínimo 2 atletas aprovados na pesagem para realizar o sorteio.", "../views/ver_chave.php?id=" . $idCategoria);
        }

        $totalAtletas = count($atletasAptos);

        // Calcula potência de 2 mais próxima
        $bracketSize = 2;
        while ($bracketSize < $totalAtletas) {
            $bracketSize *= 2;
        }

        $totalByes = $bracketSize - $totalAtletas;
        $totalRodadas = (int)log($bracketSize, 2);

        // Agrupamento por Dojo para evitar confrontos diretos na R1
        $porDojo = [];
        foreach ($atletasAptos as $atleta) {
            $dojo = $atleta['dojo_snapshot'] ?: 'Independente';
            $porDojo[$dojo][] = $atleta;
        }

        // Distribuição alternada
        $listaOrdenada = [];
        $continua = true;
        while ($continua) {
            $continua = false;
            foreach ($porDojo as $dojo => &$membros) {
                if (!empty($membros)) {
                    $listaOrdenada[] = array_shift($membros);
                    $continua = true;
                }
            }
        }

        // Preenche slots da primeira rodada intercalando Byes
        $slots = [];
        $byesRestantes = $totalByes;
        for ($s = 0; $s < $bracketSize; $s++) {
            if ($s % 2 !== 0 && $byesRestantes > 0) {
                $slots[] = null; // Bye no canto branco (SHIRO)
                $byesRestantes--;
            } else {
                $slots[] = array_shift($listaOrdenada) ?: null;
            }
        }

        // Remove chave anterior se existir
        $chaveModel->excluirPorCategoria($idCategoria);

        // Salva a chave
        $idChave = $chaveModel->salvarChave(
            $idCampeonato,
            $idCategoria,
            'eliminatoria_simples',
            $totalAtletas,
            $totalRodadas,
            json_encode(['bracket_size' => $bracketSize, 'byes' => $totalByes], JSON_UNESCAPED_UNICODE)
        );

        // Gera as lutas da primeira rodada e calcula as rodadas subsequentes
        $numeroLuta = 1;
        $lutasPorRodada = [];
        $totalLutasR1 = $bracketSize / 2;

        for ($i = 0; $i < $totalLutasR1; $i++) {
            $aka = $slots[$i * 2] ?? null;
            $shiro = $slots[$i * 2 + 1] ?? null;

            $status = ($aka && $shiro) ? 'pendente' : 'concluida';
            $vencedorId = null;
            $formaVitoria = null;

            // Se um dos lados for Bye, o outro avança por WO automaticamente
            if ($aka && !$shiro) {
                $vencedorId = (int)$aka['id_inscricao'];
                $formaVitoria = 'wo';
            } elseif (!$aka && $shiro) {
                $vencedorId = (int)$shiro['id_inscricao'];
                $formaVitoria = 'wo';
            }

            $idLuta = $lutaModel->criarLuta([
                'id_chave' => $idChave,
                'id_campeonato' => $idCampeonato,
                'id_categoria' => $idCategoria,
                'id_tatame' => null,
                'numero_luta' => $numeroLuta++,
                'rodada' => 1,
                'posicao_na_chave' => $i + 1,
                'id_inscricao_aka' => $aka ? (int)$aka['id_inscricao'] : null,
                'id_inscricao_shiro' => $shiro ? (int)$shiro['id_inscricao'] : null,
                'status' => $status,
                'proxima_luta_id' => null
            ]);

            if ($vencedorId) {
                $lutaModel->definirVencedor($idLuta, $vencedorId, $formaVitoria);
            }

            $lutasPorRodada[1][$i + 1] = $idLuta;
        }

        // Gera as lutas das rodadas seguintes (Quartas, Semis, Final)
        for ($r = 2; $r <= $totalRodadas; $r++) {
            $totalLutasR = $bracketSize / pow(2, $r);
            for ($j = 0; $j < $totalLutasR; $j++) {
                $idLuta = $lutaModel->criarLuta([
                    'id_chave' => $idChave,
                    'id_campeonato' => $idCampeonato,
                    'id_categoria' => $idCategoria,
                    'id_tatame' => null,
                    'numero_luta' => $numeroLuta++,
                    'rodada' => $r,
                    'posicao_na_chave' => $j + 1,
                    'id_inscricao_aka' => null,
                    'id_inscricao_shiro' => null,
                    'status' => 'pendente',
                    'proxima_luta_id' => null
                ]);
                $lutasPorRodada[$r][$j + 1] = $idLuta;

                // Conecta a rodada anterior a esta nova luta
                $posAnteriorAka = ($j * 2) + 1;
                $posAnteriorShiro = ($j * 2) + 2;

                if (isset($lutasPorRodada[$r - 1][$posAnteriorAka])) {
                    $idLutaAntAka = $lutasPorRodada[$r - 1][$posAnteriorAka];
                    $db = (new Conexao())->conectar();
                    $stmtUp = $db->prepare("UPDATE campeonato_lutas SET proxima_luta_id = ? WHERE id_luta = ?");
                    $stmtUp->bind_param("ii", $idLuta, $idLutaAntAka);
                    $stmtUp->execute();
                    $stmtUp->close();
                }

                if (isset($lutasPorRodada[$r - 1][$posAnteriorShiro])) {
                    $idLutaAntShiro = $lutasPorRodada[$r - 1][$posAnteriorShiro];
                    $db = (new Conexao())->conectar();
                    $stmtUp = $db->prepare("UPDATE campeonato_lutas SET proxima_luta_id = ? WHERE id_luta = ?");
                    $stmtUp->bind_param("ii", $idLuta, $idLutaAntShiro);
                    $stmtUp->execute();
                    $stmtUp->close();
                }
            }
        }

        Auditoria::log("SORTEIO_CHAVE", "campeonato_chaves", $idChave, null, [
            'atletas' => $totalAtletas,
            'byes' => $totalByes,
            'rodadas' => $totalRodadas
        ], null, $idCampeonato);

        exibirMensagemEredirecionar("Chaveamento oficial gerado com sucesso! ({$totalAtletas} atletas, {$totalByes} Byes)", "../views/ver_chave.php?id=" . $idCategoria);
    }
}
