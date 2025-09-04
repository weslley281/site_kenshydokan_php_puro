<?php
include "menu.php";
include_once __DIR__ . "/../../db/conexao.php";
include_once __DIR__ . "/../../repositorios/imagemRepositorio.php";
include_once __DIR__ . "/../../repositorios/graduacaoRepositorio.php";

$c = new Conexao();
$conexao = $c->conectar();

$id_usuario = $_SESSION['id_usuario'];

$busca_usuario = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

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