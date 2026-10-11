<?php
$pageTitle = 'Central do Campeonato | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/tatameModel.php";

$id = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($id);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$categoriaModel = new Categoria();
$categorias = $categoriaModel->buscarPorCampeonato($id);
$totalCategorias = count($categorias);

$inscricaoModel = new Inscricao();
$inscricoes = $inscricaoModel->buscarPorCampeonato($id);
$totalInscritos = count($inscricoes);
$totalAprovados = count(array_filter($inscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada'));

$tatameModel = new Tatame();
$tatames = $tatameModel->buscarPorCampeonato($id);
$totalTatames = count($tatames);
?>

<!-- Header do Campeonato -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="inicio.php">Início</a></li>
                <li class="breadcrumb-item"><a href="campeonatos.php">Campeonatos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($campeonato['titulo']) ?></li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-1 text-dark">
            <i class="fas fa-trophy text-danger me-2"></i><?= htmlspecialchars($campeonato['titulo']) ?>
        </h2>
        <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">
            <span><i class="fas fa-map-marker-alt text-danger me-1"></i><?= htmlspecialchars($campeonato['cidade']) ?>-<?= htmlspecialchars($campeonato['estado']) ?></span>
            &bull;
            <span><i class="far fa-calendar-alt text-danger me-1"></i><?= date('d/m/Y', strtotime($campeonato['data_inicio'])) ?></span>
            &bull;
            <span class="badge bg-danger text-uppercase"><?= str_replace('_', ' ', $campeonato['status']) ?></span>
        </div>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end flex-wrap">
        <a href="editar_campeonato.php?id=<?= $id ?>" class="btn btn-secondary">
            <i class="fas fa-edit me-1"></i> Editar Dados
        </a>
        <a href="../controllers/relatorioController.php?id_campeonato=<?= $id ?>&tipo=inscritos" target="_blank" class="btn btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> PDF Inscritos
        </a>
        <a href="../controllers/relatorioController.php?id_campeonato=<?= $id ?>&tipo=medalhas" target="_blank" class="btn btn-outline-danger">
            <i class="fas fa-medal me-1"></i> PDF Medalhas
        </a>
    </div>
</div>

<!-- Barra de Resumo Rápido (KPIs) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kpi-blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Categorias</div>
                    <div class="kpi-value text-primary"><?= $totalCategorias ?></div>
                    <div class="small text-muted mt-1">Kumite, Kata e Quebra</div>
                </div>
                <div class="kpi-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kpi-green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Atletas Inscritos</div>
                    <div class="kpi-value text-success"><?= $totalInscritos ?></div>
                    <div class="small text-success fw-bold mt-1"><?= $totalAprovados ?> aptos na pesagem</div>
                </div>
                <div class="kpi-icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-id-card"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card kpi-dark">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Tatames Ativos</div>
                    <div class="kpi-value text-dark"><?= $totalTatames ?></div>
                    <div class="small text-muted mt-1">Áreas com telão e mesa</div>
                </div>
                <div class="kpi-icon-box bg-dark bg-opacity-10 text-dark">
                    <i class="fas fa-desktop"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Status Oficial</div>
                    <div class="kpi-value text-danger fs-4 text-uppercase"><?= str_replace('_', ' ', $campeonato['status']) ?></div>
                    <div class="small text-muted mt-1">Edição: <?= htmlspecialchars($campeonato['edicao'] ?? 'Oficial') ?></div>
                </div>
                <div class="kpi-icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-flag-checkered"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Módulos de Operação do Evento -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold text-dark mb-0">
        <i class="fas fa-th-large text-danger me-2"></i>Módulos de Gestão do Campeonato
    </h5>
    <span class="badge bg-light text-dark border">8 Módulos Oficiais Integrados</span>
</div>

<div class="row g-4 mb-4">
    <!-- 1. Categorias -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-primary">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-layer-group fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Categorias</h5>
                <p class="text-muted small flex-grow-1">Pesos, idades e modalidades de Kumite, Kata e Tameshiwari.</p>
                <a href="categorias.php?id=<?= $id ?>" class="btn btn-primary btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-cog me-1"></i> Gerenciar Categorias
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Inscrições -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-success">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-success bg-opacity-10 text-success" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-id-card fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Inscrições</h5>
                <p class="text-muted small flex-grow-1">Inscrição de filiados com snapshot imutável e validação técnica.</p>
                <a href="inscricoes.php?id=<?= $id ?>" class="btn btn-success btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-users me-1"></i> Ver Inscrições
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Pesagem -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-warning">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-warning bg-opacity-10 text-warning" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-weight fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Pesagem Oficial</h5>
                <p class="text-muted small flex-grow-1">Controle de balança, tolerância de peso e remanejamentos.</p>
                <a href="pesagem.php?id=<?= $id ?>" class="btn btn-warning text-dark btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-balance-scale me-1"></i> Abrir Balança
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Chaves e Sorteios -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-danger">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-danger bg-opacity-10 text-danger" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-sitemap fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Chaveamento</h5>
                <p class="text-muted small flex-grow-1">Sorteio eliminatório com potências de 2 e separação de dojo.</p>
                <a href="chaves.php?id=<?= $id ?>" class="btn btn-danger btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-project-diagram me-1"></i> Chaves e Sorteios
                </a>
            </div>
        </div>
    </div>

    <!-- 5. Tatames -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-dark">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-dark bg-opacity-10 text-dark" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-desktop fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Áreas de Tatame</h5>
                <p class="text-muted small flex-grow-1">Mesas de controle, filas de luta e telões públicos em Full Screen.</p>
                <a href="tatames.php?id=<?= $id ?>" class="btn btn-secondary btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-tv me-1"></i> Painel de Tatames
                </a>
            </div>
        </div>
    </div>

    <!-- 6. Resultados -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-warning">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-warning bg-opacity-10 text-warning" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-medal fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Resultados Oficiais</h5>
                <p class="text-muted small flex-grow-1">Quadro geral de medalhas por dojo e homologação de pódios.</p>
                <a href="resultados.php?id=<?= $id ?>" class="btn btn-warning text-dark btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-trophy me-1"></i> Pódios e Medalhas
                </a>
            </div>
        </div>
    </div>

    <!-- 7. Certificados -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-success">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-success bg-opacity-10 text-success" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-certificate fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Certificados</h5>
                <p class="text-muted small flex-grow-1">Emissão individual e em lote com autenticidade por Hash.</p>
                <a href="certificados.php?id=<?= $id ?>" class="btn btn-success btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-print me-1"></i> Gerar Certificados
                </a>
            </div>
        </div>
    </div>

    <!-- 8. Auditoria -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm border-top border-4 border-secondary">
            <div class="card-body p-4 text-center d-flex flex-column">
                <div class="mx-auto mb-3 p-3 rounded-circle bg-secondary bg-opacity-10 text-secondary" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-history fa-2x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Auditoria</h5>
                <p class="text-muted small flex-grow-1">Trilha completa de ações, pesagens, notas e operadores.</p>
                <a href="auditoria.php?id=<?= $id ?>" class="btn btn-secondary btn-sm w-100 fw-bold mt-2">
                    <i class="fas fa-shield-alt me-1"></i> Trilha de Auditoria
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
