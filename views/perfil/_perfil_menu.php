<?php
// _perfil_menu.php
$active_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-lg-3">
    <h1 class="my-4"><?php echo $page_title ?? 'Meu Perfil'; ?></h1>
    <div class="list-group">
        <a href="perfil.php" class="list-group-item <?php echo ($active_page == 'perfil.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Perfil</a>
        <a href="editar_perfil.php" class="list-group-item <?php echo ($active_page == 'editar_perfil.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Editar Perfil</a>
        <?php if ($usuario["nivel"] == "admin" || $usuario["nivel"] == "sensei") { ?>
            <a href="exame_graduacao.php" class="list-group-item <?php echo ($active_page == 'exame_graduacao.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Exame de Graduação</a>
            <a href="criar_postagem.php" class="list-group-item <?php echo ($active_page == 'criar_postagem.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Criar Postagem</a>
            <a href="suas_postagens.php" class="list-group-item <?php echo ($active_page == 'suas_postagens.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Suas Postagens</a>
            <a href="documentos.php" class="list-group-item <?php echo ($active_page == 'documentos.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Arquivos para Baixar</a>
        <?php } ?>
        <?php if ($usuario["nivel"] == "admin") { ?>
            <a href="../admin" class="list-group-item <?php echo ($active_page == 'admin.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Administrativo</a>
        <?php } ?>
        <a href="eventos.php" class="list-group-item <?php echo ($active_page == 'eventos.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Eventos Online</a>
        <a href="meus_certificados.php" class="list-group-item <?php echo ($active_page == 'meus_certificados.php') ? 'bg-danger text-dark' : 'bg-light text-dark'; ?>">Meus Certificados</a>
        <a href="../../controllers/sair.php" class="list-group-item bg-light text-dark">Sair</a>
    </div>
</div>