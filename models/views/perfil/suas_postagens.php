<?php
$page_title = "Postagens";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
?>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<!-- Content -->
				<div class="col-lg-9">					
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>

					<hr>
					<div class="text-center">
						<h1><strong>Minhas Postagens</strong></h1>
					</div>
					<!-- Começo das tabelas de Postagens -->
					<div class="row">
						<div class="container mt-5">
							<div class="text-center">
								<h2>Postagens</h2>
							</div>
							<div class="table-responsive">
								<table id="minhaTabela" class="table table-bordered display" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>id</th>
											<th>Titulo</th>
											<th>data</th>
											<th>açoes</th>
										</tr>
									</thead>
									<tbody>
										<?php
										include_once __DIR__ . "/../../models/publicacaoModel.php";
										$id_usuario = $usuario["id_usuario"];
										$postagens = Publicacao::buscarPostagensPorUsuario($id_usuario);
										$linha = count($postagens);

										if ($linha == 0) {
											echo "<h3> Você não postou nada!! </h3>";
										} else {
											foreach ($postagens as $postagem) {
										?>
												<tr>
													<td><?php echo $postagem["id_publicacao"]; ?></td>
													<td><?php echo $postagem["titulo"]; ?></td>
													<td><?php echo $postagem["dataCriacao"]; ?></td>
													<td>
														<a title="Editar" class="btn btn-info" href="editar_postagem.php?id=<?php echo $postagem["id_publicacao"]; ?>"><i class="fas fa-edit"></i></a>

														<a title="Excluir" class="btn btn-danger" href="../../controllers/deletar_postagem.php?id=<?php echo $postagem["id_publicacao"]; ?>"><i class="fa fa-minus-square"></i></a>
													</td>
												</tr>
										<?php }
										} ?>
									</tbody>
									<tfoot>
										<tr>
											<th></th>
											<th></th>
											<th></th>
											<th>Resultados:<?php echo $linha; ?></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<!-- Fim das tabelas de Postagens -->
				</div>
			</div>
		</div>
	</div>
<?php include __DIR__ . "/../rodape.php"; ?>