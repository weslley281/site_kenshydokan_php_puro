<?php
$page_title = "Documentos";
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
						<h1><strong>Documento para baixar</strong></h1>
					</div>
					<?php
					include_once __DIR__ . "/../../models/documentoModel.php";
					$docModel = new DocumentoModel();
					$documentos = $docModel->listarTodos();
					?>
					<div class="row pl-3">
						<?php if (!empty($documentos)): ?>
							<ul class="list-unstyled" style="line-height: 2;">
								<?php foreach ($documentos as $doc): ?>
									<li class="mb-2">
										<i class="fa-solid fa-file-pdf text-danger mr-2"></i>
										<a href="../../arquivos/<?php echo htmlspecialchars($doc['arquivo']); ?>" target="_blank" class="text-dark font-weight-bold">
											<?php echo htmlspecialchars($doc['titulo']); ?>
										</a>
										<?php if (!empty($doc['descricao'])): ?>
											<span class="text-secondary d-block small ml-4"><?php echo htmlspecialchars($doc['descricao']); ?></span>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else: ?>
							<p class="text-muted">Nenhum documento disponível para download no momento.</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include __DIR__ . "/rodape.php"; ?>