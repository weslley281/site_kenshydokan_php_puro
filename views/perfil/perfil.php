<?php
$page_title = "Meu Perfil";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
?>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<!-- Content -->
				<div class="col-lg-9">
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>
					<hr>
					<div class="text-center">
						<h1><strong>Cursos</strong></h1>
					</div>
					<div class="row">
						<?php
						$busca = "SELECT * FROM cursos";
						$resultado = mysqli_query($conexao, $busca);
						while ($curso = mysqli_fetch_array($resultado)) {
							$id_imagem_curso = $curso["id_imagem"];

							$busca_imagem_curso = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem_curso'";
							$resultado_imagem_curso = mysqli_query($conexao, $busca_imagem_curso);
							$imagem_curso = mysqli_fetch_array($resultado_imagem_curso);

							$id_categoria = $curso["id_categoria"];
							$busca_categoria = "SELECT * FROM categorias WHERE id_categoria = '$id_categoria'";
							$resultado_categoria = mysqli_query($conexao, $busca_categoria);
							$categoria = mysqli_fetch_array($resultado_categoria);
						?>
							<div class="col-lg-4 col-md-6 mb-4">
								<div class="card h-100">
									<a href="../assistir_aulas.php?id=<?php echo $curso["id_curso"]; ?>"><img class="card-img-top" src="../../img/<?php echo $imagem_curso["nome"]; ?>" alt="" width="150px" height="150px"></a>
									<div class="card-body">
										<h4 class="card-title">
											<a href="assistir_aulas.php?id=<?php echo $curso["id_curso"]; ?>"><?php echo $curso["nome"]; ?></a>
										</h4>
										<h5><?php echo $categoria["categoria"]; ?></h5>
										<p class="card-text"><?php echo $curso["descricao"]; ?></p>
										<p class="card-text">Professor: <?php echo $curso["professor"]; ?></p>
										<p class="card-text"><?php echo $curso["dataCriacao"]; ?></p>
									</div>
									<div class="card-footer">
										<small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include __DIR__ . "/../rodape.php"; ?>