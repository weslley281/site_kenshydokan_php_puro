<?php
session_start();
if (!isset($_SESSION["user_id"]) && !isset($_GET['page'])) {
    echo "<script language='javascript'>window.location='./index.php?page=login'; </script>";
}
require_once "./config/db.php";
require_once "./config/CreateTables.php";

require_once "./utils/renderAlert.php";
require_once "./utils/truncate.php";
require_once './utils/openssl.php';

require_once './models/User.php';
require_once './models/MartialArt.php';
require_once './models/Modality.php';
require_once './models/Cashier.php';
require_once './models/SalesRecords.php';
require_once './models/SalesItem.php';
require_once './models/MethodPayment.php';
require_once './models/SalesPaymentItem.php';
require_once './models/Expiration.php';


$createTable = new CreateTables;
$user = new User($conn);
$martial_art = new MartialArt($conn);
$modality = new Modality($conn);
$cashier = new Cashier($conn);
$salesRecord = new SalesRecord($conn);
$salesItem = new SalesItem($conn);
$methodPayment = new MethodPayment($conn);
$salesPaymentItem = new SalesPaymentItem($conn);
$expiration = new Expiration($conn);

$createTable->createUsersTable($conn);
$createTable->createMartialArtsTable($conn);
$createTable->createModalityTable($conn);
$createTable->createModalityDaysTable($conn);
$createTable->createCashierTable($conn);
$createTable->createMethodPaymentTable($conn);
$createTable->createSalesTable($conn);
$createTable->createSalesItemTable($conn);
$createTable->createSalesPaymentItemTable($conn);
$createTable->createExpirationTable($conn);

$createTable->insertPaymentMethods($conn);

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? '';

$titles = [
    'dashboard' => 'Painel de Controle',
    'login' => 'Login',
    'users' => 'Usuários',
    'martial_arts' => 'Artes Marciais',
    'modalities' => 'Turmas',
    'financial' => 'Financeiro',
];

$page_title = isset($titles[$page]) ? $titles[$page] : 'Página não encontrada';

if (!$user->getByEmail("instituto@kenshydokan.org.br")) {
    $password = password_hash("admin@123", PASSWORD_DEFAULT);

    $data = [
        "name" => 'Kenshydokan',
        "phone" => '65981233996',
        "email" => "instituto@kenshydokan.org.br",
        "address" => "R. 24 de Outubro, 185",
        "complement" => "Edifício",
        "country" => "Brasil",
        "state" => "Mato Grosso",
        "city" => "Várzea Grande",
        "neighborhood" => "Centro Norte",
        "postal_code" => "78110-520",
        "marital_status" => "single",
        "gender" => "masculine",
        "birth_date" => "2000-01-01",
        "password" => $password,
        "type" => 'admin',
        "cpf" => '10.707.722/0001-34',
    ];

    $user->create($data);
}

require_once "./header.php";
?>

<body>
    <?php include_once './views/navbar.php'; ?>
    <div class="container">
        <?php

        // Usando switch para simplificar condicionais
        switch ($page) {
            case 'dashboard':
                include_once "./views/dashboard.php";
                break;

            case 'validate':
                if (isset($_SESSION['type']) && $_SESSION['type'] === 'student') {
                    echo "<script>window.location.href = './index.php?page=dashboard&error=permission';</script>";
                    exit;
                }
                include_once './views/validate/index.php';
                break;

            case 'login':
                switch ($action) {
                    case 'success':
                        echo renderAlert('success', 'Sucesso!', 'Loguin Registrado com Sucesso. Você já pode navegar.');
                        echo "<script>";
                        echo "setTimeout(function() { window.location.href = './index.php?page=dashboard'; }, 3000);";
                        echo "</script>";

                        break;

                    case 'fail':
                        echo renderAlert('danger', 'Erro!', 'Erro ao fazer login: usuário ou senha incorreto.');
                        include_once './views/login.php';
                        break;
                    default:
                        include_once './views/login.php';
                        break;
                }
                break;
            case 'users':
                if (isset($_SESSION['type']) && $_SESSION['type'] === 'student') {
                    echo "<script>window.location.href = './index.php?page=dashboard&error=permission';</script>";
                    exit;
                }

                if ($action === 'create') {
                    include_once './views/user/create.php';
                } else {
                    switch ($action) {
                        case 'success':
                            echo renderAlert('success', 'Sucesso!', 'Usuário criado com sucesso.');
                            break;

                        case 'fail':
                            echo renderAlert('danger', 'Erro!', 'Erro ao criar o usuário.');
                            break;

                        case 'saved':
                            echo renderAlert('info', 'Sucesso!', 'Usuário editado com sucesso.');
                            break;

                        case 'deleted':
                            echo renderAlert('warning', 'Sucesso!', 'Usuário deletado.');
                            break;
                    }
                }

                include_once './views/user/index.php';

                break;

            case 'martial_arts':
                if (isset($_SESSION['type']) && $_SESSION['type'] === 'student') {
                    echo "<script>window.location.href = './index.php?page=dashboard&error=permission';</script>";
                    exit;
                }

                if ($action === 'create') {
                    include_once './views/martial_art/create.php';
                } else {
                    switch ($action) {
                        case 'success':
                            echo renderAlert('success', 'Sucesso!', 'Arte Marcial criada com sucesso.');
                            break;

                        case 'fail':
                            echo renderAlert('danger', 'Erro!', 'Erro ao criar o Arte Marcial.');
                            break;

                        case 'saved':
                            echo renderAlert('info', 'Sucesso!', 'Arte Marcial editada com sucesso.');
                            break;

                        case 'deleted':
                            echo renderAlert('warning', 'Sucesso!', 'Arte Marcial deletada.');
                            break;
                    }
                }

                include_once './views/martial_art/index.php';

                break;

            case 'modalities':
                if (isset($_SESSION['type']) && $_SESSION['type'] === 'student') {
                    echo "<script>window.location.href = './index.php?page=dashboard&error=permission';</script>";
                    exit;
                }

                if ($action === 'create') {
                    include_once './views/modality/create.php';
                } else {
                    switch ($action) {
                        case 'success':
                            echo renderAlert('success', 'Sucesso!', 'Turma criada com sucesso.');
                            break;

                        case 'fail':
                            echo renderAlert('danger', 'Erro!', 'Erro ao criar o turma.');
                            break;

                        case 'saved':
                            echo renderAlert('info', 'Sucesso!', 'Turma editada com sucesso.');
                            break;

                        case 'deleted':
                            echo renderAlert('warning', 'Sucesso!', 'Turma deletada.');
                            break;
                    }
                }

                include_once './views/modality/index.php';

                break;

            case "financial":
                if (isset($_SESSION['type']) && $_SESSION['type'] === 'student') {
                    echo "<script>window.location.href = './index.php?page=dashboard&error=permission';</script>";
                    exit;
                }
                include_once './views/financial/index.php';
                break;

            default:
                echo "<h2>Página não encontrada</h2>";
                break;
        }
        ?>
    </div>
    <?php require_once "./footer.php" ?>

</body>

</html>