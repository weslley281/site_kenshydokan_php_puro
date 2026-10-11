<?php
$pageTitle = 'Visualizar Chave | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/lutaModel.php";

$idCategoria = (int)($_GET['id'] ?? 0);
$categoriaModel = new Categoria();
$categoria = $categoriaModel->buscarPorId($idCategoria);

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId((int)$categoria['id_campeonato']);

$chaveModel = new Chave();
$chave = $chaveModel->buscarPorCategoria($idCategoria);

$lutaModel = new Luta();
$lutas = $chave ? $lutaModel->buscarPorChave((int)$chave['id_chave']) : [];

$lutasPorRodada = [];
foreach ($lutas as $l) {
    $lutasPorRodada[$l['rodada']][] = $l;
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $categoria['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item"><a href="chaves.php?id=<?= $categoria['id_campeonato'] ?>" class="text-decoration-none">Chaves</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($categoria['nome']) ?></li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-sitemap text-danger me-2"></i>Chave: <?= htmlspecialchars($categoria['nome']) ?>
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; <?= strtoupper($categoria['modalidade']) ?> &bull; <?= $categoria['idade_min'] ?> a <?= $categoria['idade_max'] ?> anos</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <?php if ($chave): ?>
            <a href="../controllers/relatorioController.php?id_campeonato=<?= $categoria['id_campeonato'] ?>&tipo=chave&id_categoria=<?= $idCategoria ?>" target="_blank" class="btn btn-outline-danger">
                <i class="fas fa-file-pdf me-1"></i> Imprimir Chave (PDF)
            </a>
        <?php endif; ?>
        <a href="chaves.php?id=<?= $categoria['id_campeonato'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Todas as Chaves
        </a>
    </div>
</div>

<?php if (!$chave): ?>
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="fas fa-sitemap fa-3x text-muted opacity-50 mb-3"></i>
            <h5 class="fw-bold text-dark">Chaveamento ainda não foi gerado</h5>
            <p class="text-muted mb-3">Esta categoria ainda não possui uma chave eliminatória sorteada.</p>
            <form method="POST" action="../controllers/chaveamentoController.php">
                <input type="hidden" name="tipo" value="gerar_chave">
                <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">
                <button type="submit" class="btn btn-danger px-4 fw-bold">
                    <i class="fas fa-random me-1"></i> Realizar Sorteio Agora
                </button>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Visualizador de Bracket Horizontal em Colunas de Rodada -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <span class="badge bg-danger me-2"><?= $chave['total_atletas'] ?> Atletas</span>
                <span class="badge bg-secondary me-2"><?= $chave['total_rodadas'] ?> Rodadas</span>
                <span class="text-muted small">Eliminatória Simples Kenshydokan</span>
            </div>
            <small class="text-muted">Gerado em: <?= date('d/m/Y H:i', strtotime($chave['created_at'])) ?></small>
        </div>

        <div class="card-body p-3 overflow-auto" style="min-height: 520px; background-color: #f8fafc;">
            <div class="d-flex gap-4 flex-nowrap" style="min-width: <?= count($lutasPorRodada) * 280 ?>px;">
                <?php
                $nomesRodadas = [1 => '1ª Rodada', 2 => 'Quartas de Final', 3 => 'Semifinais', 4 => 'Grande Final', 5 => 'Super Final'];
                $totalR = (int)$chave['total_rodadas'];
                ?>

                <?php foreach ($lutasPorRodada as $numRodada => $lutas): ?>
                    <?php
                    $labelRodada = $nomesRodadas[$numRodada] ?? "Rodada {$numRodada}";
                    if ($numRodada == $totalR) $labelRodada = "Grande Final";
                    elseif ($numRodada == $totalR - 1) $labelRodada = "Semifinais";
                    elseif ($numRodada == $totalR - 2) $labelRodada = "Quartas de Final";
                    ?>
                    <div class="flex-fill" style="min-width: 260px; max-width: 320px;">
                        <div class="text-center py-2 px-3 mb-3 bg-dark text-white rounded fw-bold fs-7 text-uppercase shadow-sm">
                            <?= $labelRodada ?> (<?= count($lutas) ?>)
                        </div>

                        <div class="d-flex flex-column justify-content-around h-100 gap-3">
                            <?php foreach ($lutas as $luta): ?>
                                <div class="card border-0 shadow-sm position-relative <?= $luta['status'] === 'concluida' ? 'bg-white' : '' ?>">
                                    <div class="card-header py-1 px-2 bg-light border-bottom d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                                        <span class="fw-bold text-secondary">Luta #<?= $luta['numero_luta'] ?></span>
                                        <?php if ($luta['status'] === 'concluida'): ?>
                                            <span class="badge bg-success" style="font-size: 0.65rem;">Concluída</span>
                                        <?php elseif ($luta['status'] === 'em_andamento'): ?>
                                            <span class="badge bg-danger" style="font-size: 0.65rem;">Em Luta</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary" style="font-size: 0.65rem;">Pendente</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body p-2">
                                        <!-- Canto Vermelho (AKA) -->
                                        <div class="p-1 mb-1 rounded d-flex align-items-center justify-content-between border-start border-3 border-danger <?= ($luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_aka']) ? 'bg-danger bg-opacity-10 fw-bold' : 'bg-white' ?>" style="font-size: 0.82rem;">
                                            <div class="text-truncate me-1">
                                                <span class="badge bg-danger p-1 me-1" style="font-size: 0.65rem;">AKA</span>
                                                <span title="<?= htmlspecialchars($luta['nome_aka'] ?? 'Aguardando') ?>">
                                                    <?= htmlspecialchars($luta['nome_aka'] ?? 'Aguardando Luta') ?>
                                                </span>
                                            </div>
                                            <?php if ($luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_aka']): ?>
                                                <i class="fas fa-trophy text-warning" title="Vencedor"></i>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Canto Branco (SHIRO) -->
                                        <div class="p-1 rounded d-flex align-items-center justify-content-between border-start border-3 border-secondary <?= ($luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_shiro']) ? 'bg-secondary bg-opacity-10 fw-bold' : 'bg-white' ?>" style="font-size: 0.82rem;">
                                            <div class="text-truncate me-1">
                                                <span class="badge bg-secondary p-1 me-1" style="font-size: 0.65rem;">SHIRO</span>
                                                <span title="<?= htmlspecialchars($luta['nome_shiro'] ?? 'Aguardando') ?>">
                                                    <?= htmlspecialchars($luta['nome_shiro'] ?? ($luta['status'] === 'concluida' ? 'BYE (Isento)' : 'Aguardando Luta')) ?>
                                                </span>
                                            </div>
                                            <?php if ($luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_shiro']): ?>
                                                <i class="fas fa-trophy text-warning" title="Vencedor"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-footer p-1 bg-white border-top text-center" style="font-size: 0.75rem;">
                                        <?php if (!empty($luta['id_inscricao_aka']) && !empty($luta['id_inscricao_shiro'])): ?>
                                            <a href="sumula_kumite.php?id=<?= $luta['id_luta'] ?>" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;">
                                                <i class="fas fa-clipboard-list me-1"></i> Abrir Súmula
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size: 0.7rem;">Aguardando definição</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include_once __DIR__ . "/rodape.php"; ?>
