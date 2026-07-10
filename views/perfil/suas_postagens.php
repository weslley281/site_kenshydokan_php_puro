<?php
$page_title = "Minhas Postagens";
include __DIR__ . "/_perfil_auth.php";

// Bloqueia usuários que não são admin ou sensei
if ($_SESSION["nivel"] !== "admin" && $_SESSION["nivel"] !== "sensei") {
    echo "<script language='javascript'>window.alert('Você não tem permissão para acessar esta página.'); </script>";
    echo "<script language='javascript'>window.location='perfil.php'; </script>";
    exit();
}

include __DIR__ . "/menu.php";
?>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<!-- Content -->
				<div class="col-lg-9">					
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>

					<div class="card border-0 shadow-sm rounded-lg mt-4">
						<div class="card-body p-4">
							
							<div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
								<h3 class="font-weight-bold text-dark mb-0">
									<i class="fa-solid fa-newspaper text-danger mr-2"></i>Minhas Postagens
								</h3>
								<a href="criar_postagem.php" class="btn btn-danger font-weight-bold rounded-pill px-4 shadow-sm">
									<i class="fa-solid fa-square-plus mr-1"></i> Nova Postagem
								</a>
							</div>

							<!-- Começo das tabelas de Postagens -->
							<div class="table-responsive mt-3">
								<table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
									<thead>
										<tr class="text-secondary small font-weight-bold border-bottom">
											<th scope="col" style="width: 60px;">ID</th>
											<th scope="col">Título</th>
											<th scope="col">Status</th>
											<th scope="col">Data de Criação</th>
											<th scope="col" class="text-center" style="width: 120px;">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php
										include_once __DIR__ . "/../../models/publicacaoModel.php";
										$id_usuario = $usuario["id_usuario"];
										$postagens = Publicacao::buscarPostagensPorUsuario($id_usuario);
										$linha = count($postagens);

										if ($linha == 0) {
											echo '<tr><td colspan="5" class="text-center text-muted py-4">Você ainda não criou nenhuma postagem.</td></tr>';
										} else {
											foreach ($postagens as $postagem) {
												$status = strtolower($postagem["status"]);
												$badge_class = ($status === 'aprovado' || $status === 'ativo') ? 'success' : 'warning';
												$status_label = ($status === 'aprovado' || $status === 'ativo') ? 'Publicado' : 'Aguardando';
										?>
												<tr>
													<td class="align-middle font-weight-bold text-secondary">#<?php echo $postagem["id_publicacao"]; ?></td>
													<td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($postagem["titulo"]); ?></td>
													<td class="align-middle">
														<span class="badge badge-<?php echo $badge_class; ?> text-uppercase px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
															<?php echo $status_label; ?>
														</span>
													</td>
													<td class="align-middle text-muted small"><?php echo date('d/m/Y', strtotime($postagem["dataCriacao"])); ?></td>
													<td class="align-middle text-center">
														<a title="Editar" class="btn btn-sm btn-outline-info border-0 rounded-circle mr-1" href="editar_postagem.php?id=<?php echo $postagem["id_publicacao"]; ?>" style="width: 32px; height: 32px; padding: 5px 0;">
															<i class="fas fa-edit"></i>
														</a>

														<!-- Botão Excluir que abre modal -->
														<button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluir<?php echo $postagem["id_publicacao"]; ?>" title="Excluir" style="width: 32px; height: 32px; padding: 5px 0;">
															<i class="fa-solid fa-trash-can"></i>
														</button>

														<!-- Modal de Confirmação de Exclusão -->
														<div class="modal fade" id="modalExcluir<?php echo $postagem["id_publicacao"]; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirLabel<?php echo $postagem["id_publicacao"]; ?>" aria-hidden="true">
															<div class="modal-dialog modal-dialog-centered" role="document">
																<div class="modal-content border-0 shadow-lg">
																	<div class="modal-header bg-danger text-white border-0 py-3">
																		<h5 class="modal-title font-weight-bold" id="excluirLabel<?php echo $postagem["id_publicacao"]; ?>">
																			<i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
																		</h5>
																		<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body p-4 text-center">
																		<p class="lead mb-2">Tem certeza que deseja excluir esta postagem?</p>
																		<h5 class="font-weight-bold text-danger mb-0"><?php echo htmlspecialchars($postagem["titulo"]); ?></h5>
																		<p class="text-muted mt-2 small">Esta ação apagará o post e sua imagem de capa permanentemente.</p>
																	</div>
																	<div class="modal-footer border-0 bg-light py-3 d-flex justify-content-center">
																		<button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
																		<a class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm" href="../../controllers/deletar_postagem.php?id=<?php echo $postagem["id_publicacao"]; ?>">Confirmar Exclusão</a>
																	</div>
																</div>
															</div>
														</div>

													</td>
												</tr>
										<?php }
										} ?>
									</tbody>
									<tfoot>
										<tr class="bg-light">
											<th colspan="4" class="text-right text-secondary small py-3">Total de Artigos:</th>
											<th class="text-center font-weight-bold text-dark py-3"><?php echo $linha; ?></th>
										</tr>
									</tfoot>
								</table>
							</div>
							<!-- Fim das tabelas de Postagens -->

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include __DIR__ . "/rodape.php"; ?>