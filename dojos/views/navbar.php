<nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php"><?php echo $page_title; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Alterna navegação">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php"><i class="fa-solid fa-house"></i> Home</a>
            </li>
            <?php if (isset($_SESSION['type']) && $_SESSION['type'] !== 'student') { ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=users"><i class="fa-solid fa-user"></i> Usuários</a>
            </li>
            <?php } ?>
            <?php if (isset($_SESSION['type']) && $_SESSION['type'] !== 'student') { ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=financial"><i class="fa-solid fa-hand-holding-dollar"></i> Financeiro</a>
            </li>
            <?php } ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-pen-ruler"></i> Aulas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <?php if (isset($_SESSION['type']) && $_SESSION['type'] !== 'student') { ?><a class="dropdown-item" href="index.php?page=martial_arts">Artes Marciais</a><?php } ?>
                    <?php if (isset($_SESSION['type']) && $_SESSION['type'] !== 'student') { ?><a class="dropdown-item" href="index.php?page=modalities">Turmas</a><?php } ?>
                    <a class="dropdown-item" href="index.php?page=private_class">Aulas Particulares</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Algo mais aqui</a>
                </div>
            </li>
            <?php if (isset($_SESSION["user_id"])) { ?>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="./utils/go_out.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Sair</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>