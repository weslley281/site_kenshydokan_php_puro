<div class="tab-pane fade show active">
  <div class="container text-center">
      <h2>Todas as  Postagens</h2>
      <table id="minhaTabela2" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Codigo</th>
				<th scope="col">Titulo</th>
				<th scope="col">Criação</th>
				<th scope="col">Alteração</th>
				<th scope="col">Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM postagens";
$resultado = mysqli_query($conexao, $busca);
$linha = mysqli_num_rows($resultado);
if ($linha == '') {
    echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
} else {
    while ($res_postagem = mysqli_fetch_array($resultado)) {
        $id_publicacao = $res_postagem["id_publicacao"];
        $titulo = $res_postagem["titulo"];
        $status = $res_postagem["status"];
        $dataCriacao = $res_postagem["dataCriacao"];
        $dataMudanca = $res_postagem["dataMudanca"];
        ?>
					<tr>
						<th class="font-weight-bold" scope="row"><?php echo $id_publicacao; ?></th>
						<td class="text-capitalize"><?php echo $titulo; ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
						<td class="text-capitalize">
              <a title="Editar" class="btn btn-info" href="editar_postagem.php?id=<?php echo $id_publicacao; ?>"><i class="fas fa-edit"></i></a>

            <a title="Editar" class="btn btn-info" href="../controllers/mudar_status_postagem.php?id=<?php echo $id_publicacao; ?>">
              <?php echo $status == "aguardando" ? '<i class="fa-regular fa-thumbs-down"></i>' : '<i class="fa-regular fa-thumbs-up"></i>' ?></a>
            </td>
					</tr>
			<?php }
}?>
		</tbody>
	</table>
    </div>
  </div>