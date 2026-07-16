<?php
include "menu.php";
include_once "../models/filiadoModel.php";
include_once "../models/graduacaoModel.php";

// Busca todas as graduações e monta um array associativo por id
$graduacaoRepositorio = new Graduacao();
$graduacoes = [];
foreach ($graduacaoRepositorio->listarGraduacoes() as $g) {
	$graduacoes[$g['id_graduacao']] = $g['graduacao'];
}

$filiadoModel = new FiliadoModel();
$filiadosConfirmados = $filiadoModel->listarFiliadosAtivos();
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
			if (empty($filiadosConfirmados)) {
				echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
			} else {
				foreach ($filiadosConfirmados as $res) {
					$id_filiado = $res["id_filiado"];
					$nome = $res["nome"];
					$dojo = $res["dojo"];
					$id_graduacao = $res["id_graduacao"];
					$graduacao_nome = isset($graduacoes[$id_graduacao]) ? $graduacoes[$id_graduacao] : '';
			?>
					<tr>
						<th class="font-weight-bold" scope="row"><?php echo $id_filiado; ?></th>
						<td class="text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
						<td class="text-capitalize"><?php echo htmlspecialchars($dojo); ?></td>
						<td class="text-capitalize"><?php echo htmlspecialchars($graduacao_nome); ?></td>
					</tr>
			<?php }
			} ?>
		</tbody>
	</table>
</div>

<?php
include "rodape.php";
?>