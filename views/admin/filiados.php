<div class="tab-pane fade show active">
  <div class="container text-center">
      <h2>Todos os Filiados</h2>
      <table id="minhaTabela4" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Codigo</th>
				<th scope="col">Nome</th>
				<th scope="col">Graduação</th>
				<th scope="col">Dojo</th>
				<th scope="col">Email</th>
				<th scope="col">Telefone</th>
				<th scope="col">Cidade</th>
				<th scope="col">Estado</th>
				<th scope="col">Criação</th>
				<th scope="col">Alteração</th>
				<th scope="col">Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM filiados";
$resultado = mysqli_query($conexao, $busca);
$linha = mysqli_num_rows($resultado);
if ($linha == '') {
    echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
} else {
    while ($res_filiado = mysqli_fetch_array($resultado)) {
        $id_filiado = $res_filiado["id_filiado"];
        $nome = $res_filiado["nome"];
        $id_graduacao = $res_filiado["id_graduacao"];
        $dojo = $res_filiado["dojo"];
        $email = $res_filiado["email"];
        $telefone = $res_filiado["telefone"];
        $cidade = $res_filiado["cidade"];
        $id_estado = $res_filiado["id_estado"];

        $res_graduacao = GraduacaoRepositorio::buscarGraduacao($id_graduacao);
        $graduacao = $res_graduacao["graduacao"];

        $busca_estado = "SELECT * FROM estados WHERE id_estado = '$id_estado'";
        $resultado_estado = mysqli_query($conexao, $busca_estado);
        $estado = mysqli_fetch_array($resultado_estado);
        ?>
					<tr>
						<th class="font-weight-bold" scope="row"><?php echo $id_filiado; ?></th>
						<td class="text-capitalize"><?php echo $nome; ?></td>
						<td class="text-capitalize"><?php echo $graduacao; ?></td>
						<td class="text-capitalize"><?php echo $dojo; ?></td>
						<td class="text-capitalize"><?php echo $email; ?></td>
						<td class="text-capitalize"><?php echo $telefone; ?></td>
						<td class="text-capitalize"><?php echo $cidade; ?></td>
						<td class="text-capitalize"><?php echo $estado["estado"] != null ? $estado["estado"] : "" ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
						<td class="text-capitalize"></td>
					</tr>
			<?php }
}?>
		</tbody>
	</table>
    </div>
  </div>