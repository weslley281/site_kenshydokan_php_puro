<?php
// _perfil_menu.php
$active_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-lg-3">
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-3">
        <h1 class="my-4 d-lg-none"><?php echo $page_title ?? 'Meu Perfil'; ?></h1>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#perfilMenuCollapse" aria-controls="perfilMenuCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="perfilMenuCollapse">
            <div class="list-group w-100">
                <h1 class="my-4 d-none d-lg-block"><?php echo $page_title ?? 'Meu Perfil'; ?></h1>
                <a href="perfil.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'perfil.php') ? 'active' : ''; ?>">Perfil</a>
                <a href="editar_perfil.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'editar_perfil.php') ? 'active' : ''; ?>">Editar Perfil</a>
                <?php if ($usuario["nivel"] == "admin" || $usuario["nivel"] == "sensei") { ?>
                    <a href="exame_graduacao.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'exame_graduacao.php') ? 'active' : ''; ?>">Exame de Graduação</a>
                    <a href="criar_postagem.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'criar_postagem.php') ? 'active' : ''; ?>">Criar Postagem</a>
                    <a href="suas_postagens.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'suas_postagens.php') ? 'active' : ''; ?>">Suas Postagens</a>
                    <a href="documentos.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'documentos.php') ? 'active' : ''; ?>">Arquivos para Baixar</a>
                <?php } ?>
                <?php if ($usuario["nivel"] == "admin") { ?>
                    <a href="../admin" class="list-group-item list-group-item-action <?php echo ($active_page == 'admin.php') ? 'active' : ''; ?>">Administrativo</a>
                <?php } ?>
                <a href="eventos.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'eventos.php') ? 'active' : ''; ?>">Eventos Online</a>
                <a href="meus_certificados.php" class="list-group-item list-group-item-action <?php echo ($active_page == 'meus_certificados.php') ? 'active' : ''; ?>">Meus Certificados</a>
                <a href="../../controllers/sair.php" class="list-group-item list-group-item-action">Sair</a>
            </div>
        </div>
    </nav>
</div>