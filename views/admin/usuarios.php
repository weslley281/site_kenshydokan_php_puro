<div class="tab-pane fade show active">
    <div class="container text-center">
      <h2>Todos os Usuários</h2>
      <table id="minhaTabela" class="table table-bordered" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Imagem</th>
				<th scope="col">Nome</th>
				<th scope="col">Nivel</th>
				<th scope="col">Email</th>
				<th scope="col">Telefone</th>
				<th scope="col">Criação</th>
				<th scope="col">Alteração</th>
				<th scope="col">Ações</th>
			</tr>
		</thead>
		<tbody>
			<?php
$busca = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexao, $busca);
$linha = mysqli_num_rows($resultado);
if ($linha == '') {
    echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
} else {
    while ($res_usuario = mysqli_fetch_array($resultado)) {
        $id_usuario = $res_usuario["id_usuario"];
        $nome = $res_usuario["nome"];
        $nivel = $res_usuario["nivel"];
        $id_imagem = $res_usuario["id_imagem"];
        $id_fil = $res_usuario["id_fil"];
        $email = $res_usuario["email"];
        $telefone = $res_usuario["telefone"];
        $dataCriacao = $res_usuario["dataCriacao"];
        $dataMudanca = $res_usuario["dataMudanca"];

        $res_imagem = ImagemRepositorio::procura_imagem($id_imagem);
        $caminho_imagem = $res_imagem["caminho"];
        $nome_imagem = $res_imagem["nome"];
        ?>
					<tr>
						<th class="font-weight-bold" scope="row"><img class="img-fluid" src="<?php echo $caminho_imagem; ?>" width="50" height="50" alt="<?php echo $nome_imagem; ?>"></th>
						<td class="text-capitalize"><?php echo $nome; ?></td>
						<td class="text-capitalize"><?php echo $nivel; ?></td>
						<td class="text-capitalize"><?php echo $email; ?></td>
						<td class="text-capitalize"><?php echo $telefone; ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataCriacao), "d/m/Y"); ?></td>
						<td class="text-capitalize"><?php echo date_format(date_create($dataMudanca), "d/m/Y"); ?></td>
						<td class="text-capitalize">

            <a title="Editar" class="btn btn-info" href="editar_usuario_admin.php?id=<?php echo $id_usuario; ?>"><i class="fas fa-edit"></i></a>

            <button title="Excluir" type="button" class="btn btn-danger" data-toggle="modal" data-target="#usuarioModal<?php echo $id_usuario ?>">
              <i class="fa fa-minus-square"></i>
            </button>
            </td>
					</tr>

          <!-- Modal -->
          <div class="modal fade" id="usuarioModal<?php echo $id_usuario ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content bg-danger">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Você deseja realmente deletar esse usuário:</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <h2><?php echo $nome ?>?</h2>
                  </div>
                </div>
                <div class="modal-footer">
                  <form action="../controllers/usuarioController.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tipo" value="deletar">
                    <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
			<?php }}?>
		</tbody>
	</table>
    </div>
  </div>