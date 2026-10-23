<?php
/**
 * Controlador de Homologação de Resultados e Pódios
 * Padrão IDÊNTICO ao site principal (controllers/homologacaoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/classificacaoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/sumulaKataModel.php";
include_once __DIR__ . "/../models/sumulaTameshiwariModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/inicio.php');
    exigirPerfil([]);
    $classificacaoModel = new Classificacao();
    $categoriaModel = new Categoria();

    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);
    $idCategoria = (int)($_POST["id_categoria"] ?? 0);

    if ($tipo === "homologar_categoria") {
        $categoria = $categoriaModel->buscarPorId($idCategoria);
        if (!$categoria) {
            exibirMensagemEredirecionar("Categoria não encontrada.", "../views/resultados.php?id=" . $idCampeonato);
        }

        $classificacaoModel->limparPorCategoria($idCategoria);
        $homologadorId = $_SESSION['id_usuario'] ?? 1;

        if ($categoria['modalidade'] === 'kumite') {
            $chaveModel = new Chave();
            $lutaModel = new Luta();
            $chave = $chaveModel->buscarPorCategoria($idCategoria);

            if ($chave) {
                $lutas = $lutaModel->buscarPorChave((int)$chave['id_chave']);
                // Busca a luta final (maior rodada)
                $lutaFinal = null;
                $rodadaFinal = (int)$chave['total_rodadas'];
                foreach ($lutas as $l) {
                    if ((int)$l['rodada'] === $rodadaFinal && $l['status'] === 'concluida') {
                        $lutaFinal = $l;
                        break;
                    }
                }

                if ($lutaFinal && !empty($lutaFinal['id_vencedor'])) {
                    $campeaoId = (int)$lutaFinal['id_vencedor'];
                    $viceId = ($campeaoId == $lutaFinal['id_inscricao_aka']) ? (int)$lutaFinal['id_inscricao_shiro'] : (int)$lutaFinal['id_inscricao_aka'];

                    $classificacaoModel->gravarColocacao($idCampeonato, $idCategoria, $campeaoId, 1, 'ouro', 10, $homologadorId);
                    if ($viceId > 0) {
                        $classificacaoModel->gravarColocacao($idCampeonato, $idCategoria, $viceId, 2, 'prata', 7, $homologadorId);
                    }

                    // 3º lugar (perdedores das semifinais)
                    $rodadaSemi = $rodadaFinal - 1;
                    if ($rodadaSemi >= 1) {
                        foreach ($lutas as $ls) {
                            if ((int)$ls['rodada'] === $rodadaSemi && $ls['status'] === 'concluida') {
                                $perdedorSemi = ($ls['id_vencedor'] == $ls['id_inscricao_aka']) ? (int)$ls['id_inscricao_shiro'] : (int)$ls['id_inscricao_aka'];
                                if ($perdedorSemi > 0) {
                                    $classificacaoModel->gravarColocacao($idCampeonato, $idCategoria, $perdedorSemi, 3, 'bronze', 4, $homologadorId);
                                }
                            }
                        }
                    }
                }
            }
        } elseif ($categoria['modalidade'] === 'kata') {
            $kataModel = new SumulaKata();
            $ranking = $kataModel->obterRanking($idCategoria, 1);
            foreach ($ranking as $idx => $r) {
                $colocacao = $idx + 1;
                $medalha = ($colocacao === 1) ? 'ouro' : (($colocacao === 2) ? 'prata' : (($colocacao === 3) ? 'bronze' : 'nenhuma'));
                $pontos = ($colocacao === 1) ? 10 : (($colocacao === 2) ? 7 : (($colocacao === 3) ? 4 : 1));
                $classificacaoModel->gravarColocacao($idCampeonato, $idCategoria, (int)$r['id_inscricao'], $colocacao, $medalha, $pontos, $homologadorId);
            }
        } else {
            // Tameshiwari
            $tameshiwariModel = new SumulaTameshiwari();
            $ranking = $tameshiwariModel->obterRanking($idCategoria);
            foreach ($ranking as $idx => $r) {
                $colocacao = $idx + 1;
                $medalha = ($colocacao === 1) ? 'ouro' : (($colocacao === 2) ? 'prata' : (($colocacao === 3) ? 'bronze' : 'nenhuma'));
                $pontos = ($colocacao === 1) ? 10 : (($colocacao === 2) ? 7 : (($colocacao === 3) ? 4 : 1));
                $classificacaoModel->gravarColocacao($idCampeonato, $idCategoria, (int)$r['id_inscricao'], $colocacao, $medalha, $pontos, $homologadorId);
            }
        }

        Auditoria::log("HOMOLOGAR_RESULTADOS", "campeonato_classificacao", $idCategoria, null, ['categoria' => $categoria['nome']], null, $idCampeonato);
        exibirMensagemEredirecionar("Pódio da categoria {$categoria['nome']} homologado com sucesso!", "../views/resultados.php?id=" . $idCampeonato);
    }
}
