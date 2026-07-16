<?php
// _perfil_menu.php
$active_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-lg-3 mb-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white d-lg-none d-flex justify-content-between align-items-center py-3 border-0 rounded-lg">
            <h6 class="mb-0 font-weight-bold text-uppercase tracking-wider">Painel de Controle</h6>
            <button class="btn btn-outline-light btn-sm font-weight-bold px-3 rounded-pill" type="button" data-toggle="collapse" data-target="#perfilMenuCollapse" aria-controls="perfilMenuCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars-staggered mr-2"></i> Menu
            </button>
        </div>
        <div class="collapse d-lg-block" id="perfilMenuCollapse">
            <div class="card-body p-0">
                <div class="list-group list-group-flush rounded-lg overflow-hidden">
                    <div class="d-none d-lg-block p-4 bg-dark text-white border-0 text-center">
                        <img src="../../img/wkka.jpg" width="50" alt="Logo" class="mb-2 rounded-circle shadow-sm">
                        <h6 class="mb-0 font-weight-bold text-uppercase tracking-wider">Painel do Filiado</h6>
                    </div>
                    <a href="perfil.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'perfil.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-user mr-2 <?php echo ($active_page == 'perfil.php') ? '' : 'text-danger'; ?>"></i> Perfil
                    </a>
                    <a href="editar_perfil.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'editar_perfil.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-user-pen mr-2 <?php echo ($active_page == 'editar_perfil.php') ? '' : 'text-danger'; ?>"></i> Editar Perfil
                    </a>
                    <?php if ($usuario["nivel"] == "admin" || $usuario["nivel"] == "sensei") { ?>
                        <a href="exame_graduacao.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'exame_graduacao.php') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-medal mr-2 <?php echo ($active_page == 'exame_graduacao.php') ? '' : 'text-danger'; ?>"></i> Exame de Graduação
                        </a>
                        <a href="criar_postagem.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'criar_postagem.php') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-square-plus mr-2 <?php echo ($active_page == 'criar_postagem.php') ? '' : 'text-danger'; ?>"></i> Criar Postagem
                        </a>
                        <a href="suas_postagens.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'suas_postagens.php') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-newspaper mr-2 <?php echo ($active_page == 'suas_postagens.php') ? '' : 'text-danger'; ?>"></i> Suas Postagens
                        </a>
                        <a href="documentos.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'documentos.php') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-file-arrow-down mr-2 <?php echo ($active_page == 'documentos.php') ? '' : 'text-danger'; ?>"></i> Arquivos para Baixar
                        </a>
                    <?php } ?>
                    <?php if ($usuario["nivel"] == "admin") { ?>
                        <a href="../admin" class="list-group-item list-group-item-action <?php echo ($active_page == 'admin.php') ? 'active' : ''; ?>">
                            <i class="fa-solid fa-screwdriver-wrench mr-2 <?php echo ($active_page == 'admin.php') ? '' : 'text-danger'; ?>"></i> Administrativo
                        </a>
                        <a href="../gerenciamento_dojo" class="list-group-item list-group-item-action <?php echo (strpos($_SERVER['REQUEST_URI'], 'gerenciamento_dojo') !== false) ? 'active' : ''; ?>">
                            <i class="fa-solid fa-store mr-2 <?php echo (strpos($_SERVER['REQUEST_URI'], 'gerenciamento_dojo') !== false) ? '' : 'text-danger'; ?>"></i> Gerenciar Dojô
                        </a>
                    <?php } ?>
                    <a href="eventos.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'eventos.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-circle-play mr-2 <?php echo ($active_page == 'eventos.php') ? '' : 'text-danger'; ?>"></i> Eventos Online
                    </a>
                    <a href="meus_certificados.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'meus_certificados.php') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-certificate mr-2 <?php echo ($active_page == 'meus_certificados.php') ? '' : 'text-danger'; ?>"></i> Meus Certificados
                    </a>
                    <a href="../../controllers/sair.php" class="list-group-item list-group-item-action text-danger font-weight-bold">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>