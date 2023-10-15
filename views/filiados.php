<?php
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();
?>
<!-- Navigation -->
<?php include "menu.php";?>
<!-- /Navigation -->

<div class="container mt-5">
	<table class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Codigo</th>
				<th scope="col">Nome</th>
				<th scope="col">Dojo</th>
				<th scope="col">Graduação</th>
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
						<th class="font-weight-bold" scope="row"><?php echo $id_filiado; ?></th>
						<td class="text-capitalize"><?php echo $nome; ?></td>
						<td class="text-capitalize"><?php echo $dojo; ?></td>
						<td class="text-capitalize"><?php echo $graduacao["graduacao"]; ?></td>
					</tr>
			<?php }
}?>
		</tbody>
	</table>
</div>

<?php
include "rodape.php";
?>
