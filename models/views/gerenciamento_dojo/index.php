<?php
include_once __DIR__ . "/../admin/menu.php";
include_once __DIR__ . "/../../models/dojoAlunoConfigModel.php";
include_once __DIR__ . "/../../models/dojoMensalidadeModel.php";
include_once __DIR__ . "/../../models/dojoFinanceiroModel.php";

// Verificação de autenticação
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    echo "<script>alert('Acesso negado.'); window.location.href = '../login.php';</script>";
    exit();
}

$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 'dashboard';
$referencia = isset($_GET["referencia"]) ? $_GET["referencia"] : date("Y-m");

$alunoConfigModel = new DojoAlunoConfigModel();
$mensalidadeModel = new DojoMensalidadeModel();
$financeiroModel = new DojoFinanceiroModel();

// Atualiza inadimplentes automaticamente ao carregar
$mensalidadeModel->atualizarInadimplencias();

// Dados para cartões
$resumo_financeiro = $financeiroModel->buscarResumoFinanceiroMes($referencia);
$counts_alunos = $alunoConfigModel->contarStatus();
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="d-flex align-items-center flex-wrap">
                <h2 class="font-weight-bold text-dark mb-0 mr-2">Gestão do Dojô Kenshydokan</h2>
                <a href="ajuda.php" class="btn btn-sm btn-outline-danger border-0 rounded-circle d-inline-flex align-items-center justify-content-center" title="Como usar o sistema? / Ajuda" style="width: 32px; height: 32px; padding: 0;">
                    <i class="fa-solid fa-circle-question" style="font-size: 1.3rem;"></i>
                </a>
            </div>
            <p class="text-muted mb-0"><i class="fa-regular fa-calendar mr-2"></i>Mês de Referência: <?php echo date("m/Y", strtotime($referencia . '-01')); ?></p>
        </div>
        <div>
            <!-- Seletor de Referência de Mês -->
            <form method="get" action="index.php" class="form-inline bg-white p-2 rounded-pill shadow-sm border">
                <input type="hidden" name="pagina" value="<?php echo htmlspecialchars($pagina); ?>">
                <label for="referencia" class="mr-2 ml-1 text-secondary small font-weight-bold text-uppercase">Período:</label>
                <input type="month" id="referencia" name="referencia" value="<?php echo htmlspecialchars($referencia); ?>" class="form-control form-control-sm bg-light border-0 mr-2 rounded-pill px-3" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <!-- Navegação por Abas (Premium Pills) -->
    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-white rounded-pill shadow-sm border" style="gap: 5px;">
        <li class="nav-item">
            <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'dashboard') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=dashboard&referencia=<?php echo $referencia; ?>">
                <i class="fa-solid fa-gauge mr-1"></i> Painel Geral
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'alunos') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=alunos&referencia=<?php echo $referencia; ?>">
                <i class="fa-solid fa-user-gear mr-1"></i> Config. Alunos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'mensalidades') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=mensalidades&referencia=<?php echo $referencia; ?>">
                <i class="fa-solid fa-sack-dollar mr-1"></i> Mensalidades
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'financeiro') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=financeiro&referencia=<?php echo $referencia; ?>">
                <i class="fa-solid fa-cash-register mr-1"></i> Livro Caixa
            </a>
        </li>
    </ul>

    <!-- Conteúdo das Abas -->
    <div class="tab-content" id="dojoTabContent">
        <?php
        if ($pagina === 'dashboard') {
            include_once __DIR__ . '/dashboard.php';
        } elseif ($pagina === 'alunos') {
            include_once __DIR__ . '/alunos.php';
        } elseif ($pagina === 'mensalidades') {
            include_once __DIR__ . '/mensalidades.php';
        } elseif ($pagina === 'financeiro') {
            include_once __DIR__ . '/financeiro.php';
        } else {
            include_once __DIR__ . '/dashboard.php';
        }
        ?>
    </div>
</div>

<!-- Modais de alto nível (fora do tab-content e do container principal) para evitar problemas de backdrop/stacking context do Bootstrap -->
<?php
if ($pagina === 'alunos') {
    include_once __DIR__ . '/alunos_modals.php';
} elseif ($pagina === 'mensalidades') {
    include_once __DIR__ . '/mensalidades_modals.php';
} elseif ($pagina === 'financeiro') {
    include_once __DIR__ . '/financeiro_modals.php';
}
?>

<?php
include_once __DIR__ . "/../admin/rodape.php";
?>
