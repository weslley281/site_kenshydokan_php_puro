<?php
/**
 * Controlador de Inscrições e Integração com Filiados
 * Padrão IDÊNTICO ao site principal (controllers/inscricaoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_GET['acao'] ?? '') === 'buscar_filiados') {
    ini_set('display_errors', '0');
    ob_start();
}

include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/filiadoModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    if (isset($_GET['acao']) && $_GET['acao'] === 'buscar_filiados') {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['results' => [], 'error' => 'Sua sessão expirou. Entre novamente no sistema.']);
        exit;
    }
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

// Endpoint AJAX de busca de filiados para Select2
if (isset($_GET['acao']) && $_GET['acao'] === 'buscar_filiados') {
    header('Content-Type: application/json; charset=utf-8');
    $respostaEnviada = false;
    register_shutdown_function(static function () use (&$respostaEnviada): void {
        if ($respostaEnviada) {
            return;
        }

        $ultimoErro = error_get_last();
        if ($ultimoErro === null || !in_array($ultimoErro['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            return;
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode([
            'results' => [],
            'error' => 'O servidor encontrou um erro ao consultar filiados.',
            'debug' => Conexao::debugAtivo() ? [
                'etapa' => 'erro fatal do PHP',
                'tipo' => $ultimoErro['type'],
                'arquivo' => basename($ultimoErro['file']),
                'linha' => $ultimoErro['line'],
                'mensagem' => 'Consulte o log PHP do servidor para o detalhe técnico.'
            ] : null
        ], JSON_INVALID_UTF8_SUBSTITUTE);
    });
    $termo = trim($_GET['q'] ?? '');
    new Conexao();
    $debugAtivo = Conexao::debugAtivo();
    $configBancoPrincipal = null;
    if ($debugAtivo) {
        $configBancoPrincipal = [
            'host' => $_ENV['MAIN_DB_HOST'] ?? $_ENV['DB_HOST'] ?? '127.0.0.1',
            'porta' => $_ENV['MAIN_DB_PORT'] ?? $_ENV['DB_PORT'] ?? 3306,
            'banco_configurado' => $_ENV['MAIN_DB_NAME'] ?? $_ENV['DB_NAME'] ?? null,
            'usuario_configurado' => !empty($_ENV['MAIN_DB_USER'] ?? $_ENV['DB_USER'] ?? ''),
            'senha_configurada' => !empty($_ENV['MAIN_DB_PASS'] ?? $_ENV['DB_PASS'] ?? '')
        ];
    }
    $redigirCredenciais = static function (string $mensagem): string {
        foreach (['DB_USER', 'DB_PASS', 'MAIN_DB_USER', 'MAIN_DB_PASS'] as $chave) {
            $segredo = $_ENV[$chave] ?? getenv($chave);
            if (is_string($segredo) && $segredo !== '') {
                $mensagem = str_replace($segredo, '[redigido]', $mensagem);
            }
        }
        return $mensagem;
    };
    if (strlen($termo) < 2) {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        $respostaEnviada = true;
        echo json_encode(['results' => []]);
        exit;
    }

    $etapaBusca = 'conectar ao banco principal';
    $filiadoModel = null;
    try {
        $filiadoModel = new Filiado();
        $diagnostico = $debugAtivo ? $filiadoModel->diagnosticarConexao() : null;
        $etapaBusca = 'consultar e formatar filiados';
        $filiados = $filiadoModel->buscarPorTermo($termo, 30);
        $results = [];

        foreach ($filiados as $f) {
            $codigo = !empty($f['codigo']) ? " [Cód: {$f['codigo']}]" : '';
            $dn = ($f['dataNascimento'] !== '0000-00-00') ? date('d/m/Y', strtotime($f['dataNascimento'])) : 'Data não informada';
            $results[] = [
                'id' => $f['id_filiado'],
                'text' => "{$f['nome']}{$codigo} - {$f['dojo']} ({$f['graduacao_nome']})",
                'nome' => $f['nome'],
                'dojo' => $f['dojo'],
                'dataNascimento' => $f['dataNascimento'],
                'dataNascimentoFormatada' => $dn,
                'graduacao_id' => $f['id_graduacao'],
                'graduacao_nome' => $f['graduacao_nome']
            ];
        }

        $resposta = ['results' => $results];
        if ($debugAtivo) {
            $dadosDebug = [
                'etapa' => 'concluída',
                'quantidade_resultados' => count($results),
                'configuracao_banco_principal' => $configBancoPrincipal,
                'conexao_e_schema' => $diagnostico
            ];
            ob_start();
            var_dump($dadosDebug);
            $dadosDebug['var_dump'] = ob_get_clean();
            $resposta['debug'] = $dadosDebug;
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        $respostaEnviada = true;
        echo json_encode($resposta, JSON_INVALID_UTF8_SUBSTITUTE);
    } catch (Throwable $erro) {
        $erroTecnico = $erro;
        while ($erroTecnico->getPrevious() !== null) {
            $erroTecnico = $erroTecnico->getPrevious();
        }
        $codigo = $erroTecnico->getCode();
        $sqlstate = $erroTecnico instanceof mysqli_sql_exception ? $erroTecnico->getSqlState() : 'N/A';
        $mensagem = $redigirCredenciais($erroTecnico->getMessage());
        error_log(sprintf('Falha na busca de filiados na etapa "%s": %s; código %s; SQLSTATE %s', $etapaBusca, $mensagem, $codigo, $sqlstate));
        http_response_code(500);
        $resposta = [
            'results' => [],
            'error' => 'Não foi possível consultar os filiados. Verifique a conexão e as permissões do banco principal.'
        ];
        if ($debugAtivo) {
            $dadosDebug = [
                'etapa' => $etapaBusca,
                'tipo_erro' => get_class($erroTecnico),
                'mensagem' => $mensagem,
                'codigo' => $codigo,
                'sqlstate' => $sqlstate,
                'configuracao_banco_principal' => $configBancoPrincipal
            ];
            if ($filiadoModel instanceof Filiado) {
                try {
                    $dadosDebug['conexao_e_schema'] = $filiadoModel->diagnosticarConexao();
                } catch (Throwable $erroDiagnostico) {
                    $dadosDebug['erro_diagnostico'] = get_class($erroDiagnostico) . ': ' . $redigirCredenciais($erroDiagnostico->getMessage());
                }
            }
            ob_start();
            var_dump($dadosDebug);
            $dadosDebug['var_dump'] = ob_get_clean();
            $resposta['debug'] = $dadosDebug;
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        $respostaEnviada = true;
        echo json_encode($resposta, JSON_INVALID_UTF8_SUBSTITUTE);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inscricaoModel = new Inscricao();
    $categoriaModel = new Categoria();
    $campeonatoModel = new Campeonato();
    $filiadoModel = new Filiado();

    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "criar_inscricao") {
        $idCategoria = (int)($_POST["id_categoria"] ?? 0);
        $idFiliado = (int)($_POST["id_filiado"] ?? 0);
        $sexo = $_POST["sexo"] ?? "M";
        $pesoDeclarado = $_POST["peso_declarado"] !== "" ? (float)$_POST["peso_declarado"] : null;

        $campeonato = $campeonatoModel->buscarPorId($idCampeonato);
        $categoria = $categoriaModel->buscarPorId($idCategoria);
        $filiado = $filiadoModel->buscarPorId($idFiliado);

        if (!$campeonato || !$categoria || !$filiado) {
            exibirMensagemEredirecionar("Dados incompletos para a inscrição.", "../views/inscricoes.php?id=" . $idCampeonato);
        }

        // 1. Checa se já está inscrito nesta categoria
        if ($inscricaoModel->jaInscritoNaCategoria($idCategoria, $idFiliado)) {
            exibirMensagemEredirecionar("O atleta {$filiado['nome']} já está inscrito nesta categoria.", "../views/inscricoes.php?id=" . $idCampeonato);
        }

        // 2. Valida Data de Nascimento e Idade
        if (empty($filiado['dataNascimento']) || $filiado['dataNascimento'] === '0000-00-00') {
            exibirMensagemEredirecionar("O filiado {$filiado['nome']} está sem data de nascimento cadastrada.", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

        $nasc = new DateTime($filiado['dataNascimento']);
        $ref = new DateTime($campeonato['data_referencia_idade']);
        $idade = $ref->diff($nasc)->y;

        if ($idade < (int)$categoria['idade_min'] || $idade > (int)$categoria['idade_max']) {
            exibirMensagemEredirecionar("Idade do atleta ({$idade} anos) incompatível com a categoria {$categoria['nome']} ({$categoria['idade_min']} a {$categoria['idade_max']} anos).", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

        // 3. Valida Sexo
        if ($categoria['sexo'] !== 'misto' && $categoria['sexo'] !== $sexo) {
            exibirMensagemEredirecionar("Sexo incompatível com a categoria selecionada.", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

        // 4. Grava Inscrição com Snapshot Imutável
        $statusInicial = ($categoria['exige_pesagem'] == 1) ? 'pendente' : 'aprovada';

        $dadosSnapshot = [
            'id_campeonato' => $idCampeonato,
            'id_categoria' => $idCategoria,
            'id_filiado' => $idFiliado,
            'nome_atleta_snapshot' => $filiado['nome'],
            'dojo_snapshot' => $filiado['dojo'] ?: 'Independente',
            'graduacao_id_snapshot' => (int)$filiado['id_graduacao'],
            'graduacao_nome_snapshot' => $filiado['graduacao_nome'],
            'data_nascimento_snapshot' => $filiado['dataNascimento'],
            'idade_evento' => $idade,
            'sexo' => $sexo,
            'peso_declarado' => $pesoDeclarado,
            'status_elegibilidade' => $statusInicial
        ];

        $idInscricao = $inscricaoModel->criarComSnapshot($dadosSnapshot);
        if ($idInscricao > 0) {
            Auditoria::log("INSCREVER_ATLETA", "campeonato_inscricoes", $idInscricao, null, [
                'atleta' => $filiado['nome'],
                'categoria' => $categoria['nome']
            ], null, $idCampeonato);
            exibirMensagemEredirecionar("Atleta {$filiado['nome']} inscrito com sucesso na categoria {$categoria['nome']}!", "../views/inscricoes.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao registrar inscrição.", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

    } elseif ($tipo === "atualizar_status") {
        $idInscricao = (int)($_POST["id_inscricao"] ?? 0);
        $novoStatus = $_POST["status"] ?? "aprovada";
        $motivo = trim($_POST["motivo"] ?? "");

        if ($inscricaoModel->atualizarStatus($idInscricao, $novoStatus, $motivo ?: null)) {
            Auditoria::log("ALTERAR_STATUS_INSCRICAO", "campeonato_inscricoes", $idInscricao, null, ['status' => $novoStatus, 'motivo' => $motivo], null, $idCampeonato);
            exibirMensagemEredirecionar("Status da inscrição atualizado para " . strtoupper($novoStatus) . ".", "../views/inscricoes.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao atualizar status da inscrição.", "../views/inscricoes.php?id=" . $idCampeonato);
        }
    }
}
