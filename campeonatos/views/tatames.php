<?php
$pageTitle = 'Tatames | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/tatameModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$tatameModel = new Tatame();
$tatames = $tatameModel->buscarPorCampeonato($idCampeonato);
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Tatames</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-th-large text-danger me-2"></i>Áreas de Tatame e Quadras
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Controle de mesas de arbitragem e telões públicos</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNovoTatame">
            <i class="fas fa-plus me-1"></i> Adicionar Tatame
        </button>
        <a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($tatames)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="fas fa-th-large fa-3x text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">Nenhum tatame cadastrado ainda</h5>
                    <p class="text-muted mb-3">Cadastre os tatames oficiais para abrir as mesas de operação e os telões.</p>
                    <button class="btn btn-danger px-4" data-bs-toggle="modal" data-bs-target="#modalNovoTatame">
                        Cadastrar Tatame 1
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($tatames as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 <?= $t['status'] === 'em_luta' ? 'border-top border-4 border-danger' : 'border-top border-4 border-success' ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-square text-danger me-2"></i><?= htmlspecialchars($t['nome']) ?>
                            </h4>
                            <?php if ($t['status'] === 'em_luta'): ?>
                                <span class="badge bg-danger animate-pulse">Em Luta</span>
                            <?php else: ?>
                                <span class="badge bg-success">Disponível</span>
                            <?php endif; ?>
                        </div>

                        <div class="p-3 bg-light rounded mb-3 small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Área:</span>
                                <strong>Tatame #<?= $t['numero'] ?></strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Mesário / Operador:</span>
                                <strong><?= htmlspecialchars($t['operador_nome'] ?? 'Livre') ?></strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="painel_tatame.php?id=<?= $t['id_tatame'] ?>" class="btn btn-outline-dark">
                                <i class="fas fa-desktop me-1"></i> Painel da Mesa (Mesário)
                            </a>
                            <a href="telao.php?id=<?= $t['id_tatame'] ?>" target="_blank" class="btn btn-danger">
                                <i class="fas fa-tv me-1"></i> Abrir Telão do Público
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
            <form method="POST" action="../controllers/tatameController.php">
                <input type="hidden" name="tipo" value="criar_tatame">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-plus-circle text-danger me-2"></i>Cadastrar Área de Tatame
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numTatame" class="form-label fw-bold">Número da Quadra: <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="numTatame" name="numero" value="<?= count($tatames) + 1 ?>" min="1" max="20" required>
                    </div>
                    <div class="mb-3">
                        <label for="nomeTatame" class="form-label fw-bold">Nome / Identificação: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomeTatame" name="nome" value="Tatame <?= count($tatames) + 1 ?>" required>
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

<?php include_once __DIR__ . "/rodape.php"; ?>
