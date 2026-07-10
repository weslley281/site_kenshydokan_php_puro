<?php
$page_title = "Assistir Aula";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../models/aulaModel.php";

$aulaModelRepo = new AulaModel();

$id_aula = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_aula === 0) {
    header("Location: /cursos.php");
    exit;
}

$aula = $aulaModelRepo->buscarAula($id_aula);
if (!$aula) {
    echo "<p>Aula não encontrada.</p>";
    exit;
}

$aulas_assistidas_ids = [];
if (isset($_SESSION['id_usuario'])) {
    $aulas_assistidas_ids = $aulaModelRepo->getAulasAssistidasPorUsuario($_SESSION['id_usuario']);
}
$aula_ja_assistida = in_array($id_aula, $aulas_assistidas_ids);

// Busca próxima aula do mesmo curso
$proxima_aula = null;
try {
    include_once __DIR__ . "/../../db/conexao.php";
    $dbConnNext = new Conexao();
    $conexaoNext = $dbConnNext->conectar();
    if ($conexaoNext) {
        $stmtNext = $conexaoNext->prepare("
            SELECT id_aula, titulo 
            FROM aulas 
            WHERE id_curso = ? AND (num_ordenacao > ? OR (num_ordenacao = ? AND id_aula > ?))
            ORDER BY num_ordenacao ASC, id_aula ASC 
            LIMIT 1
        ");
        $id_curso_curr = intval($aula['id_curso']);
        $num_ord_curr = intval($aula['num_ordenacao']);
        $id_aula_curr = intval($aula['id_aula']);
        $stmtNext->bind_param("iiii", $id_curso_curr, $num_ord_curr, $num_ord_curr, $id_aula_curr);
        $stmtNext->execute();
        $resNext = $stmtNext->get_result();
        if ($resNext && $rowNext = $resNext->fetch_assoc()) {
            $proxima_aula = $rowNext;
        }
        $stmtNext->close();
        $conexaoNext->close();
    }
} catch (Throwable $t) {
    // Fail silently
}
?>


<div class="container mt-5">
    <div class="row">
        <?php include __DIR__ . "/_perfil_menu.php"; ?>
        <div class="col-lg-9 mb-4">
            <?php include __DIR__ . "/_perfil_info_card.php"; ?>
            <hr>
            <a href="assistir_aulas.php?id=<?php echo $aula['id_curso']; ?>" class="btn btn-secondary mb-3">Voltar para a lista de aulas</a>
            <hr>


            <div class="card">

                <div class="card-header">
                    <h1><?php echo htmlspecialchars($aula['titulo']); ?></h1>
                </div>
                <div class="card-body">
                    <div>
                        <?= $aula['aula']; ?>
                        
                    </div>
                </div>
                <form action="../../controllers/gerar_pdf_aula.php" method="post" target="_blank">
                    <input type="hidden" name="titulo" value="<?php echo htmlspecialchars($aula['titulo']); ?>">
                    <input type="hidden" name="conteudo" value="<?php echo htmlspecialchars($aula['aula']); ?>">
                    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center" style="gap: 15px;">
                        <div>
                            <?php if ($proxima_aula): ?>
                                <a href="ver_aula.php?id=<?php echo $proxima_aula['id_aula']; ?>" class="btn btn-danger rounded-pill font-weight-bold shadow-sm px-4">
                                    Próxima Aula: <?php echo htmlspecialchars($proxima_aula['titulo']); ?> <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-outline-primary rounded-pill font-weight-bold px-3 mr-2">Baixar PDF</button>
                            <button type="button" class="btn btn-success rounded-pill font-weight-bold px-4 marcar-assistido" data-id-aula="<?php echo $aula['id_aula']; ?>" <?php if ($aula_ja_assistida) echo 'disabled'; ?>>
                                <?php echo $aula_ja_assistida ? 'Assistido' : 'Marcar como Assistido'; ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userId = <?php echo json_encode($_SESSION['id_usuario'] ?? null); ?>;
        const button = document.querySelector('.marcar-assistido');

        if (button) {
            button.addEventListener('click', function() {
                markAulaAsWatched(this.dataset.idAula, this);
            });
        }

        async function markAulaAsWatched(aulaId, button) {
            if (!userId) {
                alert('Você precisa estar logado para marcar aulas como assistidas.');
                return;
            }
            try {
                const response = await fetch('../../controllers/marcar_aula_assistida.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id_aula=${aulaId}`
                });
                const data = await response.json();

                if (data.success) {
                    button.textContent = 'Assistido';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-secondary');
                    button.disabled = true;
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error marking aula as watched:', error);
                alert('Erro ao marcar aula como assistida.');
            }
        }
    });
</script>

<?php
include __DIR__ . "/rodape.php";
?>
