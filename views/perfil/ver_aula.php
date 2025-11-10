<?php
$page_title = "Assistir Aula";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../repositorios/AulaRepositorio.php";

$aulaRepositorio = new AulaRepositorio();

$id_aula = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_aula === 0) {
    // Redirect or show an error if no ID is provided
    header("Location: /cursos.php"); // Or your main courses list
    exit;
}

$aula = $aulaRepositorio->buscarAula($id_aula);
if (!$aula) {
    echo "<p>Aula não encontrada.</p>";
    exit;
}

$aulas_assistidas_ids = [];
if (isset($_SESSION['id_usuario'])) {
    $aulas_assistidas_ids = $aulaRepositorio->getAulasAssistidasPorUsuario($_SESSION['id_usuario']);
}
$aula_ja_assistida = in_array($id_aula, $aulas_assistidas_ids);

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
                        <?= $aula['aula']; //var_dump($aula['aula']); ?>

                    </div>
                </div>
                <form action="../../controllers/gerar_pdf_aula.php" method="post" target="_blank">
                    <input type="hidden" name="titulo" value="<?php echo htmlspecialchars($aula['titulo']); ?>">
                    <input type="hidden" name="conteudo" value="<?php echo htmlspecialchars($aula['aula']); ?>">
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Baixar PDF</button>
                        <button type="button" class="btn btn-success marcar-assistido" data-id-aula="<?php echo $aula['id_aula']; ?>" <?php if ($aula_ja_assistida) echo 'disabled'; ?>>
                            <?php echo $aula_ja_assistida ? 'Assistido' : 'Marcar como Assistido'; ?>
                        </button>
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
include __DIR__ . "/../rodape.php";
?>