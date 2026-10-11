<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Tatames</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-th-large text-danger me-2"></i>Gestão de Áreas de Combate (Tatames)
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Controle centralizado de quadras, mesas de arbitragem e telões públicos</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNovoTatame">
            <i class="fas fa-plus me-1"></i> Adicionar Tatame
        </button>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($tatames)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="fas fa-th-large fa-3x text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">Nenhum tatame cadastrado ainda</h5>
                    <p class="text-muted mb-3">Adicione as áreas de combate para distribuir as lutas e ativar as mesas de arbitragem e telões.</p>
                    <button class="btn btn-danger px-4" data-bs-toggle="modal" data-bs-target="#modalNovoTatame">
                        <i class="fas fa-plus me-1"></i> Cadastrar Tatame 1
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($tatames as $tatame): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 <?= $tatame['status'] === 'em_luta' ? 'border-top border-4 border-danger' : 'border-top border-4 border-success' ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-square text-danger me-2"></i><?= htmlspecialchars($tatame['nome']) ?>
                            </h4>
                            <?php if ($tatame['status'] === 'em_luta'): ?>
                                <span class="badge bg-danger animate-pulse">
                                    <i class="fas fa-fist-raised me-1"></i>Em Combate
                                </span>
                            <?php elseif ($tatame['status'] === 'pausa'): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-pause me-1"></i>Em Pausa
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Disponível
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="p-3 bg-light rounded mb-3 small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Área Oficial:</span>
                                <strong class="text-dark">Tatame #<?= $tatame['numero'] ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Luta em Andamento:</span>
                                <?php if ($tatame['luta_em_andamento_id']): ?>
                                    <strong class="text-danger">Luta #<?= $tatame['luta_em_andamento_id'] ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Nenhuma no momento</span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Operador / Mesário:</span>
                                <strong><?= htmlspecialchars($tatame['operador_nome'] ?? 'Livre') ?></strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="/tatames/<?= $tatame['id_tatame'] ?>/painel" class="btn btn-outline-dark">
                                <i class="fas fa-desktop me-1"></i> Painel da Mesa (Mesário)
                            </a>
                            <a href="/tatames/<?= $tatame['id_tatame'] ?>/telao" target="_blank" class="btn btn-danger">
                                <i class="fas fa-tv me-1"></i> Abrir Telão do Tatame (Público)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Novo Tatame -->
<div class="modal fade" id="modalNovoTatame" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="/tatames">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-plus-circle text-danger me-2"></i>Cadastrar Nova Área de Tatame
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numeroTatame" class="form-label fw-bold">Número da Área / Tatame: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="numeroTatame" name="numero" value="<?= count($tatames) + 1 ?>" min="1" max="20" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomeTatame" class="form-label fw-bold">Identificação / Nome: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomeTatame" name="nome" value="Tatame <?= count($tatames) + 1 ?>" required placeholder="Ex: Tatame 1, Tatame Principal, etc.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Salvar Tatame</button>
                </div>
            </form>
        </div>
    </div>
</div>
