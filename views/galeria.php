<?php
include "menu.php";
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();

?>

<br>
<?php
$busca = "SELECT * FROM galeria";
$resultado = mysqli_query($conexao, $busca);
while ($galeria = mysqli_fetch_array($resultado)) {
    $id_galeria = $galeria["id_galeria"];
    ?>
	<div class="container mt-5">
		<div class="text-center">
			<h2><strong><?php echo $galeria["nome"]; ?></strong></h2>
		</div>
		<div class="row mt-5">
<?php
$busca2 = "SELECT * FROM fotos WHERE id_galeria = '$id_galeria'";
    $resultado2 = mysqli_query($conexao, $busca2);
    while ($foto = mysqli_fetch_array($resultado2)) {
        ?>
		<div class="col-md-4 mb-3">
			<img src="../slides/<?php echo $foto["foto"] ?>" class="img-thumbnail gallery-image" alt="<?php echo $foto["foto"] ?>">
		</div>
<?php }?>
		</div>
	</div>
<?php }?>

<!-- Modal para visualização de imagens -->
<div class="modal" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Imagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-fluid" id="modalImage" src="" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para abrir o modal e atualizar a imagem -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const galleryImages = document.querySelectorAll(".gallery-image");
        const modalImage = document.getElementById("modalImage");

        galleryImages.forEach(function(image) {
            image.addEventListener("click", function() {
                modalImage.src = this.src;
                $("#imageModal").modal("show"); // Use jQuery para mostrar o modal
            });
        });
    });
</script>

<?php include "rodape.php";?>