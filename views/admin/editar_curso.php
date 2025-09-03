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

$id_imagem = $curso["id_imagem"];
$busca = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
$resultado = mysqli_query($conexao, $busca);
$imagem = mysqli_fetch_array($resultado);

if (isset($_SESSION["id_usuario"]) && isset($_GET["id"]) && $_SESSION['nivel'] == "admin") {
    ?>
    <div class="container">
      <div class="row">
        <!-- esquerda -->
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
          <div class="card card-signin my-5">
            <div class="card-body">
              <h5 class="card-title text-center">Editar Curso</h5>
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

          <div class="form-group">
              <label for="cargaHoraria">Carga Horária: </label>
              <input id="cargaHoraria" type="text" class="form-control" value="<?php echo $curso["cargaHoraria"]; ?>" name="cargaHoraria" required>
            </div>

            <div class="form-group">
              <label for="percentual_conclusao_certificado">Percentual de Conclusão para Certificado: </label>
              <input id="percentual_conclusao_certificado" type="number" class="form-control" value="<?php echo $curso["percentual_conclusao_certificado"]; ?>" name="percentual_conclusao_certificado" min="0" max="100" required>
            </div>

            <div class="form-group">
              <label for="temCertificado">Tem Certificado: </label>
                <select id="temCertificado" class="form-select form-control" name="temCertificado">
                    <option value="nao" <?php echo ($curso["temCertificado"] == 'nao') ? 'selected' : ''; ?>>Não</option>
                    <option value="sim" <?php echo ($curso["temCertificado"] == 'sim') ? 'selected' : ''; ?>>Sim</option>
                </select>
            </div>

            <div class="form-group">
              <label for="situacao">Situação: </label>
                <select id="situacao" class="form-select form-control" name="situacao">
                    <option value="aguardando" <?php echo ($curso["situacao"] == 'aguardando') ? 'selected' : ''; ?>>Aguardando</option>
                    <option value="aprovado" <?php echo ($curso["situacao"] == 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                    <option value="removido" <?php echo ($curso["situacao"] == 'removido') ? 'selected' : ''; ?>>Removido</option>
                </select>
            </div>

          <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
      </form>

      <h5 class="mt-5">Editar Imagem</h5>

      <form class="form-signin" enctype="multipart/form-data" action="../controllers/cursoController.php" method="post">
        <input type="hidden" name="tipo" value="editar_imagem">
        <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
        <div class="form-group">
            <label for="imagem">Imagem: </label>
            <input type="file" class="form-control" id="imagem" accept="image/*" required name="imagem">

            <div class="text-center mt-2">
                <img src="<?php echo $imagem["caminho"]; ?>" class="img-thumbnail" alt="<?php echo $imagem["nome"]; ?>" id="imagePreview" style="max-width: 100%;">
            </div>
        </div>

        <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
    </form>
</div>
</div>
</div>

<!-- Direita -->
<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
  <div class="card card-signin my-5">
    <div class="card-body">
      <h5 class="card-title text-center">Aulas</h5>
      <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Aula</th>
                <th scope="col">Link</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
$busca = "SELECT * FROM aulas WHERE id_curso = '$id_curso'";
    $resultado = mysqli_query($conexao, $busca);
    $linha = mysqli_num_rows($resultado);
    if ($linha == '') {
        echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
    } else {
        while ($aula = mysqli_fetch_array($resultado)) {
            ?>
                    <tr>
                        <th scope="row"><?php echo $aula["id_aula"]; ?></th>
                        <td><?php echo $aula["titulo"]; ?></td>
                        <td><a href="<?php echo $aula["link"]; ?>">ver</a></td>
                        <td>
                            <div class="form-group">

                                <a class="btn btn-primary" href="<?php echo "../views/editar_aula.php?id=" . $aula["id_aula"] . "&id_curso=" . $id_curso ?>" title="Editar Aula"><i class="fa-solid fa-pen-to-square"></i></a>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirAula<?php echo $aula["id_aula"]; ?>" title="Excluir"><i class="fa-regular fa-calendar-xmark"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modalExcluirAula<?php echo $aula["id_aula"]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja escluir:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                        <p><?php echo $aula["titulo"]; ?></p>
                    </div>
                    <div class="modal-footer">
                        <form action="../controllers/aulaController" method="post">
                          <input type="hidden" name="tipo" value="excluir">
                          <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>">
                          <input type="hidden" name="id_aula" value="<?php echo $aula["id_aula"]; ?>">

                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                          <button type="submit" class="btn btn-danger">Sim</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  <?php }}?>
</tbody>
</table>
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