<div class="tab-pane fade show active">
	<div class="container text-center">
		<h2>Todos os Filiados</h2>

		<div class="row my-4">
			<div class="col mx-1 my-1">
				<a href="criar_filiado.php" class="btn btn-outline-success btn-lg btn-block">Criar Filiado</a>
			</div>
		</div>

		<table id="minhaTabela4" class="table table-bordered" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th scope="col">Codigo</th>
					<th scope="col">Nome</th>
					<th scope="col">Graduação</th>
					<th scope="col">Dojo</th>
					<th scope="col">Cidade</th>
					<th scope="col">Estado</th>
					<th scope="col">Criação</th>
					<th scope="col">Alteração</th>
					<th scope="col">Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php
				include_once __DIR__ . "/../../models/filiadoModel.php";
				include_once __DIR__ . "/../../models/graduacaoModel.php";
				include_once __DIR__ . "/../../models/estadoModel.php";

				$estadoModel = new EstadoModel();
				$filiadoModelRepo = new FiliadoModel();
				$filiadosData = $filiadoModelRepo->listarTodosFiliadosCompleto();
				
				if (empty($filiadosData)) {
					echo "<h3> Não foram encontrados dados Cadastrados no Banco!! </h3>";
				} else {
					foreach ($filiadosData as $res_filiado) {
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

						$res_graduacao = Graduacao::buscarGraduacao($id_graduacao);
						$graduacao = $res_graduacao ? $res_graduacao["graduacao"] : "Sem graduação";

						$estado = $estadoModel->buscarPorId($id_estado);
				?>
						<tr>
							<th class="font-weight-bold" scope="row"><?php echo $id_filiado; ?></th>
							<td class="text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
							<td class="text-capitalize"><?php echo htmlspecialchars($graduacao); ?></td>
							<td class="text-capitalize"><?php echo htmlspecialchars($dojo); ?></td>
							<td class="text-capitalize"><?php echo htmlspecialchars($cidade); ?></td>
							<td class="text-capitalize"><?php echo $estado["estado"] != null ? htmlspecialchars($estado["estado"]) : "" ?></td>
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
										<p><?php echo htmlspecialchars($nome); ?></p>
									</div>
									<div class="modal-footer">
										<form action="../controllers/filiadoController.php" method="post">
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
										<p><?php echo htmlspecialchars($nome); ?></p>
									</div>
									<div class="modal-footer">
										<form action="../controllers/filiadoController.php" method="post">
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