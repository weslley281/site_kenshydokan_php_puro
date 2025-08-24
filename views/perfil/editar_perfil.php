<?php
$page_title = "Editar Perfil";
include __DIR__ . "/../menu.php";
include __DIR__ . "/_perfil_auth.php";
?>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<!-- Content -->
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

								<form action="../../controllers/usuarioController.php" method="post" enctype="multipart/form-data">
									<input type="hidden" value="editar_imagem" name="tipo">
									<input type="hidden" value="<?php echo $usuario["id_imagem"] ?>" name="id_imagem">
									<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario" readonly>

									<div class="form-group">
										<input class="form-control" type="file" id="imagem" name="imagem">
									</div>

									<input class="btn btn-secondary mb-2" type="submit" name="atualizar" value="atualizar imagem">
								</form>

								<form action="../../controllers/usuarioController.php" method="post">
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
									<form v-if="senha" action="../../controllers/editar_senha.php" method="post">
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
				</div>
			</div>
		</div>
	</div>
<?php include __DIR__ . "/../rodape.php"; ?>