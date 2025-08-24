<?php
include "menu.php";
include_once "../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();
?>

<section class="container">

	<div>
		<?php
		$busca = "SELECT * FROM campeonatos order by dataCriacao asc";
		$resultado = mysqli_query($conexao, $busca);
		while ($res = mysqli_fetch_array($resultado)) {
			$id_campeonato = $res["id_campeonato"];
			$titulo_camp = $res["titulo"];
			$subtitulo = $res["subtitulo"];
			$endereco = $res["endereco"];
			$data_camp = $res["dataCriacao"];
			$ativo_camp = $res["ativo"];
			if ($ativo_camp == "sim") {
				$ativo = "Faça sua inscrição";
			} else {
				$ativo = "Campeonato já realizado";
			}
		?>
			<div class="container border mt-3 mb-3">
				<h4><?php echo $titulo_camp; ?></h4>
				<blockquote class="blockquote">Será Realizado em: <?php echo $data_camp; ?><br>

					<?php echo $subtitulo; ?>.<br>

					<?php echo $endereco; ?><br>
				</blockquote>
				<div class="text-danger text-right">
					<?php echo $ativo; ?>
				</div>
			</div>
		<?php } ?>
	</div>

</section>

<?php include "rodape.php"; ?>