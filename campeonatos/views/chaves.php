<?php
$pageTitle = 'Lutas | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/chaveModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = $cid;
$categorias = (new Categoria())->buscarPorCampeonato($idCampeonato);
$inscricaoModel = new Inscricao();
$chaveModel = new Chave();
?>

<div class="page-head">
    <div>
        <h1>5. Lutas</h1>
        <p>Em cada categoria, clique em <strong>Sortear lutas</strong>. Depois é só abrir e começar as lutas.</p>
    </div>
    <a href="resultados.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary">Próxima etapa: Resultados <i class="fas fa-arrow-right"></i></a>
</div>

<?php if (empty($categorias)): ?>
    <div class="alert alert-warning">Nenhuma categoria criada. <a href="categorias.php?id=<?= $idCampeonato ?>">Criar categoria</a></div>
<?php else: ?>
<div class="card">
    <ul class="list-group list-group-flush">
    <?php foreach ($categorias as $cat):
        $insc = $inscricaoModel->buscarPorCampeonato($idCampeonato, (int)$cat['id_categoria']);
        $liberados = count(array_filter($insc, fn($i) => $i['status_elegibilidade'] === 'aprovada'));
        $chave = $cat['modalidade'] === 'kumite' ? $chaveModel->buscarPorCategoria((int)$cat['id_categoria']) : null;
    ?>
        <li class="list-group-item py-3 d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <div>
                <strong class="fs-5"><?= htmlspecialchars($cat['nome']) ?></strong><br>
                <span class="text-muted"><?= $liberados ?> atleta(s) liberado(s)</span>
            </div>
            <div class="d-flex gap-2">
            <?php if ($cat['modalidade'] === 'kumite'): ?>
                <?php if ($chave): ?>
                    <a href="ver_chave.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-danger btn-lg"><i class="fas fa-hand-fist"></i> Ver lutas</a>
                    <form method="POST" action="../controllers/chaveamentoController.php" onsubmit="return confirm('Sortear de novo? As lutas atuais desta categoria serão apagadas.');">
                        <input type="hidden" name="tipo" value="gerar_chave">
                        <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                        <button type="submit" class="btn btn-outline-secondary btn-lg" title="Sortear de novo"><i class="fas fa-rotate"></i></button>
                    </form>
                <?php elseif ($liberados < 2): ?>
                    <span class="text-muted align-self-center">Precisa de pelo menos 2 atletas liberados</span>
                <?php else: ?>
                    <form method="POST" action="../controllers/chaveamentoController.php" onsubmit="return confirm('Sortear as lutas desta categoria agora?');">
                        <input type="hidden" name="tipo" value="gerar_chave">
                        <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                        <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-shuffle"></i> Sortear lutas</button>
                    </form>
                <?php endif; ?>
            <?php elseif ($cat['modalidade'] === 'kata'): ?>
                <a href="sumula_kata.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-danger btn-lg"><i class="fas fa-pen"></i> Dar notas (Kata)</a>
            <?php else: ?>
                <a href="sumula_tameshiwari.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-danger btn-lg"><i class="fas fa-pen"></i> Dar notas (Quebramento)</a>
            <?php endif; ?>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php include_once __DIR__ . "/rodape.php"; ?>
