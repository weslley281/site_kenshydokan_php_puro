<?php
include_once "../db/conexao.php";
include_once "../repositorios/publicacaoRepositorio.php";
include_once "menu.php";

$c = new Conexao();
$conexao = $c->conectar();
?>
<!-- /Navigation -->

<section class="container">
  <?php
$busca_postagen = "SELECT * FROM postagens WHERE status = 'sim' order by id_publicacao desc";
$resultado_postagen = mysqli_query($conexao, $busca_postagen);
while ($postagem = mysqli_fetch_array($resultado_postagen)) {
    $autor = PublicacaoRepositorio::buscar_nome_autor($postagem["id_usuario"]);
    ?>
    <div class="container-fluid border bg-white mt-5 mb-5">
      <div>
        <?php echo $postagem["conteudo"]; ?>
      </div>
      <div class="text-success text-right">
        Publicado por: <a href="ver_perfil.php?id_usuario=<?php echo $postagem["id_usuario"]; ?>"><?php echo "<b><u>$autor</u></b>" ?></a> em <b><u><?php echo date('d/m/Y', strtotime($postagem['dataCriacao'])); ?></u></b>;
      </div>
    </div>
  <?php }?>

</section>

<?php include "rodape.php";?>