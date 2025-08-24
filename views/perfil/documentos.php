<?php
$page_title = "Documentos";
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
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>

					<hr>
					<div class="text-center">
						<h1><strong>Documento para baixar</strong></h1>
					</div>
					<div class="row">
						<ul>
							<li><a href="../../arquivos/Dojokum.pdf"><b>Dojo Kum</b></a></li>
							<li><a href="../../arquivos/ficha.pdf"><b>Ficha para inscrever atleta em exame de graduação manualmente</b></a></li>
							<li><a href="../../arquivos/regras.pdf"><b>Regras para Campeonatos</b></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include __DIR__ . "/../rodape.php"; ?>