<?php
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/lutaModel.php";

$idCategoria = (int)($_GET['id'] ?? 0);
$categoria = (new Categoria())->buscarPorId($idCategoria);
$campAtualId = $categoria ? (int)$categoria['id_campeonato'] : 0; // informa o campeonato ao menu

$pageTitle = 'Lutas da categoria | Kenshydokan';
include_once __DIR__ . "/menu.php";

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$chave = (new Chave())->buscarPorCategoria($idCategoria);
$lutas = $chave ? (new Luta())->buscarPorChave((int)$chave['id_chave']) : [];

$porRodada = [];
foreach ($lutas as $l) {
    $porRodada[(int)$l['rodada']][] = $l;
}
$totalRodadas = $chave ? (int)$chave['total_rodadas'] : 0;

function nomeRodada(int $r, int $total): string {
    if ($r === $total) return 'Final';
    if ($r === $total - 1) return 'Semifinal';
    if ($r === $total - 2) return 'Quartas de final';
    return "{$r}ª rodada";
}
?>

<div class="page-head">
    <div>
        <h1><?= htmlspecialchars($categoria['nome']) ?></h1>
        <p>Clique em <strong>Começar luta</strong> para abrir o placar.</p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($chave): ?>
            <a href="../controllers/relatorioController.php?id_campeonato=<?= $categoria['id_campeonato'] ?>&tipo=chave&id_categoria=<?= $idCategoria ?>" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-print"></i> Imprimir</a>
        <?php endif; ?>
        <a href="chaves.php?id=<?= $categoria['id_campeonato'] ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
</div>

<?php if (!$chave): ?>
    <div class="card"><div class="empty">
        <i class="fas fa-shuffle"></i>As lutas desta categoria ainda não foram sorteadas.
        <form method="POST" action="../controllers/chaveamentoController.php" class="mt-3">
            <input type="hidden" name="tipo" value="gerar_chave">
            <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">
            <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-shuffle"></i> Sortear lutas</button>
        </form>
    </div></div>
<?php else: ?>
    <?php foreach ($porRodada as $rodada => $lista): ?>
        <h2 class="h5 mt-4 mb-2"><?= nomeRodada($rodada, $totalRodadas) ?></h2>
        <div class="card">
            <ul class="list-group list-group-flush">
            <?php foreach ($lista as $luta):
                $temDois = !empty($luta['id_inscricao_aka']) && !empty($luta['id_inscricao_shiro']);
                $venceuAka = $luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_aka'];
                $venceuShiro = $luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_shiro'];
                $nomeShiro = $luta['nome_shiro'] ?? (!empty($luta['id_inscricao_aka']) && $luta['status'] === 'concluida' ? 'Passa direto (sem adversário)' : 'A definir');
            ?>
                <li class="list-group-item py-3 d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="text-muted fw-bold">Luta <?= (int)$luta['numero_luta'] ?></span>
                        <span class="badge bg-danger fs-6 <?= $venceuAka ? '' : ($luta['id_vencedor'] ? 'opacity-50' : '') ?>">
                            <?= $venceuAka ? '🏆 ' : '' ?><?= htmlspecialchars($luta['nome_aka'] ?? 'A definir') ?>
                        </span>
                        <span class="text-muted">x</span>
                        <span class="badge bg-light text-dark border fs-6 <?= $venceuShiro ? '' : ($luta['id_vencedor'] ? 'opacity-50' : '') ?>">
                            <?= $venceuShiro ? '🏆 ' : '' ?><?= htmlspecialchars($nomeShiro) ?>
                        </span>
                    </div>
                    <div>
                        <?php if ($luta['status'] === 'concluida'): ?>
                            <span class="badge bg-success fs-6"><i class="fas fa-check"></i> Terminada</span>
                        <?php elseif ($temDois): ?>
                            <a href="sumula_kumite.php?id=<?= $luta['id_luta'] ?>" class="btn btn-danger btn-lg">
                                <i class="fas fa-play"></i> <?= $luta['status'] === 'em_andamento' ? 'Continuar luta' : 'Começar luta' ?>
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Aguardando lutas anteriores</span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include_once __DIR__ . "/rodape.php"; ?>
