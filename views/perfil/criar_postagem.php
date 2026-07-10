<?php
$page_title = "Criar Postagens";
include __DIR__ . "/_perfil_auth.php";

// Bloqueia usuários que não são admin ou sensei
if ($_SESSION["nivel"] !== "admin" && $_SESSION["nivel"] !== "sensei") {
    echo "<script language='javascript'>window.alert('Você não tem permissão para acessar esta página.'); </script>";
    echo "<script language='javascript'>window.location='perfil.php'; </script>";
    exit();
}

include __DIR__ . "/menu.php";
?>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<div class="col-lg-9 mb-4">
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>

					<div class="card border-0 shadow-sm rounded-lg mt-4">
						<div class="card-body p-4">
							<div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
								<h3 class="font-weight-bold text-dark mb-0">
									<i class="fa-solid fa-square-plus text-danger mr-2"></i>Criar Nova Postagem
								</h3>
								<a href="suas_postagens.php" class="btn btn-outline-secondary btn-sm font-weight-bold rounded-pill px-3">
									<i class="fa-solid fa-arrow-left mr-1"></i> Voltar
								</a>
							</div>
							
							<?php if ($_SESSION["nivel"] === "sensei"): ?>
								<div class="alert alert-info border-0 shadow-sm rounded-lg mb-4" role="alert">
									<i class="fa-solid fa-circle-info mr-2"></i>
									<strong>Nota:</strong> Como instrutor/sensei, sua postagem ficará no estado "Aguardando" e necessitará da validação de um administrador para se tornar pública no site.
								</div>
							<?php else: ?>
								<div class="alert alert-success border-0 shadow-sm rounded-lg mb-4" role="alert">
									<i class="fa-solid fa-circle-check mr-2"></i>
									<strong>Nota:</strong> Como administrador, seu artigo será publicado diretamente e ficará visível de imediato no blog.
								</div>
							<?php endif; ?>

							<form action="../../controllers/postagemController.php" method="POST" enctype="multipart/form-data">
								<input type="hidden" value="inserir" name="tipo">

								<!-- Título -->
								<div class="form-group mb-4">
									<label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Título do Artigo</label>
									<input id="titulo" class="form-control form-control-lg bg-light border-0" type="text" placeholder="Digite um título cativante para a publicação" name="titulo" required autofocus style="font-size: 1.1rem; border-radius: 8px;">
								</div>

								<!-- Imagem de Capa -->
								<div class="form-group mb-4">
									<label for="capa" class="text-secondary small font-weight-bold text-uppercase">Imagem de Capa (Opcional)</label>
									<div class="custom-file bg-light border-0" style="border-radius: 8px;">
										<input type="file" class="custom-file-input" id="capa" name="capa" accept="image/*">
										<label class="custom-file-label bg-light border-0" for="capa" id="capa-label" style="border-radius: 8px;">Escolher imagem de capa...</label>
									</div>
									<small class="text-muted d-block mt-2">A imagem de capa selecionada será comprimida localmente em tempo real para otimizar o carregamento da página.</small>
									
									<!-- Container de Preview da Capa -->
									<div class="mt-3 text-center" style="display: none;" id="capaPreviewContainer">
										<img id="capaPreview" src="#" alt="Preview da Capa" class="img-thumbnail rounded shadow-sm" style="max-height: 250px; width: 100%; object-fit: cover;">
									</div>
								</div>

								<!-- Conteúdo -->
								<div class="form-group mb-4">
									<label for="conteudo" class="text-secondary small font-weight-bold text-uppercase">Conteúdo do Artigo</label>
									<textarea id="conteudo" name="conteudo" rows="20" placeholder="Escreva seu artigo aqui..." class="form-control bg-light border-0" style="border-radius: 8px;">Comece a criar seu artigo.</textarea>
								</div>

								<div class="mt-4">
									<button type="submit" name="salvar" class="btn btn-danger btn-lg font-weight-bold rounded-pill shadow-sm px-5 py-2">
										<i class="fa-solid fa-save mr-2"></i> Salvar e Publicar
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Script de Compressão de Capa -->
	<script>
	document.addEventListener("DOMContentLoaded", function() {
		const capaInput = document.getElementById("capa");
		const capaLabel = document.getElementById("capa-label");
		const previewContainer = document.getElementById("capaPreviewContainer");
		const previewImg = document.getElementById("capaPreview");

		if (capaInput) {
			capaInput.addEventListener("change", function() {
				if (this.files && this.files[0]) {
					const file = this.files[0];
					capaLabel.textContent = file.name;

					if (!file.type.startsWith('image/')) {
						return;
					}

					const reader = new FileReader();
					reader.onload = function(e) {
						const img = new Image();
						img.onload = function() {
							const canvas = document.createElement('canvas');
							let width = img.width;
							let height = img.height;

							// Limites para imagem de capa (max 1200px de largura/altura)
							const MAX_WIDTH = 1200;
							const MAX_HEIGHT = 800;

							if (width > height) {
								if (width > MAX_WIDTH) {
									height *= MAX_WIDTH / width;
									width = MAX_WIDTH;
								}
							} else {
								if (height > MAX_HEIGHT) {
									width *= MAX_HEIGHT / height;
									height = MAX_HEIGHT;
								}
							}

							canvas.width = width;
							canvas.height = height;

							const ctx = canvas.getContext('2d');
							ctx.drawImage(img, 0, 0, width, height);

							canvas.toBlob(function(blob) {
								const extensao = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
								const novoNome = file.name.substring(0, file.name.lastIndexOf('.')) + '_min' + (extensao === '.webp' ? '.webp' : '.jpg');
								const novoFormato = extensao === '.webp' ? 'image/webp' : 'image/jpeg';
								
								const compressedFile = new File([blob], novoNome, {
									type: novoFormato,
									lastModified: Date.now()
								});

								try {
									const dataTransfer = new DataTransfer();
									dataTransfer.items.add(compressedFile);
									capaInput.files = dataTransfer.files;
								} catch (err) {
									console.error("Erro no DataTransfer:", err);
								}

								const blobURL = URL.createObjectURL(blob);
								previewImg.src = blobURL;
								previewContainer.style.display = "block";
							}, file.type === 'image/webp' ? 'image/webp' : 'image/jpeg', 0.75);
						};
						img.src = e.target.result;
					};
					reader.readAsDataURL(file);
				}
			});
		}
	});
	</script>

	<?php
	include __DIR__ . "/rodape.php";
	?>