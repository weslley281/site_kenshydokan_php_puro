<?php
$pageTitle = 'Mesa de Tatame | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/tatameModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/lutaModel.php";

$idTatame = (int)($_GET['id'] ?? 0);
$tatameModel = new Tatame();
$tatame = $tatameModel->buscarPorId($idTatame);

if (!$tatame) {
    echo "<div class='alert alert-danger'>Tatame não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId((int)$tatame['id_campeonato']);

$lutaModel = new Luta();
$lutas = $lutaModel->buscarPorTatame($idTatame);

$lutaAtual = null;
$proximasLutas = [];
$lutasFinalizadas = [];

foreach ($lutas as $l) {
    if ($l['status'] === 'em_andamento') $lutaAtual = $l;
    elseif ($l['status'] === 'concluida') $lutasFinalizadas[] = $l;
    else $proximasLutas[] = $l;
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $tatame['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item"><a href="tatames.php?id=<?= $tatame['id_campeonato'] ?>" class="text-decoration-none">Tatames</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($tatame['nome']) ?></li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-desktop text-danger me-2"></i>Mesa de Arbitragem: <?= htmlspecialchars($tatame['nome']) ?>
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="telao.php?id=<?= $idTatame ?>" target="_blank" class="btn btn-danger">
            <i class="fas fa-tv me-1"></i> Abrir Telão do Tatame
        </a>
        <a href="tatames.php?id=<?= $tatame['id_campeonato'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<!-- Luta Ativa no Tatame -->
<div class="card border-0 shadow-sm mb-4 border-top border-4 border-danger">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-fist-raised text-danger me-2"></i>Combate Ativo na Quadra
        </h5>
        <?php if ($lutaAtual): ?>
            <span class="badge bg-danger animate-pulse fs-7">EM COMBATE</span>
        <?php else: ?>
            <span class="badge bg-success fs-7">TATAME DISPONÍVEL</span>
        <?php endif; ?>
    </div>
    <div class="card-body p-4">
        <?php if ($lutaAtual): ?>
            <div class="row align-items-center text-center g-3">
                <div class="col-md-5">
                    <div class="p-4 bg-danger bg-opacity-10 rounded border border-danger">
                        <span class="badge bg-danger mb-2 px-3 py-1 fs-7">CANTO VERMELHO (AKA)</span>
                        <h4 class="fw-bold text-danger mb-1"><?= htmlspecialchars($lutaAtual['nome_aka'] ?? 'AKA') ?></h4>
                        <span class="text-muted small"><?= htmlspecialchars($lutaAtual['dojo_aka'] ?? 'Dojo') ?></span>
                    </div>
                </div>

                <div class="col-md-2">
                    <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">VS</span>
                    <div class="fw-bold mt-2 text-dark">Luta #<?= $lutaAtual['numero_luta'] ?></div>
                    <small class="text-muted d-block"><?= htmlspecialchars($lutaAtual['categoria_nome'] ?? '') ?></small>
                </div>

                <div class="col-md-5">
                    <div class="p-4 bg-secondary bg-opacity-10 rounded border border-secondary">
                        <span class="badge bg-secondary mb-2 px-3 py-1 fs-7">CANTO BRANCO (SHIRO)</span>
                        <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($lutaAtual['nome_shiro'] ?? 'SHIRO') ?></h4>
                        <span class="text-muted small"><?= htmlspecialchars($lutaAtual['dojo_shiro'] ?? 'Dojo') ?></span>
                    </div>
                </div>

                <div class="col-12 mt-4 text-center">
                    <a href="sumula_kumite.php?id=<?= $lutaAtual['id_luta'] ?>" class="btn btn-danger btn-lg px-5 shadow fw-bold">
                        <i class="fas fa-stopwatch me-2"></i> OPERAR SÚMULA DIGITAL EM TEMPO REAL
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-4 text-muted">
                <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark">Nenhum combate em andamento nesta área agora.</h5>
                <p class="text-muted mb-0">Selecione uma luta da fila abaixo para iniciar a operação.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Fila e Histórico -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                    <i class="fas fa-list-ol text-primary me-2"></i>Fila de Combates (<?= count($proximasLutas) ?>)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3">Luta #</th>
                                <th>Categoria</th>
                                <th>Confronto (AKA vs SHIRO)</th>
                                <th class="text-end pe-3">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($proximasLutas)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhuma luta na fila.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($proximasLutas as $l): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark">#<?= $l['numero_luta'] ?></td>
                                        <td>
                                            <span class="small fw-semibold text-dark"><?= htmlspecialchars($l['categoria_nome'] ?? '') ?></span>
                                            <small class="text-muted d-block"><?= $l['rodada'] ?>ª Rodada</small>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <span class="text-danger fw-bold"><?= htmlspecialchars($l['nome_aka'] ?? 'Aguardando') ?></span> x 
                                                <span class="text-secondary fw-bold"><?= htmlspecialchars($l['nome_shiro'] ?? 'Aguardando') ?></span>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <?php if (!empty($l['id_inscricao_aka']) && !empty($l['id_inscricao_shiro'])): ?>
                                                <a href="sumula_kumite.php?id=<?= $l['id_luta'] ?>" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-play me-1"></i> Iniciar
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">Aguardando chave</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                    <i class="fas fa-history text-success me-2"></i>Lutas Finalizadas (<?= count($lutasFinalizadas) ?>)
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Confronto</th>
                                <th>Vencedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lutasFinalizadas)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Nenhuma luta finalizada ainda.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($lutasFinalizadas as $lf): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-dark">#<?= $lf['numero_luta'] ?></td>
                                        <td class="small">
                                            <span class="text-danger"><?= htmlspecialchars($lf['nome_aka'] ?? '') ?></span> vs 
                                            <span class="text-secondary"><?= htmlspecialchars($lf['nome_shiro'] ?? '') ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-trophy me-1"></i><?= htmlspecialchars($lf['nome_vencedor'] ?? 'Vencedor') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
