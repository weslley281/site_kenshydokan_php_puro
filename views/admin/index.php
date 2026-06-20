<?php
include "menu.php";
include_once __DIR__ . "/../../models/usuarioModel.php";
include_once __DIR__ . "/../../models/imagemModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$id_usuario = $_SESSION['id_usuario'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
  $pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 'visualizacoes';
?>
  <div class="container py-4">
    <!-- Premium Navigation Tab Bar -->
    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-white rounded-pill shadow-sm border" style="gap: 5px;">
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'visualizacoes') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=visualizacoes">
          <i class="fa-solid fa-chart-line mr-1"></i> Painel
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'usuarios') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=usuarios">
          <i class="fa-solid fa-users mr-1"></i> Usuários
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'postagens') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=postagens">
          <i class="fa-solid fa-newspaper mr-1"></i> Postagens
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'cursos') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=cursos">
          <i class="fa-solid fa-graduation-cap mr-1"></i> Cursos
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'filiados') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=filiados">
          <i class="fa-solid fa-id-badge mr-1"></i> Filiados
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'exames') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=exames">
          <i class="fa-solid fa-file-signature mr-1"></i> Exames
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'galerias') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=galerias">
          <i class="fa-solid fa-images mr-1"></i> Galerias
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'graduacoes') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=graduacoes">
          <i class="fa-solid fa-medal mr-1"></i> Graduações
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link rounded-pill font-weight-bold <?php echo ($pagina == 'dojos') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=dojos">
          <i class="fa-solid fa-store mr-1"></i> Dojos
        </a>
      </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="myTabContent">
      <?php if ($pagina == "visualizacoes") {
        include_once "visualizacoes.php";
      } ?>
      <?php if ($pagina == "usuarios") {
        include_once "usuarios.php";
      } ?>
      <?php if ($pagina == "postagens") {
        include_once "postagens.php";
      } ?>
      <?php if ($pagina == "cursos") {
        include_once "cursos.php";
      } ?>
      <?php if ($pagina == "filiados") {
        include_once "filiados.php";
      } ?>
      <?php if ($pagina == "exames") {
        include_once "gerenciar_exames.php";
      } ?>
      <?php if ($pagina == "galerias") {
        include_once "galerias.php";
      } ?>
      <?php if ($pagina == "graduacoes") {
        include_once "graduacoes.php";
      } ?>
      <?php if ($pagina == "dojos") {
        include_once "dojos.php";
      } ?>
    </div>
  </div>

<?php
  include __DIR__ . "/../rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>