<?php
$page_title = "Criar Postagens";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
?>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<div class="col-lg-9 mb-4">
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>

					<hr>
					<div class="text-center">
						<h1><strong>Criar Postagem</strong></h1>
					</div>
					<p class="text-danger mx-auto">A sua postagem ira aparecer no site depois que um administrador aprovar</p>
					<form action="../../controllers/postagemController.php" method="POST">
						<div id="sample">
							<input type="hidden" value="<?php echo $usuario["id_usuario"] ?>" name="id_usuario">
							<input type="hidden" value="inserir" name="tipo">

							<div class="form-group">
								<input class="form-control" type="text" placeholder="Titulo" name="titulo" required autofocus>
							</div>

							<div class="form-group">
								<textarea name="conteudo" rows="20" required>
	                    				Comece a criar.
	                				</textarea>
							</div>
							<input class="btn btn-success mt-3" type="submit" name="salvar" value="salvar">
						</div>
					</form>

				</div>

			</div>

		</div>
	</div>

	<?php
	include __DIR__ . "/rodape.php";
	?>