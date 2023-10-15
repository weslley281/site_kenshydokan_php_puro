<?php
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();

include "menu.php";
?>
<!-- /Navigation -->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
<script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/galeria.css">
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox({
			openEffect: "none",
			closeEffect: "none"
		});

		$(".zoom").hover(function() {

			$(this).addClass('transition');
		}, function() {

			$(this).removeClass('transition');
		});
	});
</script>

<br>
<?php
$busca = "SELECT * FROM galeria";
$resultado = mysqli_query($conexao, $busca);
while ($galeria = mysqli_fetch_array($resultado)) {
    $id_galeria = $galeria["id_galeria"];
    ?>
	<div class="container page-top mt-5">
		<center>
			<h2><strong><?php echo $galeria["nome"]; ?></strong></h2>
		</center>
		<div class="row mt-5">
			<?php
$busca2 = "SELECT * FROM fotos WHERE id_galeria = '$id_galeria'";
    $resultado2 = mysqli_query($conexao, $busca2);
    while ($foto = mysqli_fetch_array($resultado2)) {
        ?>
				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a href="../slides/<?php echo $foto["foto"] ?>" class="fancybox" rel="ligthbox">
						<img src="../slides/<?php echo $foto["foto"] ?>" class="zoom img-fluid " alt="<?php echo $foto["foto"] ?>">

					</a>
				</div>
			<?php }?>

		</div>
	</div>
<?php }?>
<?php include "rodape.php";?>