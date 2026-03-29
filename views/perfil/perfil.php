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

						if ($usuario["nivel"] == "aluno") {
							echo "<h3 class='text-center'>Você é um usuário do tipo 'aluno'. Para acessar os cursos, por favor, entre em contato com o administrador para alterar seu nível de acesso.</h3>";
							echo "<hr>";
							echo "<h4 class='text-center'>Se você já é filiado, mas seu nível ainda está como 'aluno', por favor, contate o administrador para corrigir essa situação.</h4>";
							echo "<hr>";
							echo "<h5 class='text-center'>Lembre-se de que apenas usuários com níveis superiores a 'aluno' podem acessar os cursos disponíveis na plataforma.</h5>";
							echo "<hr>";
							echo "<h6 class='text-center'>Obrigado por sua compreensão!</h6>";
							echo "<hr>";
							return;
						}
						
						if ($filiado["confirmacao"] == "nao") {
							echo "<h3 class='text-center'>Seu status de filiação ainda está pendente. Para acessar os cursos, por favor, aguarde a confirmação da sua filiação ou entre em contato com o administrador para mais informações.</h3>";
							echo "<hr>";
							echo "<h4 class='text-center'>Se você já enviou sua filiação, por favor, aguarde a confirmação. Caso contrário, entre em contato com o administrador para iniciar o processo de filiação.</h4>";
							echo "<hr>";
							echo "<h5 class='text-center'>Lembre-se de que apenas usuários com filiação confirmada podem acessar os cursos disponíveis na plataforma.</h5>";
							echo "<hr>";
							echo "<h6 class='text-center'>Obrigado por sua compreensão!</h6>";
							echo "<hr>";
							return;
						}else{
						$busca = "SELECT * FROM cursos WHERE situacao = 'aprovado'";
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
						<?php }} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include __DIR__ . "/rodape.php"; ?>