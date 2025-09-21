<?php
include_once "menu.php";
include_once __DIR__ . "/../../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Criar Curso</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../../controllers/cursoController.php" method="post">
            <input type="hidden" name="tipo" value="inserir">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" name="nome" required autofocus>
            </div>

            <div class="form-group">
              <label for="id_categoria">Categoria: </label>
                <select id="id_categoria" class="form-select form-control js-example-basic-single" aria-label="Default select example" name="id_categoria">
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
              <textarea name="descricao" id="descricao"></textarea>
            </div>

            <div class="form-group">
              <label for="professor">Professor: </label>
              <input id="professor" type="text" class="form-control" name="professor" required>
            </div>

            <div class="form-group">
              <label for="cargaHoraria">Carga Horária: </label>
              <input id="cargaHoraria" type="text" class="form-control" name="cargaHoraria" required>
            </div>

            <div class="form-group">
              <label for="percentual_conclusao_certificado">Percentual de Conclusão para Certificado: </label>
              <input id="percentual_conclusao_certificado" type="number" class="form-control" name="percentual_conclusao_certificado" min="0" max="100" value="100" required>
            </div>

            <div class="form-group">
              <label for="temCertificado">Tem Certificado: </label>
                <select id="temCertificado" class="form-select form-control" name="temCertificado">
                    <option value="nao" selected>Não</option>
                    <option value="sim">Sim</option>
                </select>
            </div>

            <div class="form-group">
              <label for="situacao">Situação: </label>
                <select id="situacao" class="form-select form-control" name="situacao">
                    <option value="aguardando" selected>Aguardando</option>
                    <option value="aprovado">Aprovado</option>
                    <option value="removido">Removido</option>
                </select>
            </div>

            <div class="form-group">
              <label for="imagem">Imagem: </label>
                <input type="file" class="form-control" id="imagem" accept="image/*" required name="imagem">

                <div class="text-center mt-2">
                  <img src="#" class="img-thumbnail" alt="Prévia da Imagem" id="imagePreview" style="max-width: 100%; display: none;">
                </div>
            </div>

            <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include "../rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>