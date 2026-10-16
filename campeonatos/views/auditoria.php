<?php
$pageTitle = 'Histórico de alterações | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";

$idCampeonato = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$campeonatoModel = new Campeonato();
$campeonato = $idCampeonato > 0 ? $campeonatoModel->buscarPorId($idCampeonato) : null;

$auditoriaModel = new Auditoria();
if ($idCampeonato > 0) {
    $logs = $auditoriaModel->buscarPorCampeonato($idCampeonato);
} else {
    $logs = $auditoriaModel->buscarRecentes(250);
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <?php if ($campeonato): ?>
                    <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page">Histórico de alterações</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-history text-danger me-2"></i>Histórico de alterações
        </h2>
        <p class="text-muted small mb-0">
            <?= $campeonato ? htmlspecialchars($campeonato['titulo']) : 'Veja quem fez cada mudança no sistema de campeonatos.' ?>
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <?php if ($campeonato): ?>
            <a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Voltar às etapas
            </a>
        <?php else: ?>
            <a href="campeonatos.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Ver Campeonatos
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-list text-primary me-2"></i>Alterações registradas (<?= count($logs) ?>)
        </h5>
        <span class="badge bg-light text-dark border">Registro preservado</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 140px;">Data / Hora</th>
                        <th>Ação</th>
                        <th>Quem</th>
                        <th>Área / item</th>
                        <th>Detalhes</th>
                        <th>Motivo</th>
                        <th class="pe-3" style="width: 110px;">IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Nenhuma alteração registrada ainda.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td class="ps-3 small font-monospace text-muted">
                                    <?= date('d/m/Y H:i:s', strtotime($l['created_at'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-dark font-monospace"><?= htmlspecialchars($l['acao']) ?></span>
                                </td>
                                <td class="small fw-bold text-dark">
                                    <?= htmlspecialchars($l['usuario_nome'] ?? 'Sistema / Cron') ?>
                                </td>
                                <td class="small text-muted font-monospace">
                                    <?= htmlspecialchars($l['tabela_afetada'] ?? '-') ?> #<?= $l['registro_id'] ?? '-' ?>
                                </td>
                                <td class="small">
                                    <?php if (!empty($l['dados_depois'])): ?>
                                        <details>
                                            <summary class="text-primary cursor-pointer small">Ver dados</summary>
                                            <pre class="bg-light p-2 rounded mt-1 border small font-monospace" style="max-width: 350px; max-height: 150px; overflow: auto;"><?= htmlspecialchars($l['dados_depois']) ?></pre>
                                        </details>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted">
                                    <?= htmlspecialchars($l['justificativa'] ?? '-') ?>
                                </td>
                                <td class="pe-3 small font-monospace text-muted">
                                    <?= htmlspecialchars($l['ip_address'] ?? '127.0.0.1') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
