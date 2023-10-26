<?php
include_once "menu.php";
include_once "../db/conexao.php";

$c = new Conexao;
$conexao = $c->conectar();

$id_usuario = $_GET['id'];

$busca_usuario = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

$id_imagem = $usuario["id_imagem"];
$busca_imagem = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
$resultado_imagem = mysqli_query($conexao, $busca_imagem);
$imagem = mysqli_fetch_array($resultado_imagem);

if ($usuario["id_fil"] != 0 || $usuario["id_fil"] != null && $usuario['nivel'] == "kohai") {
    $id_filiado = $usuario["id_fil"];
    $busca_filiado = "SELECT * FROM filiados WHERE id_filiado = '$id_filiado'";
    $resultado_filiado = mysqli_query($conexao, $busca_filiado);
    $filiado = mysqli_fetch_array($resultado_filiado);
    $estaFiliado = ($filiado["confirmacao"] == "sim") ? "Você está filiado" : "Aguardando Confirmação de Filiação, Não Está Filiado Não";

    $id_graduacao = $filiado["id_graduacao"];
    $busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
    $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
    $graduacao = mysqli_fetch_array($resultado_graduacao);
} else {
    $estaFiliado = "Sem dados";
    $filiado = array(
        "nome" => "A definir",
        "dojo" => "A definir",
        "id_filiado" => 0,
    );
    $graduacao = array(
        'graduacao' => 'sem registro',
    );
}

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<!-- /Navigation -->

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Editar</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/usuarioController.php" method="post">
            <input type="hidden" name="tipo" value="edidar_admin">
            <input type="hidden" name="id_imagem" value="<?php echo $id_imagem; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" value="<?php echo $usuario["nome"] ?>" name="nome" required autofocus>
            </div>

            <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>