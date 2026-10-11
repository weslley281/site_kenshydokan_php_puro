<?php
$pageTitle = 'Pesagem Oficial | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/pesagemModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$inscricaoModel = new Inscricao();
$pesagemModel = new Pesagem();
$categoriaModel = new Categoria();

$inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato);
$historicoPesagens = $pesagemModel->buscarPorCampeonato($idCampeonato);
$categorias = $categoriaModel->buscarPorCampeonato($idCampeonato);

$totalGeral = count($inscricoes);
$pesadosAprovados = 0;
$pesadosReprovados = 0;
$pendentesPesagem = 0;

foreach ($inscricoes as $ins) {
    if ($ins['status_elegibilidade'] === 'aprovada') $pesadosAprovados++;
    elseif ($ins['status_elegibilidade'] === 'reprovada') $pesadosReprovados++;
    else $pendentesPesagem++;
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Pesagem</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-weight text-danger me-2"></i>Controle de Balança Oficial
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Data da Pesagem: <?= date('d/m/Y', strtotime($campeonato['data_pesagem'] ?? $campeonato['data_inicio'])) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="../controllers/relatorioController.php?id_campeonato=<?= $idCampeonato ?>&tipo=pesagem" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf me-1 text-danger"></i> PDF Pesagem
        </a>
        <a href="chaves.php?id=<?= $idCampeonato ?>" class="btn btn-danger">
            <i class="fas fa-sitemap me-1"></i> Ir para Chaveamento
        </a>
    </div>
</div>

<!-- Resumo (KPIs) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kpi-blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Total Atletas</div>
                    <div class="kpi-value text-primary"><?= $totalGeral ?></div>
                    <div class="small text-muted mt-1">Inscritos no campeonato</div>
                </div>
                <div class="kpi-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kpi-green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Aprovados no Peso</div>
                    <div class="kpi-value text-success"><?= $pesadosAprovados ?></div>
                    <div class="small text-success fw-bold mt-1">Aptos para sorteio</div>
                </div>
                <div class="kpi-icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kpi-gold">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Pendentes de Balança</div>
                    <div class="kpi-value text-warning"><?= $pendentesPesagem ?></div>
                    <div class="small text-muted mt-1">Aguardando pesagem</div>
                </div>
                <div class="kpi-icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Reprovados / Excesso</div>
                    <div class="kpi-value text-danger"><?= $pesadosReprovados ?></div>
                    <div class="small text-danger fw-bold mt-1">Acima do peso + tol.</div>
                </div>
                <div class="kpi-icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Fila e Histórico -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#tabFila" type="button">
            <i class="fas fa-list-ol me-1"></i> Fila de Atletas (<?= count($inscricoes) ?>)
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#tabHist" type="button">
            <i class="fas fa-history me-1"></i> Histórico de Aferições (<?= count($historicoPesagens) ?>)
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB 1: FILA -->
    <div class="tab-pane fade show active" id="tabFila">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-responsive">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Atleta</th>
                                <th>Dojo</th>
                                <th>Categoria Atual</th>
                                <th>Limites de Peso</th>
                                <th>Peso Decl.</th>
                                <th>Status Balança</th>
                                <th class="text-end pe-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscricoes as $idx => $ins): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <strong class="text-dark"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></strong>
                                        <small class="text-muted d-block"><?= htmlspecialchars($ins['graduacao_nome_snapshot']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($ins['dojo_snapshot']) ?></span>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold"><?= htmlspecialchars($ins['categoria_nome']) ?></div>
                                    </td>
                                    <td class="small">
                                        <?php if ($ins['exige_pesagem'] == 1): ?>
                                            <?= $ins['peso_min'] ? number_format($ins['peso_min'], 1) . 'kg' : 'Sem mín.' ?> até <strong><?= $ins['peso_max'] ? number_format($ins['peso_max'], 1) . 'kg' : 'Livre' ?></strong>
                                            <?php if ($ins['tolerancia_peso_kg'] > 0): ?>
                                                <span class="text-info">(+<?= number_format($ins['tolerancia_peso_kg'], 1) ?>kg tol.)</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Não exige pesagem</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $ins['peso_declarado'] ? number_format($ins['peso_declarado'], 1) . ' kg' : '-' ?>
                                    </td>
                                    <td>
                                        <?php if ($ins['status_elegibilidade'] === 'aprovada'): ?>
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprovado</span>
                                        <?php elseif ($ins['status_elegibilidade'] === 'pendente'): ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-balance-scale me-1"></i>Pendente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Reprovado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button class="btn btn-sm btn-primary" onclick="abrirModalPesagem(<?= htmlspecialchars(json_encode($ins)) ?>)">
                                            <i class="fas fa-weight me-1"></i> Pesar
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" title="Remanejar" onclick="abrirModalRemanejar(<?= $ins['id_inscricao'] ?>, '<?= addslashes($ins['nome_atleta_snapshot']) ?>')">
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

    <!-- TAB 2: HISTÓRICO -->
    <div class="tab-pane fade" id="tabHist">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 datatable-responsive">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Data / Hora</th>
                                <th>Atleta</th>
                                <th>Categoria</th>
                                <th>Tentativa</th>
                                <th>Peso Aferido</th>
                                <th>Resultado</th>
                                <th>Operador</th>
                                <th>Observações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historicoPesagens as $idx => $p): ?>
                                <tr>
                                    <td class="ps-3 text-muted"><?= $idx + 1 ?></td>
                                    <td class="small"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                                    <td><strong><?= htmlspecialchars($p['nome_atleta']) ?></strong></td>
                                    <td><?= htmlspecialchars($p['categoria_nome']) ?></td>
                                    <td><span class="badge bg-secondary"><?= $p['tentativa'] ?>ª</span></td>
                                    <td class="fw-bold"><?= number_format($p['peso_aferido'], 2, ',', '.') ?> kg</td>
                                    <td>
                                        <?php if ($p['resultado'] === 'aprovado'): ?>
                                            <span class="badge bg-success">Aprovado</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Reprovado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($p['operador_nome'] ?? 'Operador') ?></td>
                                    <td class="small text-muted"><?= htmlspecialchars($p['observacoes'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pesar -->
<div class="modal fade" id="modalPesagem" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/pesagemController.php">
                <input type="hidden" name="tipo" value="registrar_pesagem">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_inscricao" id="pesagemIdInscricao" value="">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-balance-scale text-danger me-2"></i>Aferição Oficial na Balança
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                            <input type="number" step="0.05" min="15" max="250" class="form-control text-center fw-bold fs-3" id="peso_aferido" name="peso_aferido" required placeholder="0.00" autofocus>
                            <span class="input-group-text fw-bold">KG</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="obs" class="form-label small fw-bold">Observações da Balança:</label>
                        <input type="text" class="form-control" id="obs" name="observacoes" placeholder="Ex: Pesado de calça de quimono...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">Salvar Aferição</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Remanejar Categoria -->
<div class="modal fade" id="modalRemanejar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/pesagemController.php">
                <input type="hidden" name="tipo" value="remanejar_categoria">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_inscricao" id="remanejarIdInscricao" value="">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-exchange-alt me-2"></i>Remanejar Categoria por Incompatibilidade de Peso
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Atleta: <strong id="remanejarNomeAtleta" class="text-dark"></strong></p>
                    <div class="mb-3">
                        <label for="nova_cat" class="form-label fw-bold">Nova Categoria de Destino: <span class="text-danger">*</span></label>
                        <select class="form-select" id="nova_cat" name="nova_categoria_id" required>
                            <option value="">-- Selecione a Categoria --</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>">
                                    [<?= strtoupper($cat['modalidade']) ?>] <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="just" class="form-label fw-bold">Justificativa Oficial: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="just" name="justificativa" rows="3" required placeholder="Motivo do remanejamento..."></textarea>
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

function abrirModalRemanejar(id, nome) {
    document.getElementById('remanejarIdInscricao').value = id;
    document.getElementById('remanejarNomeAtleta').textContent = nome;
    var modal = new bootstrap.Modal(document.getElementById('modalRemanejar'));
    modal.show();
}
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
