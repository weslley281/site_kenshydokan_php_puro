<?php
// controllers/presencaController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Apenas sensei (admin) ou sempai (auxiliar) podem lançar presenças
    if (!isset($_SESSION['nivel']) || !in_array($_SESSION['nivel'], ['sensei', 'sempai'])) {
        header("Location: ../views/login.php");
        exit();
    }

    include_once "../models/listaPresencaModel.php";
    $presencaModel = new ListaPresencaModel();

    $tipo = $_POST['tipo'] ?? '';

    if ($tipo === 'salvar_chamada') {
        $id_arte = intval($_POST['id_arte'] ?? 0);
        $data_presenca = $_POST['data_presenca'] ?? date('Y-m-d');
        $conteudo_aula = $_POST['conteudo_aula'] ?? '';
        $filiados_ids = $_POST['filiados_ids'] ?? []; // Array com IDs de todos os alunos exibidos
        $status_post = $_POST['status'] ?? []; // Array com as marcações de presença (apenas marcados)

        if ($id_arte <= 0 || empty($filiados_ids)) {
            echo "<script>alert('Erro: Modalidade inválida ou sem alunos.'); window.history.back();</script>";
            exit();
        }

        // Monta o status final para todos os alunos (P = Presente, F = Falta)
        $filiadosStatus = [];
        foreach ($filiados_ids as $id_filiado) {
            $id_filiado = intval($id_filiado);
            if (isset($status_post[$id_filiado]) && $status_post[$id_filiado] === 'P') {
                $filiadosStatus[$id_filiado] = 'P';
            } else {
                $filiadosStatus[$id_filiado] = 'F';
            }
        }

        $sucesso = $presencaModel->registrarChamadaLote($id_arte, $data_presenca, $conteudo_aula, $filiadosStatus);

        if ($sucesso) {
            echo "<script>alert('Presença gravada com sucesso!'); window.location='../views/admin/index.php?pagina=chamada&id_arte={$id_arte}&data_presenca={$data_presenca}';</script>";
        } else {
            echo "<script>alert('Erro ao gravar chamada.'); window.history.back();</script>";
        }
        exit();
    }
}

header("Location: ../views/login.php");
exit();
?>
