<?php
// controllers/exameController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Apenas sensei (admin) pode lançar ou alterar exames
    if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'sensei') {
        header("Location: ../views/login.php");
        exit();
    }

    include_once "../models/exameGraduacaoModel.php";
    $exameModel = new ExameGraduacaoModel();

    $tipo = $_POST['tipo'] ?? '';

    if ($tipo === 'inserir') {
        $id_filiado = intval($_POST['id_filiado'] ?? 0);
        $id_arte = intval($_POST['id_arte'] ?? 0);
        $id_graduacao_atual = intval($_POST['id_graduacao_atual'] ?? 0);
        $id_graduacao_pretendida = intval($_POST['id_graduacao_pretendida'] ?? 0);
        $nota = !empty($_POST['nota']) ? floatval(str_replace(',', '.', $_POST['nota'])) : null;
        $comentarios = $_POST['comentarios'] ?? '';
        $data_exame = $_POST['data_exame'] ?? date('Y-m-d');
        $situacao = $_POST['situacao'] ?? 'pendente'; // aprovado, reprovado, pendente

        if ($id_filiado <= 0 || $id_arte <= 0 || $id_graduacao_pretendida <= 0) {
            echo "<script>alert('Erro: Preencha todos os campos obrigatórios.'); window.history.back();</script>";
            exit();
        }

        $sucesso = $exameModel->criarExame($id_filiado, $id_arte, $id_graduacao_atual, $id_graduacao_pretendida, $nota, $comentarios, $data_exame, $situacao);

        if ($sucesso) {
            echo "<script>alert('Exame registrado com sucesso!'); window.location='../views/admin/index.php?pagina=exames';</script>";
        } else {
            echo "<script>alert('Erro ao registrar exame.'); window.history.back();</script>";
        }
        exit();
    } elseif ($tipo === 'editar') {
        $id = intval($_POST['id'] ?? 0);
        $id_filiado = intval($_POST['id_filiado'] ?? 0);
        $id_arte = intval($_POST['id_arte'] ?? 0);
        $id_graduacao_atual = intval($_POST['id_graduacao_atual'] ?? 0);
        $id_graduacao_pretendida = intval($_POST['id_graduacao_pretendida'] ?? 0);
        $nota = !empty($_POST['nota']) ? floatval(str_replace(',', '.', $_POST['nota'])) : null;
        $comentarios = $_POST['comentarios'] ?? '';
        $data_exame = $_POST['data_exame'] ?? date('Y-m-d');
        $situacao = $_POST['situacao'] ?? 'pendente';

        if ($id <= 0 || $id_filiado <= 0 || $id_arte <= 0 || $id_graduacao_pretendida <= 0) {
            echo "<script>alert('Erro: Preencha todos os campos obrigatórios.'); window.history.back();</script>";
            exit();
        }

        $sucesso = $exameModel->editarExame($id, $id_filiado, $id_arte, $id_graduacao_atual, $id_graduacao_pretendida, $nota, $comentarios, $data_exame, $situacao);

        if ($sucesso) {
            echo "<script>alert('Exame atualizado com sucesso!'); window.location='../views/admin/index.php?pagina=exames';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar exame.'); window.history.back();</script>";
        }
        exit();
    } elseif ($tipo === 'excluir') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo "<script>alert('Erro: ID inválido.'); window.history.back();</script>";
            exit();
        }

        $sucesso = $exameModel->excluirExame($id);

        if ($sucesso) {
            echo "<script>alert('Exame excluído com sucesso!'); window.location='../views/admin/index.php?pagina=exames';</script>";
        } else {
            echo "<script>alert('Erro ao excluir exame.'); window.history.back();</script>";
        }
        exit();
    }
}

header("Location: ../views/login.php");
exit();
?>
