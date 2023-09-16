<!-- Navigation -->
  <?php include("menu.php"); ?>
<!-- /Navigation -->
<?php 
include_once("../classes/connection.php");
$c = new Connection();
$conexao = $c->connect(); 

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

	        <h1 class="my-4">Postagens</h1>
	        <div class="list-group">
	          <a href="perfil.php" class="list-group-item bg-light text-dark">Perfil</a>
	          <a href="editar_perfil.php" class="list-group-item bg-light text-dark">Editar Perfil</a>
	          <?php
	            if($usuario["tipo"] == 1 or $usuario["tipo"] == 2){
	          ?>
	          <a href="exame_graduacao.php" class="list-group-item bg-light text-dark">Exame de Graduação</a>
	          <a href="criar_postagem.php" class="list-group-item bg-light text-dark">Criar Postagem</a>
	          <a href="postagens.php" class="list-group-item bg-danger text-dark">Suas Postagens</a>
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
	      	<div class="text-center"><h1><strong>Minhas Postagens</strong></h1></div>
	        <!-- Começo das tabelas de Postagens -->
              <div class="row">
                <div class="container mt-5">
                  <div class="text-center">
                    <h2>Postagens</h2>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th>id</th>
                            <th>Titulo</th>
                            <th>data</th>
                            <th>açoes</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          	$id_usuario = $usuario["id_usuario"];
                            $busca = "SELECT * FROM postagens WHERE id_usuario = '$id_usuario'";
                            $resultado = mysqli_query($conexao, $busca);
                            $linha = mysqli_num_rows($resultado);
    
                            if($linha == ''){
                                echo "<h3> Você não postou nada!! </h3>";
                            }else{
                              while($postagem = mysqli_fetch_array($resultado)){
                           ?>
                          <tr>
                            <td><?php echo $postagem["id_postagem"]; ?></td>
                            <td><?php echo $postagem["titulo"]; ?></td>
                            <td><?php echo $postagem["data"]; ?></td>
                            <td>
                              <a  title="Editar" class="btn btn-info" href="editar_postagem.php?id=<?php echo $postagem["id_postagem"]; ?>"><i class="fas fa-edit"></i></a>
    
                              <a title="Excluir" class="btn btn-danger" href="../funcoes/deletar_postagem.php?id=<?php echo $postagem["id_postagem"]; ?>"><i class="fa fa-minus-square"></i></a>
                            </td>
                          </tr>
                            <?php }} ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Resultados:<?php echo $linha; ?></th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
              </div>
              <!-- Fim das tabelas de Postagens -->
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
