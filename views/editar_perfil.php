<?php
include "menu.php";
include_once "../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();
$id_usuario = $_SESSION['id_usuario'];

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
	$busca_graduacao = "SELECT * FROM graduacoes WHERE id_graduacao = '$id_graduacao'";
	$resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
	$graduacao = mysqli_fetch_array($resultado_graduacao);
} else {
	$estaFiliado = "Sem dados";
	$filiado = array(
		"dojo" => "a definir",
	);
	$graduacao = array(
		'graduacao' => 'sem registro',
	);
}

if (isset($_SESSION["id_usuario"])) {
?>

	<body>
		<div class="container mt-5">
			<!-- Page Content -->
			<div class="container">

				<div class="row">

					<div class="col-lg-3">

						<h1 class="my-4">Editar Perfil</h1>
						<div class="list-group">
							<a href="perfil.php" class="list-group-item bg-light text-dark">Perfil</a>
							<a href="editar_perfil.php" class="list-group-item bg-danger text-dark">Editar Perfil</a>
							<?php if ($usuario["nivel"] == "admin" || $usuario["nivel"] == "sensei") { ?>
								<a href="exame_graduacao.php" class="list-group-item bg-light text-dark">Exame de Graduação</a>
								<a href="criar_postagem.php" class="list-group-item bg-light text-dark">Criar Postagem</a>
								<a href="suas_postagens.php" class="list-group-item bg-light text-dark">Suas Postagens</a>
								<a href="documentos.php" class="list-group-item bg-light text-dark">Arquivos para Baixar</a>
							<?php } ?>
							<?php if ($usuario["nivel"] == "admin") { ?>
								<a href="admin.php" class="list-group-item bg-light text-dark">Administrativo</a>
							<?php } ?>
							<a href="eventos.php" class="list-group-item bg-light text-dark">Eventos Online</a>
							<a href="../controllers/sair.php" class="list-group-item bg-light text-dark">Sair</a>
						</div>

					</div>
					<!-- /.col-lg-3 -->
					<!--dados do perfil -->
					<div class="col-lg-9">
						<div class="text-center mt-3 mb-3">
							<div class="row d-inline-flex">
								<!-- card do perfil -->
								<div class="col d-inline-flex mb-4">
									<div class="card" style="width: 18rem;">
										<img id="imagePreview" class="card-img-top" src="<?php echo $imagem["caminho"] ?>" alt="<?php echo $usuario["nome"]; ?>">
										<div class="card-body">
											<h5 class="card-title"><?php echo $usuario["nome"]; ?></h5>
										</div>
									</div>
								</div>
								<!-- mais informações -->
								<div class="col">

									<form action="../controllers/usuarioController.php" method="post" enctype="multipart/form-data">
										<input type="hidden" value="editar_imagem" name="tipo">
										<input type="hidden" value="<?php echo $usuario["id_imagem"] ?>" name="id_imagem">
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario" readonly>

										<div class="form-group">
											<input class="form-control" type="file" id="imagem" name="imagem">
										</div>

										<input class="btn btn-secondary mb-2" type="submit" name="atualizar" value="atualizar imagem">
									</form>

									<form action="../controllers/usuarioController.php" method="post">
										<input class="form-control mb-2" type="hidden" value="edidar" name="tipo" readonly>
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario" readonly>
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_imagem"]; ?>" name="id_imagem" readonly>
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_fil"]; ?>" name="id_fil" readonly>
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["nivel"]; ?>" name="nivel" readonly>

										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $usuario["nome"]; ?>" name="nome" required>
										</div>

										<div class="form-group">
											<input class="form-control" type="email" value="<?php echo $usuario["email"]; ?>" name="email" readonly>
										</div>

										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $usuario["telefone"]; ?>" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
										</div>

										<input class="btn btn-success" type="submit" value="Salvar Alterações" name="editar">
									</form>

									<div id="trocar_senha">
										<form v-if="senha" action="../controllers/editar_senha.php" method="post">
											<input type="hidden" value="<?php echo $usuario["id_usuario"] ?>" name="id_usuario">
											<input type="hidden" value="editar_senha" name="tipo">

											<div class="form-group">
												<input type="password" class="form-control" placeholder="Digite a Senha a Nova Senha" name="nova_senha">
											</div>

											<div class="form-group">
												<input type="password" class="form-control" placeholder="Repita a Senha a Nova Senha" name="senha2">
											</div>
											<input type="submit" class="btn btn-success" value="Salvar Senha" name="">
										</form>
									</div>
								</div>
							</div>
						</div>
						<!--Fim dados do perfil -->
						<!-- /.row -->
					</div>
					<!-- /.col-lg-9 -->

				</div>
				<!-- /.row -->

			</div>
			<!-- /.container -->
		</div>

	<?php
	include "rodape.php";
} else {
	echo "<script language='javascript'>window.location='login.php'; </script>";
}
	?>