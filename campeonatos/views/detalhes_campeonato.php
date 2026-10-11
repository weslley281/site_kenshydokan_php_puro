<?php
$pageTitle = 'Painel do Campeonato | Kenshydokan';
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
$inscricaoModel = new Inscricao();
$tatameModel = new Tatame();

$totalCategorias = count($categoriaModel->buscarPorCampeonato($id));
$inscricoes = $inscricaoModel->buscarPorCampeonato($id);
$totalInscritos = count($inscricoes);
$totalAprovados = count(array_filter($inscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada'));
$totalTatames = count($tatameModel->buscarPorCampeonato($id));
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($campeonato['titulo']) ?></li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-trophy text-danger me-2"></i><?= htmlspecialchars($campeonato['titulo']) ?>
        </h2>
        <p class="text-muted small mb-0">
            <?= htmlspecialchars($campeonato['edicao'] ?? '') ?> &bull; 
            <?= date('d/m/Y', strtotime($campeonato['data_inicio'])) ?> a <?= date('d/m/Y', strtotime($campeonato['data_fim'])) ?> &bull; 
            <?= htmlspecialchars($campeonato['cidade']) ?>/<?= htmlspecialchars($campeonato['estado']) ?>
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="editar_campeonato.php?id=<?= $id ?>" class="btn btn-outline-secondary">
            <i class="fas fa-edit me-1"></i> Editar
        </a>
        <a href="../controllers/relatorioController.php?id_campeonato=<?= $id ?>&tipo=inscritos" target="_blank" class="btn btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> PDF Inscritos
        </a>
    </div>
</div>

<!-- Barra de Resumo Rápido -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Categorias</div>
            <div class="fs-4 fw-bold text-dark"><?= $totalCategorias ?> cadastradas</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-success border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Atletas Inscritos</div>
            <div class="fs-4 fw-bold text-success"><?= $totalInscritos ?> (<?= $totalAprovados ?> aptos)</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-warning border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Tatames de Combate</div>
            <div class="fs-4 fw-bold text-dark"><?= $totalTatames ?> áreas ativas</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-danger border-4 p-3">
            <div class="small text-muted text-uppercase fw-bold">Status Atual</div>
            <div class="fs-6 fw-bold text-danger text-uppercase mt-1"><?= str_replace('_', ' ', $campeonato['status']) ?></div>
        </div>
    </div>
</div>

<!-- Módulos de Operação do Evento -->
<h5 class="fw-bold text-dark mb-3">
    <i class="fas fa-th-large text-danger me-2"></i>Módulos de Gestão do Campeonato
</h5>

<div class="row g-4">
    <!-- 1. Categorias -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-primary"><i class="fas fa-layer-group fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Categorias</h5>
                <p class="text-muted small">Pesos, idades e modalidades de Kumite, Kata e Tameshiwari.</p>
                <a href="categorias.php?id=<?= $id ?>" class="btn btn-outline-primary btn-sm w-100 fw-bold">
                    Gerenciar Categorias
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Inscrições -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-success"><i class="fas fa-id-card fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Inscrições</h5>
                <p class="text-muted small">Inscrição de filiados com snapshot imutável e validação.</p>
                <a href="inscricoes.php?id=<?= $id ?>" class="btn btn-outline-success btn-sm w-100 fw-bold">
                    Ver Inscrições
                </a>
            </div>
        </div>
    </div>

    <!-- 3. Pesagem -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-warning"><i class="fas fa-weight fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Pesagem Oficial</h5>
                <p class="text-muted small">Controle de balança, aferições e remanejamentos.</p>
                <a href="pesagem.php?id=<?= $id ?>" class="btn btn-outline-warning text-dark btn-sm w-100 fw-bold">
                    Abrir Balança
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Chaves e Sorteios -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-danger"><i class="fas fa-sitemap fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Chaveamento</h5>
                <p class="text-muted small">Sorteio eliminatório com Byes, potências de 2 e separação de dojo.</p>
                <a href="chaves.php?id=<?= $id ?>" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                    Chaves e Sorteios
                </a>
            </div>
        </div>
    </div>

    <!-- 5. Tatames -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-dark"><i class="fas fa-desktop fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Áreas de Tatame</h5>
                <p class="text-muted small">Mesas de controle, filas de luta e telões públicos.</p>
                <a href="tatames.php?id=<?= $id ?>" class="btn btn-outline-dark btn-sm w-100 fw-bold">
                    Painel de Tatames
                </a>
            </div>
        </div>
    </div>

    <!-- 6. Resultados -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-warning"><i class="fas fa-medal fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Resultados Oficiais</h5>
                <p class="text-muted small">Quadro geral de medalhas por dojo e homologação de pódios.</p>
                <a href="resultados.php?id=<?= $id ?>" class="btn btn-outline-warning text-dark btn-sm w-100 fw-bold">
                    Pódios e Medalhas
                </a>
            </div>
        </div>
    </div>

    <!-- 7. Certificados -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-info"><i class="fas fa-certificate fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Certificados</h5>
                <p class="text-muted small">Emissão individual e em lote com autenticidade por Hash.</p>
                <a href="certificados.php?id=<?= $id ?>" class="btn btn-outline-info btn-sm w-100 fw-bold">
                    Gerar Certificados
                </a>
            </div>
        </div>
    </div>

    <!-- 8. Auditoria -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-3 text-secondary"><i class="fas fa-history fa-3x"></i></div>
                <h5 class="fw-bold text-dark">Auditoria</h5>
                <p class="text-muted small">Trilha completa de ações, pesagens, notas e operadores.</p>
                <a href="auditoria.php?id=<?= $id ?>" class="btn btn-outline-secondary btn-sm w-100 fw-bold">
                    Trilha de Auditoria
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
