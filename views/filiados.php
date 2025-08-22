<?php
include "menu.php";
include_once "../db/conexao.php";
include_once "../repositorios/graduacaoRepositorio.php";

$c = new Conexao();
$conexao = $c->conectar();

// Busca todas as graduações e monta um array associativo por id
$graduacaoRepositorio = new GraduacaoRepositorio();
$graduacoes = [];
foreach ($graduacaoRepositorio->listarGraduacoes() as $g) {
	$graduacoes[$g['id_graduacao']] = $g['graduacao'];
}
?>

<div class="container mt-5">
	<table id="minhaTabela" class="table table-bordered" width="100%" cellspacing="0">
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
					$graduacao_nome = isset($graduacoes[$id_graduacao]) ? $graduacoes[$id_graduacao] : '';
			?>
					<tr>
						<th class="font-weight-bold" scope="row"><?php echo $id_filiado; ?></th>
						<td class="text-capitalize"><?php echo $nome; ?></td>
						<td class="text-capitalize"><?php echo $dojo; ?></td>
						<td class="text-capitalize"><?php echo $graduacao_nome; ?></td>
					</tr>
			<?php }
			} ?>
		</tbody>
	</table>
</div>

<?php
include "rodape.php";
?>