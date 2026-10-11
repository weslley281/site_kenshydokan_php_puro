<?php
$pageTitle = 'Inscrições | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$idCategoriaFiltro = !empty($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : null;

$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$categoriaModel = new Categoria();
$inscricaoModel = new Inscricao();

$categorias = $categoriaModel->buscarPorCampeonato($idCampeonato);
$inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato, $idCategoriaFiltro);

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

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Inscrições</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-id-card text-danger me-2"></i>Inscrições de Atletas
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Data Ref. Idade: <?= date('d/m/Y', strtotime($campeonato['data_referencia_idade'])) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="../controllers/relatorioController.php?id_campeonato=<?= $idCampeonato ?>&tipo=inscritos" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf me-1 text-danger"></i> PDF Relação
        </a>
        <a href="criar_inscricao.php?id=<?= $idCampeonato ?>" class="btn btn-danger">
            <i class="fas fa-user-plus me-1"></i> Nova Inscrição
        </a>
    </div>
</div>

<!-- Resumo -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Total Inscritos</div>
            <div class="fs-4 fw-bold text-dark"><?= $totalInscritos ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-success border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Aprovados / Aptos</div>
            <div class="fs-4 fw-bold text-success"><?= $totalAprovados ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Aguardando Balança</div>
            <div class="fs-4 fw-bold text-warning"><?= $totalPendentes ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Reprovados / Irregulares</div>
            <div class="fs-4 fw-bold text-danger"><?= $totalReprovados ?></div>
        </div>
    </div>
</div>

<!-- Filtros e Tabela -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <form method="GET" action="inscricoes.php" class="row g-2 align-items-center">
            <input type="hidden" name="id" value="<?= $idCampeonato ?>">
            <div class="col-md-5">
                <select name="categoria_id" class="form-select select2-enable" onchange="this.form.submit()">
                    <option value="">-- Todas as Categorias (<?= count($inscricoes) ?> atletas) --</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>" <?= ($idCategoriaFiltro == $cat['id_categoria']) ? 'selected' : '' ?>>
                            [<?= strtoupper($cat['modalidade']) ?>] <?= htmlspecialchars($cat['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-7 text-md-end small text-muted">
                <i class="fas fa-info-circle me-1"></i> Filtrando atletas por categoria oficial
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Atleta (Filiado)</th>
                        <th>Dojo / Academia</th>
                        <th>Graduação</th>
                        <th>Idade / Sexo</th>
                        <th>Categoria</th>
                        <th>Peso Decl.</th>
                        <th>Elegibilidade</th>
                        <th class="text-end pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inscricoes)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                Nenhuma inscrição encontrada.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inscricoes as $idx => $ins): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></strong>
                                    <small class="text-muted d-block">ID Filiado: #<?= $ins['id_filiado'] ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= htmlspecialchars($ins['dojo_snapshot']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                        <?= htmlspecialchars($ins['graduacao_nome_snapshot']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="small fw-semibold"><?= $ins['sexo'] === 'M' ? 'Masc' : 'Fem' ?></span>, 
                                    <span class="text-muted small"><?= $ins['idade_evento'] ?> anos</span>
                                </td>
                                <td>
                                    <div class="small fw-bold text-dark"><?= htmlspecialchars($ins['categoria_nome']) ?></div>
                                    <span class="badge bg-dark" style="font-size: 0.65rem;"><?= strtoupper($ins['categoria_modalidade']) ?></span>
                                </td>
                                <td>
                                    <?= $ins['peso_declarado'] ? number_format($ins['peso_declarado'], 1) . ' kg' : '-' ?>
                                </td>
                                <td>
                                    <?php if ($ins['status_elegibilidade'] === 'aprovada'): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Aprovada</span>
                                    <?php elseif ($ins['status_elegibilidade'] === 'pendente'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pendente</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Reprovada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
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
                                                <i class="fas fa-times-circle me-2"></i>Reprovar / Desclassificar
                                            </button>
                                        </li>
                                    </ul>
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
<div class="modal fade" id="modalStatus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/inscricaoController.php">
                <input type="hidden" name="tipo" value="atualizar_status">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_inscricao" id="statusIdInscricao" value="">
                <input type="hidden" name="status" id="statusNovoValor" value="">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-user-edit text-danger me-2"></i>Alterar Status da Inscrição
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Atleta: <strong id="statusNomeAtleta" class="text-danger"></strong><br>
                        Novo Status: <span id="statusBadge" class="badge"></span>
                    </p>
                    <div class="mb-3">
                        <label for="motivoStatus" class="form-label small fw-bold">Justificativa / Motivo:</label>
                        <textarea class="form-control" id="motivoStatus" name="motivo" rows="3" placeholder="Observações oficiais..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalStatus(id, status, nome) {
    document.getElementById('statusIdInscricao').value = id;
    document.getElementById('statusNovoValor').value = status;
    document.getElementById('statusNomeAtleta').textContent = nome;

    var badge = document.getElementById('statusBadge');
    badge.textContent = status.toUpperCase();
    badge.className = 'badge ' + (status === 'aprovada' ? 'bg-success' : (status === 'pendente' ? 'bg-warning text-dark' : 'bg-danger'));

    var modal = new bootstrap.Modal(document.getElementById('modalStatus'));
    modal.show();
}
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
