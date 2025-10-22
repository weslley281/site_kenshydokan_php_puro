<?php
include_once "menu.php";
include_once "../db/conexao.php";
include_once "../repositorios/publicacaoRepositorio.php";

$c = new Conexao();
$conexao = $c->conectar();

// 1. Pega o ID da postagem da URL de forma segura
$id_postagem = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (empty($id_postagem)) {
    echo "<section class='container mt-5'><p class='alert alert-danger'>Postagem não especificada.</p></section>";
    include "rodape.php";
    exit;
}

// 2. Busca a postagem específica no banco de dados usando prepared statements para segurança
$busca_postagen = "SELECT * FROM postagens WHERE id_publicacao = ? AND status = 'aprovado'";
$stmt = mysqli_prepare($conexao, $busca_postagen);
mysqli_stmt_bind_param($stmt, "i", $id_postagem);
mysqli_stmt_execute($stmt);
$resultado_postagen = mysqli_stmt_get_result($stmt);
$postagem = mysqli_fetch_assoc($resultado_postagen);

?>
<!-- /Navigation -->

<section class="container mt-5 mb-5">
  <?php
  if ($postagem) {
      $autor = PublicacaoRepositorio::buscar_nome_autor($postagem["id_usuario"]);
      ?>
      <div class="card shadow-lg">
        <div class="card-body p-4 p-md-5">
          <h1 class="text-center mb-4"><?php echo htmlspecialchars($postagem["titulo"]); ?></h1>
          


          <div class="post-content">
            <?php echo $postagem["conteudo"]; // Exibe o conteúdo completo ?>
          </div>
          
          <hr class="my-4">
          
          <div class="text-right text-muted">
            Publicado por: <a href="ver_perfil.php?id_usuario=<?php echo $postagem["id_usuario"]; ?>"><?php echo "<b>$autor</b>" ?></a> em <b><?php echo date('d/m/Y', strtotime($postagem['dataCriacao'])); ?></b>
          </div>
        </div>
      </div>
    <?php
  } else {
      echo "<p class='alert alert-warning text-center'>Postagem não encontrada ou não está mais disponível.</p>";
  }
  ?>
</section>

<?php include "rodape.php"; ?>