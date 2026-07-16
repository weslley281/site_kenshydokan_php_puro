<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validação de autenticação
    if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
        echo "<script>alert('Acesso negado.'); window.location.href = '../views/login.php';</script>";
        exit();
    }

    include_once "../models/dojoAlunoConfigModel.php";
    include_once "../models/dojoMensalidadeModel.php";
    include_once "../models/dojoFinanceiroModel.php";
    include_once "../models/filiadoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro ao processar. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $tipo = $_POST["tipo"];
        $alunoConfigModel = new DojoAlunoConfigModel();
        $mensalidadeModel = new DojoMensalidadeModel();
        $financeiroModel = new DojoFinanceiroModel();

        if ($tipo === "salvar_aluno_config") {
            $id_filiado = intval($_POST["id_filiado"]);
            $valor = floatval($_POST["valor_mensalidade"]);
            $vencimento = intval($_POST["dia_vencimento"]);
            $status = $_POST["status_aluno"];

            if ($alunoConfigModel->salvarConfiguracao($id_filiado, $valor, $vencimento, $status)) {
                $mensalidadeModel->atualizarInadimplencias();
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/gerenciamento_dojo/index.php?pagina=alunos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/gerenciamento_dojo/index.php?pagina=alunos');
            }
        } elseif ($tipo === "lancar_mensalidades_mes") {
            $referencia = $_POST["referencia"]; // Ex: '2026-06'
            if (empty($referencia)) {
                $referencia = date("Y-m");
            }

            $resultado = $mensalidadeModel->gerarMensalidadesDoMes($referencia);
            $mensalidadeModel->atualizarInadimplencias();

            $msg = "Lançamentos concluídos: " . $resultado['criados'] . " gerados, " . $resultado['pulados'] . " já existentes.";
            exibirMensagemEredirecionar($msg, '../views/gerenciamento_dojo/index.php?pagina=mensalidades&referencia=' . $referencia);
        } elseif ($tipo === "receber_mensalidade") {
            $id_mensalidade = intval($_POST["id_mensalidade"]);
            $data_pagamento = $_POST["data_pagamento"];
            if (empty($data_pagamento)) {
                $data_pagamento = date("Y-m-d");
            }

            $mensalidade = $mensalidadeModel->buscarMensalidadePorId($id_mensalidade);
            if ($mensalidade) {
                if ($mensalidadeModel->receberMensalidade($id_mensalidade, $data_pagamento)) {
                    // Busca dados do filiado para o histórico financeiro
                    $filiadoModelRepo = new FiliadoModel();
                    $filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($mensalidade['id_filiado']);
                    $nome_filiado = $filiado_obj ? $filiado_obj->getNome() : 'Aluno';

                    // Registra entrada no caixa geral automaticamente
                    $descricao = "Mensalidade - " . $nome_filiado . " (Ref: " . $mensalidade['referencia'] . ")";
                    $financeiroModel->registrarMovimentacao(
                        $descricao,
                        'entrada',
                        $mensalidade['valor'],
                        $data_pagamento,
                        'mensalidade',
                        $id_mensalidade
                    );

                    // Recalcula o status de inadimplência de todos os alunos
                    $mensalidadeModel->atualizarInadimplencias();

                    $destino = '../views/gerenciamento_dojo/index.php?pagina=mensalidades&referencia=' . $mensalidade['referencia'];
                    echo "<script language='javascript'>
                        if (confirm('Operação realizada com sucesso! Deseja gerar e imprimir o recibo de pagamento?')) {
                            window.open('gerar_recibo.php?id=" . $id_mensalidade . "', '_blank');
                        }
                        window.location='" . $destino . "';
                    </script>";
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/gerenciamento_dojo/index.php?pagina=mensalidades');
                }
            } else {
                exibirMensagemEredirecionar("Mensalidade não encontrada.", '../views/gerenciamento_dojo/index.php?pagina=mensalidades');
            }
        } elseif ($tipo === "lancar_financeiro") {
            $descricao = $_POST["descricao"];
            $tipo_mov = $_POST["tipo_movimentacao"]; // 'entrada' ou 'saida'
            $valor = floatval($_POST["valor"]);
            $data_mov = $_POST["data_movimentacao"];
            $categoria = $_POST["categoria"];

            if (empty($data_mov)) {
                $data_mov = date("Y-m-d");
            }

            if ($financeiroModel->registrarMovimentacao($descricao, $tipo_mov, $valor, $data_mov, $categoria)) {
                $ref_mes = substr($data_mov, 0, 7);
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/gerenciamento_dojo/index.php?pagina=financeiro&referencia=' . $ref_mes);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/gerenciamento_dojo/index.php?pagina=financeiro');
            }
        } elseif ($tipo === "excluir_financeiro") {
            $id_financeiro = intval($_POST["id_financeiro"]);
            $ref_mes = $_POST["referencia_mes"];

            if ($financeiroModel->excluirMovimentacao($id_financeiro)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/gerenciamento_dojo/index.php?pagina=financeiro&referencia=' . $ref_mes);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/gerenciamento_dojo/index.php?pagina=financeiro');
            }
        }
    } else {
        exibirMensagemEredirecionar("Lançamento inválido.", '../views/gerenciamento_dojo/index.php');
    }
} else {
    exibirMensagemEredirecionar("A requisição deve ser do tipo POST.", '../views/gerenciamento_dojo/index.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
}
?>
