<?php
$page_title = "Eventos";
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
						<h1><strong>Eventos</strong></h1>
					</div>
					<div class="row">
						<h3>Em breve terá eventos aqui</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include __DIR__ . "/../rodape.php"; ?>