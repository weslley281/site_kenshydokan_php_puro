<?php
$pageTitle = 'Resultados | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/classificacaoModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = $cid;
$categorias = (new Categoria())->buscarPorCampeonato($idCampeonato);
$classificacaoModel = new Classificacao();
$quadroMedalhas = $classificacaoModel->obterQuadroMedalhas($idCampeonato);

$medalha = [
    'ouro'   => ['🥇', '1º lugar'],
    'prata'  => ['🥈', '2º lugar'],
    'bronze' => ['🥉', '3º lugar'],
];
?>

<div class="page-head">
    <div>
        <h1>6. Resultados</h1>
        <p>Quando as lutas de uma categoria terminarem, clique em <strong>Confirmar resultado</strong>. O pódio é montado sozinho.</p>
    </div>
    <a href="../controllers/relatorioController.php?id_campeonato=<?= $idCampeonato ?>&tipo=medalhas" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-print"></i> Imprimir medalhas</a>
</div>

<div class="row g-4 mb-4">
<?php foreach ($categorias as $cat):
    $podio = $classificacaoModel->buscarPorCategoria((int)$cat['id_categoria']);
    $confirmado = !empty($podio);
?>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <strong><?= htmlspecialchars($cat['nome']) ?></strong>
                <?php if ($confirmado): ?>
                    <span class="badge bg-success"><i class="fas fa-check"></i> Confirmado</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Falta confirmar</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if ($confirmado): ?>
                    <?php foreach ($podio as $p): $m = $medalha[$p['medalha']] ?? ['', $p['colocacao'] . 'º lugar']; ?>
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            <span class="fs-3"><?= $m[0] ?></span>
                            <div>
                                <strong><?= htmlspecialchars($p['nome_atleta']) ?></strong><br>
                                <small class="text-muted"><?= $m[1] ?> · <?= htmlspecialchars($p['dojo']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">Termine as lutas (ou as notas) desta categoria e depois confirme aqui.</p>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white text-end">
                <form action="../controllers/homologacaoController.php" method="POST" onsubmit="return confirm('Confirmar o resultado desta categoria?');">
                    <input type="hidden" name="tipo" value="homologar_categoria">
                    <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                    <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                    <button type="submit" class="btn <?= $confirmado ? 'btn-outline-secondary' : 'btn-danger btn-lg' ?>">
                        <i class="fas fa-check"></i> <?= $confirmado ? 'Confirmar de novo' : 'Confirmar resultado' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php if (!empty($quadroMedalhas)): ?>
<div class="card">
    <div class="card-header py-3"><i class="fas fa-medal text-warning me-2"></i>Medalhas por dojo</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>Dojo</th><th class="text-center">🥇</th><th class="text-center">🥈</th><th class="text-center">🥉</th><th class="text-center">Total</th></tr></thead>
            <tbody>
            <?php foreach ($quadroMedalhas as $i => $q): ?>
                <tr>
                    <td class="fw-bold"><?= $i + 1 ?>º</td>
                    <td><strong><?= htmlspecialchars($q['dojo']) ?></strong></td>
                    <td class="text-center fs-5"><?= (int)$q['ouro'] ?></td>
                    <td class="text-center fs-5"><?= (int)$q['prata'] ?></td>
                    <td class="text-center fs-5"><?= (int)$q['bronze'] ?></td>
                    <td class="text-center fs-5 fw-bold"><?= (int)$q['total_medalhas'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include_once __DIR__ . "/rodape.php"; ?>
