<?php
include "menu.php";
include_once "../db/conexao.php";
include_once "../repositorios/imagemRepositorio.php";
include_once "../repositorios/graduacaoRepositorio.php";

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
    <a class="nav-link" id="usuario-tab" href="admin.php?pagina=usuarios">Usuários</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" id="usuario-tab" href="admin.php?pagina=postagens">Postagens</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" id="usuario-tab" href="admin.php?pagina=cursos">Cursos</a>
  </li>
  <li class="nav-item">
  <a class="nav-link" id="usuario-tab" href="admin.php?pagina=filiados">Filiados</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "usuarios") {include_once "admin/usuarios.php";}?>
  <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "postagens") {include_once "admin/postagens.php";}?>
  <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "cursos") {include_once "admin/cursos.php";}?>
  <?php if (isset($_GET["pagina"]) && $_GET["pagina"] == "filiados") {include_once "admin/filiados.php";}?>
</div>

<?php
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>