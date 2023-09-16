<!-- Navigation -->
  <?php include("menu.php"); ?>
<!-- /Navigation -->
<?php 
include_once("../classes/connection.php");
$c = new Connection();
$conexao = $c->connect();  
$id_postagem = $_GET["id"];

$busca_usuario = "SELECT * FROM usuarios WHERE email = '$filiado'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

$id_imagem = $usuario["id_imagem"];
$busca_imagem = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
$resultado_imagem = mysqli_query($conexao, $busca_imagem);
$imagem = mysqli_fetch_array($resultado_imagem);

$id_filiado = $usuario["id_fil"];
$busca_filiado = "SELECT * FROM filiados WHERE id_filiado = '$id_filiado'";
$resultado_filiado = mysqli_query($conexao, $busca_filiado);
$filiado = mysqli_fetch_array($resultado_filiado);
$confirmacao = $filiado["confirmacao"];
if ($confirmacao == "sim") {
 $ativo = "Você está filiado";
}else{
 $ativo = "Aguardando Cofirmação de Filiação, Não Está Filiado Não";
}

$id_graduacao = $filiado["id_graduacao"];
$busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
$resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
$graduacao = mysqli_fetch_array($resultado_graduacao);

if($filiado != ""){
 ?>
<body>
  <div class="container mt-5">
	  <!-- Page Content -->
	  <div class="container">

	    <div class="row">

	      <div class="col-lg-3">

	        <h1 class="my-4">Meu Perfil</h1>
	        <div class="list-group">
	          <a href="perfil.php" class="list-group-item bg-light text-dark">Perfil</a>
	          <a href="editar_perfil.php" class="list-group-item bg-light text-dark">Editar Perfil</a>
	          <?php
	            if($usuario["tipo"] == 1 or $usuario["tipo"] == 2){
	          ?>
	          <a href="exame_graduacao.php" class="list-group-item bg-light text-dark">Exame de Graduação</a>
	          <a href="criar_postagem.php" class="list-group-item bg-light text-dark">Criar Postagem</a>
	          <a href="postagens.php" class="list-group-item bg-light text-dark">Suas Postagens</a>
	          <a href="documentos.php" class="list-group-item bg-light text-dark">Arquivos para Baixar</a>
	          <?php } ?>
	          <a href="eventos.php" class="list-group-item bg-light text-dark">Eventos Online</a>
	          <a href="../funcoes/sair.php" class="list-group-item bg-light text-dark">Sair</a>
	        </div>

	      </div>
	      <!-- /.col-lg-3 -->
	      <!--dados do perfil -->
	      <div class="col-lg-9">
	        <button class="btn btn-danger mt-3" id="hide">Esconder informações</button>
            <button class="btn btn-success mt-3" id="show">Mostrar</button>
	      	<div id="esconder" class="text-center mt-3 mb-3">
	      		<div class="row">
	      			<!-- card do perfil -->
	      			<div class="col-5 mb-4">
	      				<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="../imagens/<?php echo $imagem["nome"] ?>" alt="">
						  <div class="card-body">
						    <h5 class="card-title"><?php echo $usuario["nome"]; ?></h5>
						  </div>
						</div>
	      			</div>
	      			<!-- mais informações -->
	      			<div class="col mb-4">
	      				<div class="card">
						  <h5 class="card-header"><?php echo "$ativo"; ?></h5>
						  <div class="card-body">
						    <h5 class="card-title">Sua graduação é <?php echo $graduacao["graduacao"]; ?></h5>
						    <p class="card-text">Dojo: <?php echo $filiado["dojo"]; ?></p>
						    <p class="card-text">E-mail: <?php echo $usuario["email"]; ?></p>
						    <p class="card-text">Telefone: <?php echo $usuario["telefone"]; ?></p>
						  </div>
						</div>
	      			</div>
	      		</div>
	      	</div>
	      	<!--Fim dados do perfil -->
	      	<hr>
	      	<div class="text-center"><h1><strong>Editar Postagem</strong></h1></div>
	        <?php         
                $query = "SELECT * from postagens where id_postagem = '$id_postagem'";
                $result = mysqli_query($conexao, $query);
                while($postagem = mysqli_fetch_array($result)){
              ?>
	        <form class="mb-3" action="../funcoes/editar_postagem.php" method="POST">
	        	<div id="sample">
	                <input class="form-control form-control-lg mt-2" type="hidden" value="<?php echo $id_postagem ?>" name="id_postagem">
	                <input class="form-control form-control-lg mt-2 mb-2" type="text" value="<?php echo $postagem["titulo"] ?>" name="titulo">
	                <textarea name="conteudo" rows="20">
	                   <?php echo $postagem["conteudo"]; ?>
	                </textarea>
	                <input class="btn btn-success mt-3" type="submit" name="editar" value="editar">
	            </div>
            </form>
            <?php } ?>
	        </div>
	        <!-- /.row -->

	      </div>
	      <!-- /.col-lg-9 -->

	    </div>
	    <!-- /.row -->

	  </div>
	  <!-- /.container -->
  </div>

  <?php
  include("rodape.php");
  }else{
  	header("location:login.php");
  }
   ?>
</body>
</html>
