<?php
    include_once("menu.php");
    session_start();
    $usuario = $_SESSION['usuario'];
    $nome = $_SESSION['nome'];
    $id_adm = $_SESSION['id_adm'];
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Kenshydokan - Admin</title>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/scripts.js"></script> 
        <script type="text/javascript" src="js/datatables.min.js"></script>
        <script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.bundle.min"></script>
        <script type="text/javascript" src="js/datatables-demo.js"></script>
        <script type="text/javascript" src="js/vue.js"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="inicio.php">Kenshydokan</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Configurações</a>
                        <a class="dropdown-item" href="registrar_adm.php">Registrar Adms</a>
                        <a class="dropdown-item" href="registrar_usuario.php">Registrar usuários</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="funcoes/sair.php">Sair</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="inicio.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Gerenciamento</div>
                            <a class="nav-link" href="inicio.php?filiados">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Filiados
                            </a>
                            <a class="nav-link" href="inicio.php?graduacao">
                                <div class="sb-nav-link-icon"><i class="fas fa-graduation-cap"></i></div>
                                Graduação
                            </a>
                            <a class="nav-link" href="inicio.php?postagens">
                                <div class="sb-nav-link-icon"><i class="far fa-copy"></i></div>
                                Postagens
                            </a>
                            <a class="nav-link" href="inicio.php?categorias">
                                <div class="sb-nav-link-icon"><i class="fas fa-clone"></i></div>
                                Categorias
                            </a>
                            <a class="nav-link" href="inicio.php?cursos">
                                <div class="sb-nav-link-icon"><i class="fas fa-pencil-alt"></i></div>
                                Cursos
                            </a>
                            <a class="nav-link" href="inicio.php?galerias">
                                <div class="sb-nav-link-icon"><i class="far fa-images"></i></div>
                                Galerias
                            </a>
                            <!--
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                            -->
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logado como:</div>
                        <?php echo $nome; ?>
                    </div>
                </nav>
            </div>
