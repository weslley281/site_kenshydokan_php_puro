<?php
$page_title = "Aulas do Curso";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../repositorios/AulaRepositorio.php";
include_once __DIR__ . "/../../repositorios/CursoRepositorio.php";

$aulaRepositorio = new AulaRepositorio();
$aulas_assistidas_ids = [];
if (isset($_SESSION['id_usuario'])) {
    $aulas_assistidas_ids = $aulaRepositorio->getAulasAssistidasPorUsuario($_SESSION['id_usuario']);
}

$id_curso = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id_curso === 0) {
    header("Location: /cursos.php");
    exit;
}

// Fetch course details using the repository for consistency
$cursoRepositorio = new CursoRepositorio();
$curso = $cursoRepositorio->buscarCurso($id_curso);

?>

<body>
    <div class="container mt-5">
        <div class="row">
            <?php include __DIR__ . "/_perfil_menu.php"; ?>
            <div class="col-lg-9 mb-4">
                <?php include __DIR__ . "/_perfil_info_card.php"; ?>

                <hr>
                <div class="text-center">
                    <h1 class="my-4"><strong>Aulas</strong></h1>
                </div>
                <hr>

                <?php
                $total_aulas = $aulaRepositorio->getTotalAulasPorCurso($id_curso);
                $aulas_assistidas_no_curso = 0;

                // This logic can be simplified if we only need the count
                $aulas_do_curso = $aulaRepositorio->buscarAulasPorCurso($id_curso);
                foreach ($aulas_do_curso as $aula) {
                    if (in_array($aula['id_aula'], $aulas_assistidas_ids)) {
                        $aulas_assistidas_no_curso++;
                    }
                }

                $percentual_conclusao = ($total_aulas > 0) ? ($aulas_assistidas_no_curso / $total_aulas) * 100 : 0;
                $percentual_necessario = $curso['percentual_conclusao_certificado'] ?? 100;
                $pode_emitir_certificado = ($percentual_conclusao >= $percentual_necessario);
                ?>

                <div class="text-center">
                    <h1>
                        <strong>Curso de <?php echo htmlspecialchars($curso["nome"]); ?></strong>
                        <?php if ($pode_emitir_certificado && $curso['temCertificado'] == 'sim') : ?>
                            <a href="../../controllers/gerar_certificado.php?id_curso=<?php echo $id_curso; ?>" class="btn btn-primary ml-3">Emitir Certificado</a>
                        <?php endif; ?>
                    </h1>
                    <p>Progresso: <?php echo round($percentual_conclusao, 2); ?>% (Necessário: <?php echo $percentual_necessario; ?>%)</p>
                </div>

                <div class="list-group">
                    <?php
                    if (empty($aulas_do_curso)) {
                        echo "<p class='text-center'>Não há aulas cadastradas nesse curso!</p>";
                    } else {
                        foreach ($aulas_do_curso as $aula) {
                            $is_watched = in_array($aula['id_aula'], $aulas_assistidas_ids);
                    ?>
                            <a href="ver_aula.php?id=<?php echo $aula['id_aula']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($aula["titulo"]); ?>
                                <?php if ($is_watched) : ?>
                                    <span class="badge badge-success badge-pill" title="Aula Assistida">&#10003;</span>
                                <?php endif; ?>
                            </a>
                    <?php
                        }
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>

<?php
include __DIR__ . "/../rodape.php";
?>
