<div class="tab-pane fade show active">
	<div class="container text-center">
		<h2>Todos os Filiados</h2>

		<div class="row my-4">
			<div class="col mx-1 my-1">
				<a href="../views/criar_filiado.php" class="btn btn-outline-success btn-lg btn-block">Criar Filiado</a>
			</div>
		</div>

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
						$dataCriacao = $res_filiado["dataCriacao"];
						$dataMudanca = $res_filiado["dataMudanca"];

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
							<td class="text-capitalize">
								<div class="form-group">
									<a class="btn btn-primary" href="editar_filiado.php?id=<?php echo $id_filiado; ?>" title="Editar Filiado"><i class="fa-solid fa-pen-to-square"></i></a>
								</div>
								<div class="form-group">
									<button class="btn btn-danger" data-toggle="modal" data-target="#modalExcluirFiliado<?php echo $id_filiado; ?>" title="Excluir filiado"><i class="fa-regular fa-calendar-xmark"></i></button>
								</div>
								<!-- Botão Desconfirmar -->
								<div class="form-group">
									<button class="btn btn-warning" data-toggle="modal" data-target="#modalDesconfirmarFiliado<?php echo $id_filiado; ?>" title="Desconfirmar filiado">
										<i class="fa-solid fa-user-xmark"></i>
									</button>
								</div>
							</td>
						</tr>

						<!-- Modal Excluir -->
						<div class="modal fade" id="modalExcluirFiliado<?php echo $id_filiado; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja excluir:</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p><?php echo $nome; ?></p>
									</div>
									<div class="modal-footer">
										<form action="../controllers/filiadoController" method="post">
											<input type="hidden" name="tipo" value="excluir">
											<input type="hidden" name="id_filiado" value="<?php echo $id_filiado; ?>">

											<button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
											<button type="submit" class="btn btn-danger">Sim</button>
										</form>
									</div>
								</div>
							</div>
						</div>

						<!-- Modal Desconfirmar -->
						<div class="modal fade" id="modalDesconfirmarFiliado<?php echo $id_filiado; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDesconfirmarLabel<?php echo $id_filiado; ?>" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="modalDesconfirmarLabel<?php echo $id_filiado; ?>">Você tem certeza que deseja desconfirmar:</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p><?php echo $nome; ?></p>
									</div>
									<div class="modal-footer">
										<form action="../controllers/filiadoController" method="post">
											<input type="hidden" name="tipo" value="desconfirmar">
											<input type="hidden" name="id_filiado" value="<?php echo $id_filiado; ?>">

											<button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
											<button type="submit" class="btn btn-warning">Sim</button>
										</form>
									</div>
								</div>
							</div>
						</div>
				<?php }
				} ?>
			</tbody>
		</table>
	</div>
</div>