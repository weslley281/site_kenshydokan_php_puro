<?php 
include_once("classes/verificacao.php");
include_once("classes/connection.php");
$c = new Connection();
$conexao = $c->connect(); 
 ?>
<!-- Navigation -->
  <?php include("menu.php"); ?>
<!-- /Navigation -->

<section class="container">
  	<?php
  			$busca_postagen = "SELECT * FROM postagens WHERE situacao = 'sim' order by id_postagem desc";
  			$resultado_postagen = mysqli_query($conexao, $busca_postagen);
  			while($postagem = mysqli_fetch_array($resultado_postagen)){
          $autor = verificacao::verifica_nome_autor($postagem["id_usuario"]);
  		 ?>
  		 <div class="container-fluid border bg-white mt-5 mb-5">
            <div>
      		 	<?php echo $postagem["conteudo"]; ?>
            </div>
            <div class="text-success text-right">
            Publicado por: <a href="filiado/ver_perfil.php?id_usuario=<?php echo $postagem["id_usuario"]; ?>"><?php echo "<b><u>$autor</u></b>"?></a> em <b><u><?php echo date('d/m/Y',  strtotime($postagem['data'])); ?></u></b>; 
            </div>
  		 </div>
  		<?php } ?>

</section>

<!-- Footer -->
  <?php 
  include("rodape.php");
  ?>
<!-- /Footer -->