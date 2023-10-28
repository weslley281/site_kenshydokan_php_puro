
<?php
include_once "menu.php";
include_once "../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

$id_curso = $_GET["id"];

$busca = "SELECT * FROM cursos WHERE id_curso = '$id_curso'";
$resultado = mysqli_query($conexao, $busca);
$curso = mysqli_fetch_array($resultado);

$id_categoria = $curso["id_categoria"];
$busca = "SELECT * FROM categorias WHERE id_categoria = '$id_categoria'";
$resultado = mysqli_query($conexao, $busca);
$categoria = mysqli_fetch_array($resultado);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Criar Curso</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../controllers/cursoController.php" method="post">
            <input type="hidden" name="tipo" value="editar">
            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" value="<?php echo $curso["nome"]; ?>" name="nome" required autofocus>
            </div>

            <div class="form-group">
              <label for="id_categoria">Categoria: </label>
                <select id="id_categoria" class="form-select form-control js-example-basic-single" aria-label="Default select example" name="id_categoria">
                    <option value="<?php echo $curso["id_categoria"]; ?>"><?php echo $categoria["categoria"]; ?></option>
            <?php
$consulta = "SELECT id_categoria, categoria FROM categorias";
    $resultado = mysqli_query($conexao, $consulta);
    if ($resultado) {
        while ($dado = mysqli_fetch_array($resultado)) {
            echo '<option value="' . $dado["id_categoria"] . '">' . $dado["categoria"] . '</option>';
        }
    } else {
        echo '<option>Erro ao carregar os dados</option>';
    }
    ?>
                </select>
            </div>

            <div class="form-group">
              <label for="descricao">Descrição: </label>
              <textarea name="descricao" class="form-control" id="descricao" rows="15" require><?php echo $curso["descricao"]; ?></textarea>
            </div>

            <div class="form-group">
              <label for="professor">Professor: </label>
              <input id="professor" type="text" class="form-control" value="<?php echo $curso["professor"]; ?>" name="professor" required>
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