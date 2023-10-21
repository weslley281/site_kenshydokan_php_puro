<?php
include "menu.php";
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();

$id_usuario = $_SESSION['id_usuario'];

$busca_usuario = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

if (isset($_SESSION["id_usuario"]) || $usuario["nivel"] == "admin") {
    ?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="profile" aria-selected="false">Perfil</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contato" role="tab" aria-controls="contact" aria-selected="false">Contato</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
  <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="profile-tab">...</div>
  <div class="tab-pane fade" id="contato" role="tabpanel" aria-labelledby="contact-tab">...</div>
</div>

<?php
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>