<?php
include_once "menu.php";
include_once "../db/conexao.php";
include_once "../repositorios/publicacaoRepositorio.php";

$c = new Conexao();
$conexao = $c->conectar();
?>
<!-- /Navigation -->

<section class="container mt-5">
  <div class="row">
    <?php
    // Query para buscar os campos necessários. A coluna 'imagem' foi removida.
    $busca_postagen = "SELECT id_publicacao, id_usuario, titulo, conteudo, dataCriacao FROM postagens WHERE status = 'aprovado' order by id_publicacao desc";
    $resultado_postagen = mysqli_query($conexao, $busca_postagen);

    if (mysqli_num_rows($resultado_postagen) > 0) {
        while ($postagem = mysqli_fetch_array($resultado_postagen)) {
            $autor = PublicacaoRepositorio::buscar_nome_autor($postagem["id_usuario"]);
            // Gera um resumo com os primeiros 150 caracteres, decodificando entidades HTML.
            $resumo = substr(html_entity_decode(strip_tags($postagem['conteudo'])), 0, 150);
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title"><?php echo htmlspecialchars($postagem['titulo']); ?></h5>
                  <p class="card-text"><?php echo htmlspecialchars($resumo); ?>...</p>
                  <a href="postagem.php?id=<?php echo $postagem['id_publicacao']; ?>" class="btn btn-primary mt-auto">Leia mais</a>
                </div>
                <div class="card-footer text-muted" style="font-size: 0.8rem;">
                  Publicado por: <a href="ver_perfil.php?id_usuario=<?php echo $postagem["id_usuario"]; ?>"><?php echo "<b>$autor</b>" ?></a> em <b><?php echo date('d/m/Y', strtotime($postagem['dataCriacao'])); ?></b>
                </div>
              </div>
            </div>
          <?php
        }
    } else {
        echo "<p class='text-center col-12'>Nenhuma postagem encontrada.</p>";
    }
    ?>
  </div>
</section>

<?php include "rodape.php"; ?>