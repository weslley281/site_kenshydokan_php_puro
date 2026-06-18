<?php
include "menu.php";
include_once __DIR__ . "/../../models/usuarioModel.php";
include_once __DIR__ . "/../../models/imagemModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$id_usuario = $_SESSION['id_usuario'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=visualizacoes">Visualizações</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=usuarios">Usuários</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=postagens">Postagens</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=cursos">Cursos</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=filiados">Filiados</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=exames">Exames de Graduação</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=galerias">Galerias</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=graduacoes">Graduações</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php?pagina=dojos">Dojos</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "visualizacoes") {
      include_once "visualizacoes.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "usuarios") {
      include_once "usuarios.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "postagens") {
      include_once "postagens.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "cursos") {
      include_once "cursos.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "filiados") {
      include_once "filiados.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "exames") {
      include_once "gerenciar_exames.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "galerias") {
      include_once "galerias.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "graduacoes") {
      include_once "graduacoes.php";
    } ?>
    <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "dojos") {
      include_once "dojos.php";
    } ?>
    <?php if (!isset($_GET["pagina"])) { ?>
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
      <br />
    <?php } ?>
  </div>

<?php
  include __DIR__ . "/../rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>