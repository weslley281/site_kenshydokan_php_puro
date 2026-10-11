<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Auditoria</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-history text-danger me-2"></i>Trilha de Auditoria e Logs Oficiais
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Rastreabilidade total de operações, pesagens, notas e decisões</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Painel do Campeonato
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-shield-alt text-primary me-2"></i>Registros Cronológicos de Auditoria
        </h5>
        <span class="badge bg-light text-secondary border">Últimos <?= count($logs) ?> registros</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 50px;">#</th>
                        <th>Data / Hora</th>
                        <th>Operador / Usuário</th>
                        <th>Ação Realizada</th>
                        <th>Tabela / Registro</th>
                        <th>Detalhes / Payload</th>
                        <th class="pe-3">Justificativa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-check fa-3x text-secondary opacity-50 mb-3"></i>
                                <p class="mb-0">Nenhum registro de auditoria gerado ainda neste campeonato.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $idx => $log): ?>
                            <tr>
                                <td class="ps-3 text-muted"><?= $idx + 1 ?></td>
                                <td class="small fw-semibold text-dark" style="white-space: nowrap;">
                                    <?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-user me-1 text-secondary"></i><?= htmlspecialchars($log['usuario_nome'] ?? 'Sistema') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-dark" style="font-size: 0.72rem;">
                                        <?= htmlspecialchars($log['acao']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted d-block"><?= htmlspecialchars($log['tabela_afetada'] ?? '-') ?></small>
                                    <small class="fw-bold">ID: #<?= $log['registro_id'] ?? '-' ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($log['dados_depois'])): ?>
                                        <button class="btn btn-xs btn-outline-secondary py-0" type="button" data-bs-toggle="collapse" data-bs-target="#log_<?= $log['id_auditoria'] ?>">
                                            Ver JSON
                                        </button>
                                        <div class="collapse mt-1" id="log_<?= $log['id_auditoria'] ?>">
                                            <pre class="bg-light p-2 rounded small mb-0 border" style="max-height: 150px; max-width: 350px; overflow: auto; font-size: 0.7rem;"><?= htmlspecialchars($log['dados_depois']) ?></pre>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted pe-3">
                                    <?= htmlspecialchars($log['justificativa'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
