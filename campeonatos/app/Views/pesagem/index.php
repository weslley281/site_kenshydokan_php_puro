<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Pesagem Oficial</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-weight text-danger me-2"></i>Controle de Balança e Pesagem Oficial
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Data de Pesagem: <?= date('d/m/Y', strtotime($campeonato['data_pesagem'] ?? $campeonato['data_inicio'])) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="/relatorios/<?= $campeonato['id_campeonato'] ?>/pesagem" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf me-1 text-danger"></i> PDF Relatório de Pesagem
        </a>
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/chaves" class="btn btn-danger">
            <i class="fas fa-sitemap me-1"></i> Ir para Chaveamento
        </a>
    </div>
</div>

<!-- Resumo da Pesagem -->
<?php
$totalGeral = count($inscricoes);
$pesadosAprovados = 0;
$pesadosReprovados = 0;
$pendentesPesagem = 0;

foreach ($inscricoes as $ins) {
    if ($ins['status_elegibilidade'] === 'aprovada') {
        $pesadosAprovados++;
    } elseif ($ins['status_elegibilidade'] === 'reprovada') {
        $pesadosReprovados++;
    } else {
        $pendentesPesagem++;
    }
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
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Total Atletas</h6>
                        <span class="fs-4 fw-bold text-dark"><?= $totalGeral ?></span>
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
                        <i class="fas fa-balance-scale-right fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Aprovados no Peso</h6>
                        <span class="fs-4 fw-bold text-success"><?= $pesadosAprovados ?></span>
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
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Pendentes de Balança</h6>
                        <span class="fs-4 fw-bold text-warning"><?= $pendentesPesagem ?></span>
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
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Reprovados / Excedentes</h6>
                        <span class="fs-4 fw-bold text-danger"><?= $pesadosReprovados ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs de Navegação -->
<ul class="nav nav-tabs mb-3" id="pesagemTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-semibold" id="fila-tab" data-bs-toggle="tab" data-bs-target="#fila" type="button" role="tab">
            <i class="fas fa-list-ol me-2"></i>Fila de Atletas para Pesagem (<?= count($inscricoes) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab">
            <i class="fas fa-history me-2"></i>Histórico e Aferições Registradas (<?= count($historicoPesagens) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="pesagemTabsContent">
    <!-- TAB 1: FILA DE ATLETAS -->
    <div class="tab-pane fade show active" id="fila" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-responsive">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3" style="width: 50px;">#</th>
                                <th>Atleta</th>
                                <th>Dojo</th>
                                <th>Categoria Atual</th>
                                <th>Limites de Peso</th>
                                <th>Peso Decl.</th>
                                <th>Status Balança</th>
                                <th class="text-end pe-3" style="width: 180px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscricoes as $idx => $ins): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($ins['graduacao_nome_snapshot']) ?> &bull; <?= $ins['idade_evento'] ?> anos</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($ins['dojo_snapshot']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small"><?= htmlspecialchars($ins['categoria_nome']) ?></div>
                                        <small class="badge bg-dark bg-opacity-75 text-white" style="font-size: 0.65rem;">
                                            <?= strtoupper($ins['categoria_modalidade']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($ins['exige_pesagem'] == 1): ?>
                                            <span class="small text-muted">
                                                <?= $ins['peso_min'] ? number_format($ins['peso_min'], 1, ',', '.') . 'kg' : 'Sem mín.' ?> 
                                                até 
                                                <strong><?= $ins['peso_max'] ? number_format($ins['peso_max'], 1, ',', '.') . 'kg' : 'Livre' ?></strong>
                                                <?php if ($ins['tolerancia_peso_kg'] > 0): ?>
                                                    <span class="text-info">(+<?= number_format($ins['tolerancia_peso_kg'], 1, ',', '.') ?>kg tol.)</span>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border">Não exige pesagem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $ins['peso_declarado'] ? number_format($ins['peso_declarado'], 1, ',', '.') . ' kg' : '-' ?>
                                    </td>
                                    <td>
                                        <?php if ($ins['status_elegibilidade'] === 'aprovada'): ?>
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprovado</span>
                                        <?php elseif ($ins['status_elegibilidade'] === 'pendente'): ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-balance-scale me-1"></i>Pendente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger" title="<?= htmlspecialchars($ins['motivo_ineligibilidade'] ?? '') ?>">
                                                <i class="fas fa-times me-1"></i>Reprovado
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button class="btn btn-sm btn-primary" onclick="abrirModalPesagem(<?= htmlspecialchars(json_encode($ins)) ?>)">
                                            <i class="fas fa-weight me-1"></i> Pesar
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" title="Remanejar Categoria" onclick="abrirModalRemanejar(<?= $ins['id_inscricao'] ?>, '<?= addslashes($ins['nome_atleta_snapshot']) ?>', <?= $ins['id_categoria'] ?>)">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: HISTÓRICO DE PESAGENS -->
    <div class="tab-pane fade" id="historico" role="tabpanel">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-responsive">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3" style="width: 50px;">#</th>
                                <th>Data / Hora</th>
                                <th>Atleta</th>
                                <th>Categoria</th>
                                <th>Tentativa</th>
                                <th>Peso Aferido</th>
                                <th>Resultado</th>
                                <th>Operador Balança</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($historicoPesagens)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Nenhuma pesagem realizada ainda neste campeonato.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($historicoPesagens as $idx => $p): ?>
                                    <tr>
                                        <td class="ps-3 text-muted"><?= $idx + 1 ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($p['nome_atleta']) ?></td>
                                        <td><?= htmlspecialchars($p['categoria_nome']) ?></td>
                                        <td><span class="badge bg-secondary"><?= $p['tentativa'] ?>ª</span></td>
                                        <td class="fw-bold fs-6"><?= number_format($p['peso_aferido'], 2, ',', '.') ?> kg</td>
                                        <td>
                                            <?php if ($p['resultado'] === 'aprovado'): ?>
                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprovado</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Reprovado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($p['operador_nome'] ?? 'Operador Oficial') ?></td>
                                        <td class="small text-muted"><?= htmlspecialchars($p['observacoes'] ?? '-') ?></td>
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

<!-- Modal Registrar Pesagem -->
<div class="modal fade" id="modalPesagem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="/pesagem" id="formPesagem">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">
                <input type="hidden" name="id_inscricao" id="pesagemIdInscricao" value="">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6">
                        <i class="fas fa-balance-scale text-danger me-2"></i>Aferição de Peso na Balança Oficial
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-3 bg-light rounded border">
                        <div class="fw-bold fs-5 text-dark" id="pesagemNomeAtleta"></div>
                        <div class="text-muted small" id="pesagemDojoAtleta"></div>
                        <div class="mt-2 text-primary fw-semibold" id="pesagemCategoriaNome"></div>
                        <div class="small text-secondary mt-1" id="pesagemLimitesTexto"></div>
                    </div>

                    <div class="mb-3">
                        <label for="peso_aferido" class="form-label fw-bold">Peso Aferido na Balança (kg): <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <input type="number" step="0.05" min="15" max="250" class="form-control text-center fw-bold fs-3 text-dark" id="peso_aferido" name="peso_aferido" required placeholder="0.00" autofocus>
                            <span class="input-group-text fw-bold">KG</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="obs_pesagem" class="form-label small fw-bold">Observações do Árbitro / Mesário:</label>
                        <input type="text" class="form-control" id="obs_pesagem" name="observacoes" placeholder="Ex: Pesado com quimono, descalço, etc.">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="fas fa-check-circle me-1"></i> Salvar Aferição
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Remanejar Categoria -->
<div class="modal fade" id="modalRemanejar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="formRemanejar" action="">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-exchange-alt me-2"></i>Remanejar Categoria por Incompatibilidade de Peso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Atleta: <strong id="remanejarAtletaNome" class="text-dark"></strong>
                    </p>
                    <div class="mb-3">
                        <label for="nova_categoria_id" class="form-label fw-bold">Nova Categoria de Destino: <span class="text-danger">*</span></label>
                        <select class="form-select" id="nova_categoria_id" name="nova_categoria_id" required>
                            <option value="">-- Selecione a nova categoria --</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>">
                                    [<?= strtoupper($cat['modalidade']) ?>] <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="justificativa" class="form-label fw-bold">Justificativa Oficial: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="justificativa" name="justificativa" rows="3" required placeholder="Ex: Atleta excedeu limite de peso da categoria original e foi realocado de acordo com o regulamento."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Confirmar Remanejamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalPesagem(ins) {
    document.getElementById('pesagemIdInscricao').value = ins.id_inscricao;
    document.getElementById('pesagemNomeAtleta').textContent = ins.nome_atleta_snapshot;
    document.getElementById('pesagemDojoAtleta').textContent = ins.dojo_snapshot + ' (' + ins.graduacao_nome_snapshot + ')';
    document.getElementById('pesagemCategoriaNome').textContent = 'Categoria: ' + ins.categoria_nome;
    
    var limites = 'Limite: ';
    if (ins.peso_min) limites += parseFloat(ins.peso_min).toFixed(1) + 'kg ';
    if (ins.peso_max) limites += 'até ' + parseFloat(ins.peso_max).toFixed(1) + 'kg';
    else limites += 'Livre';
    if (ins.tolerancia_peso_kg > 0) limites += ' (Tolerância: +' + parseFloat(ins.tolerancia_peso_kg).toFixed(1) + 'kg)';
    
    document.getElementById('pesagemLimitesTexto').textContent = limites;
    document.getElementById('peso_aferido').value = ins.peso_declarado ? parseFloat(ins.peso_declarado).toFixed(2) : '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalPesagem'));
    modal.show();
}

function abrirModalRemanejar(idInscricao, atletaNome, catAtualId) {
    document.getElementById('formRemanejar').action = '/pesagem/' + idInscricao + '/remanejar';
    document.getElementById('remanejarAtletaNome').textContent = atletaNome;
    
    var select = document.getElementById('nova_categoria_id');
    select.value = '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalRemanejar'));
    modal.show();
}
</script>
