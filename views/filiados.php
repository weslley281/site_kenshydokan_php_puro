<?php
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();
?>
<!-- Navigation -->
<?php include "menu.php";?>
<!-- /Navigation -->

<div class="container mt-5">
	<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Nome</th>
				<th>Dojo</th>
				<th>Graduação</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM filiados where confirmacao = 'sim' order by id_filiado asc";
$resultado = mysqli_query($conexao, $busca);
$linha = mysqli_num_rows($resultado);
if ($linha == '') {
    echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
} else {
    while ($res = mysqli_fetch_array($resultado)) {
        $id_filiado = $res["id_filiado"];
        $nome = $res["nome"];
        $dojo = $res["dojo"];
        $id_graduacao = $res["id_graduacao"];

        $busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
        $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
        $graduacao = mysqli_fetch_array($resultado_graduacao);
        ?>
					<tr>
						<td><?php echo $id_filiado; ?></td>
						<td><?php echo $nome; ?></td>
						<td><?php echo $dojo; ?></td>
						<td><?php echo $graduacao["graduacao"]; ?></td>
					</tr>
			<?php }
}?>
		</tbody>
	</table>
</div>

<!-- Footer -->
<?php
include "rodape.php";
?>
<!-- /Footer -->