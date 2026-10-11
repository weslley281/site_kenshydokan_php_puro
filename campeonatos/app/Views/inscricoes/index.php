<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Inscrições</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-id-card text-danger me-2"></i>Inscrições de Atletas
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Data de Referência: <?= date('d/m/Y', strtotime($campeonato['data_referencia_idade'])) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="/relatorios/<?= $campeonato['id_campeonato'] ?>/inscritos" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf me-1 text-danger"></i> PDF Relação
        </a>
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/inscricoes/nova" class="btn btn-danger">
            <i class="fas fa-user-plus me-1"></i> Nova Inscrição
        </a>
    </div>
</div>

<!-- Cards de Resumo -->
<?php
$totalInscritos = count($inscricoes);
$totalAprovados = 0;
$totalPendentes = 0;
$totalReprovados = 0;
foreach ($inscricoes as $ins) {
    if ($ins['status_elegibilidade'] === 'aprovada') $totalAprovados++;
    elseif ($ins['status_elegibilidade'] === 'pendente') $totalPendentes++;
    else $totalReprovados++;
}
?>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Total Inscritos</h6>
                        <span class="fs-4 fw-bold text-dark"><?= $totalInscritos ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success p-3 rounded">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Aprovados / Aptos</h6>
                        <span class="fs-4 fw-bold text-success"><?= $totalAprovados ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning p-3 rounded">
                        <i class="fas fa-hourglass-half fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Aguardando Pesagem</h6>
                        <span class="fs-4 fw-bold text-warning"><?= $totalPendentes ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger bg-opacity-10 text-danger p-3 rounded">
                        <i class="fas fa-times-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Irregulares / Reprovados</h6>
                        <span class="fs-4 fw-bold text-danger"><?= $totalReprovados ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros e Listagem -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <form method="GET" action="/campeonatos/<?= $campeonato['id_campeonato'] ?>/inscricoes" class="row g-2 align-items-center">
            <div class="col-md-5">
                <label for="filtroCategoria" class="form-label small text-muted mb-1 fw-bold">Filtrar por Categoria:</label>
                <select name="categoria_id" id="filtroCategoria" class="form-select select2-simple" onchange="this.form.submit()">
                    <option value="">-- Todas as Categorias (<?= count($inscricoes) ?> atletas) --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>" <?= ($categoriaAtiva == $cat['id_categoria']) ? 'selected' : '' ?>>
                            [<?= strtoupper($cat['modalidade']) ?>] <?= htmlspecialchars($cat['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-7 text-md-end mt-3 mt-md-0 pt-md-3">
                <span class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i> Mostrando <strong><?= count($inscricoes) ?></strong> atleta(s) cadastrado(s)
                </span>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 50px;">#</th>
                        <th>Atleta (Filiado)</th>
                        <th>Dojo / Academia</th>
                        <th>Graduação</th>
                        <th>Idade / Sexo</th>
                        <th>Categoria Inscrita</th>
                        <th>Peso Decl.</th>
                        <th>Elegibilidade</th>
                        <th class="text-end pe-3" style="width: 140px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inscricoes)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">Nenhuma inscrição encontrada para este campeonato.</p>
                                <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/inscricoes/nova" class="btn btn-sm btn-danger mt-2">
                                    Cadastrar Primeira Inscrição
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inscricoes as $idx => $ins): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></div>
                                    <small class="text-muted">Cód. Filiado: #<?= $ins['id_filiado'] ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-shield-alt text-danger me-1"></i><?= htmlspecialchars($ins['dojo_snapshot']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                        <?= htmlspecialchars($ins['graduacao_nome_snapshot']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $ins['sexo'] === 'M' ? 'bg-primary' : 'bg-danger' ?> bg-opacity-10 text-<?= $ins['sexo'] === 'M' ? 'primary' : 'danger' ?>">
                                        <?= $ins['sexo'] === 'M' ? 'Masc' : 'Fem' ?>
                                    </span>
                                    <span class="small text-muted ms-1"><?= $ins['idade_evento'] ?> anos</span>
                                </td>
                                <td>
                                    <div class="fw-semibold small text-dark"><?= htmlspecialchars($ins['categoria_nome'] ?? 'Categoria') ?></div>
                                    <small class="badge bg-dark bg-opacity-75 text-white" style="font-size: 0.7rem;">
                                        <?= strtoupper($ins['categoria_modalidade'] ?? '') ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="fw-bold"><?= $ins['peso_declarado'] ? number_format($ins['peso_declarado'], 1, ',', '.') . ' kg' : '-' ?></span>
                                </td>
                                <td>
                                    <?php if ($ins['status_elegibilidade'] === 'aprovada'): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprovada</span>
                                    <?php elseif ($ins['status_elegibilidade'] === 'pendente'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pendente</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger" title="<?= htmlspecialchars($ins['motivo_ineligibilidade'] ?? '') ?>">
                                            <i class="fas fa-times me-1"></i><?= ucfirst($ins['status_elegibilidade']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Ações
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <button class="dropdown-item text-success" onclick="abrirModalStatus(<?= $ins['id_inscricao'] ?>, 'aprovada', '<?= addslashes($ins['nome_atleta_snapshot']) ?>')">
                                                    <i class="fas fa-check-circle me-2"></i>Aprovar Atleta
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-warning" onclick="abrirModalStatus(<?= $ins['id_inscricao'] ?>, 'pendente', '<?= addslashes($ins['nome_atleta_snapshot']) ?>')">
                                                    <i class="fas fa-clock me-2"></i>Marcar Pendente
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-danger" onclick="abrirModalStatus(<?= $ins['id_inscricao'] ?>, 'reprovada', '<?= addslashes($ins['nome_atleta_snapshot']) ?>')">
                                                    <i class="fas fa-times-circle me-2"></i>Desclassificar / Reprovar
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/pesagem">
                                                    <i class="fas fa-weight me-2 text-info"></i>Ir para Pesagem
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Alterar Status -->
<div class="modal fade" id="modalAlterarStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form id="formStatusInscricao" method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="status" id="modalStatusInput" value="">
                
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6">
                        <i class="fas fa-user-edit me-2"></i>Atualizar Status da Inscrição
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Alterando status de: <strong id="modalAtletaNome" class="text-danger"></strong><br>
                        Novo status: <span id="modalStatusBadge" class="badge"></span>
                    </p>
                    <div class="mb-3">
                        <label for="motivoStatus" class="form-label small fw-bold">Justificativa / Motivo (obrigatório para reprovação):</label>
                        <textarea class="form-control" id="motivoStatus" name="motivo" rows="3" placeholder="Informe a razão ou observação oficial..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Alteração</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalStatus(idInscricao, status, atletaNome) {
    document.getElementById('formStatusInscricao').action = '/inscricoes/' + idInscricao + '/status';
    document.getElementById('modalStatusInput').value = status;
    document.getElementById('modalAtletaNome').textContent = atletaNome;
    
    var badge = document.getElementById('modalStatusBadge');
    badge.textContent = status.toUpperCase();
    badge.className = 'badge ' + (status === 'aprovada' ? 'bg-success' : (status === 'pendente' ? 'bg-warning text-dark' : 'bg-danger'));
    
    var modal = new bootstrap.Modal(document.getElementById('modalAlterarStatus'));
    modal.show();
}

$(document).ready(function() {
    $('.select2-simple').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
