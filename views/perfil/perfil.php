<?php
$page_title = "Meu Perfil";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../models/listaPresencaModel.php";

$presencaModel = new ListaPresencaModel();
$frequencias = [];
if (!empty($filiado['id_filiado'])) {
    $frequencias = $presencaModel->buscarFrequenciaPorFiliado($filiado['id_filiado']);
}
?>

<style>
.transition-hover {
    transition: all 0.25s ease;
}
.transition-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
}
</style>

<body>
	<div class="container mt-5">
		<div class="container">
			<div class="row">

				<?php include __DIR__ . "/_perfil_menu.php"; ?>

				<!-- Content -->
				<div class="col-lg-9">
					<?php include __DIR__ . "/_perfil_info_card.php"; ?>

					<?php if (!empty($frequencias)): ?>
						<div class="card border-0 shadow-sm rounded-lg mb-4 mt-3">
							<div class="card-body p-4">
								<h5 class="font-weight-bold text-danger mb-4 border-bottom pb-2">
									<i class="fa-solid fa-chart-line mr-2"></i>Frequência e Desempenho nas Aulas
								</h5>
								<div class="row">
									<?php foreach ($frequencias as $freq): 
										$pct = $freq['taxa_frequencia'];
										$bar_color = 'bg-success';
										if ($pct < 50) {
											$bar_color = 'bg-danger';
										} elseif ($pct < 75) {
											$bar_color = 'bg-warning';
										}
									?>
										<div class="col-md-6 mb-3">
											<div class="p-3 border rounded-lg bg-light h-100 shadow-sm transition-hover">
												<h6 class="font-weight-bold text-dark mb-3 text-capitalize"><?php echo htmlspecialchars($freq['modalidade']); ?></h6>
												
												<div class="d-flex align-items-center mb-3">
													<div class="progress flex-grow-1 mr-3" style="height: 10px; border-radius: 5px;">
														<div class="progress-bar <?php echo $bar_color; ?>" role="progressbar" style="width: <?php echo $pct; ?>%; border-radius: 5px;" aria-valuenow="<?php echo $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<span class="font-weight-bold text-dark small" style="min-width: 50px; text-align: right;"><?php echo $pct; ?>%</span>
												</div>

												<div class="row text-secondary text-center" style="font-size: 0.8rem;">
													<div class="col-4 border-right">
														<span class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.6rem;">Total Aulas</span>
														<span class="h6 font-weight-bold text-dark"><?php echo $freq['total_aulas']; ?></span>
													</div>
													<div class="col-4 border-right">
														<span class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.6rem;">Presenças</span>
														<span class="h6 font-weight-bold text-success"><?php echo $freq['total_presencas']; ?></span>
													</div>
													<div class="col-4">
														<span class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.6rem;">Faltas</span>
														<span class="h6 font-weight-bold text-danger"><?php echo $freq['total_faltas']; ?></span>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					
					<div class="text-center my-5">
						<h2 class="font-weight-bold text-dark mb-0">Cursos Disponíveis</h2>
					</div>
					
					<div class="row">
						<?php
						if ($usuario["nivel"] == "aluno") {
							?>
							<div class="col-12 mb-4">
								<div class="alert alert-warning border-0 shadow-sm p-4 text-center rounded-lg" role="alert" style="border-left: 5px solid var(--primary-red) !important;">
									<h4 class="alert-heading font-weight-bold mb-3 text-dark"><i class="fa-solid fa-circle-exclamation mr-2 text-danger"></i>Acesso Restrito</h4>
									<p class="lead text-muted" style="font-size: 1.1rem;">Você é um usuário cadastrado como <strong>Aluno</strong>.</p>
									<hr class="my-3" style="opacity: 0.15;">
									<p class="mb-0 text-muted">Para acessar a lista completa de cursos, por favor, entre em contato com o administrador ou mestre para atualizar seu nível de acesso. Filiados confirmados têm acesso completo aos treinamentos.</p>
								</div>
							</div>
							<?php
							return;
						}
						
						if ($filiado["confirmacao"] == "nao") {
							?>
							<div class="col-12 mb-4">
								<div class="alert alert-warning border-0 shadow-sm p-4 text-center rounded-lg" role="alert" style="border-left: 5px solid var(--primary-red) !important;">
									<h4 class="alert-heading font-weight-bold mb-3 text-dark"><i class="fa-solid fa-clock mr-2 text-danger"></i>Filiação em Análise</h4>
									<p class="lead text-muted" style="font-size: 1.1rem;">Seu status de filiação ainda está pendente de confirmação.</p>
									<hr class="my-3" style="opacity: 0.15;">
									<p class="mb-0 text-muted">Para acessar os cursos do portal, sua filiação precisa ser validada pela diretoria. Se já enviou sua documentação, por favor aguarde ou contate o administrador.</p>
								</div>
							</div>
							<?php
							return;
						} else {
							include_once __DIR__ . "/../../models/cursoModel.php";
							include_once __DIR__ . "/../../models/imagemModel.php";
							include_once __DIR__ . "/../../models/categoriaModel.php";

							$cursos = CursoModel::buscarCursosAprovados();
							if (empty($cursos)) {
								echo '<div class="col-12 text-center my-4"><p class="text-muted">Nenhum curso disponível no momento.</p></div>';
							} else {
								foreach ($cursos as $curso) {
									$id_imagem_curso = $curso["id_imagem"];
									$imagem_curso = Imagem::procura_imagem($id_imagem_curso);

									$id_categoria = $curso["id_categoria"];
									$categoria = CategoriaModel::buscarCategoria($id_categoria);
									?>
									<div class="col-lg-4 col-md-6 mb-4">
										<div class="card h-100 border-0 shadow-sm">
											<a href="assistir_aulas.php?id=<?php echo $curso["id_curso"]; ?>">
												<img class="card-img-top" src="../../img/<?php echo $imagem_curso["nome"]; ?>" alt="<?php echo $curso["nome"]; ?>" style="height: 160px; object-fit: cover;">
											</a>
											<div class="card-body p-3">
												<div class="mb-2">
													<span class="badge badge-danger text-uppercase px-2 py-1" style="font-size: 0.7rem; background-color: var(--primary-red);"><?php echo $categoria["categoria"]; ?></span>
												</div>
												<h5 class="card-title font-weight-bold mb-2">
													<a href="assistir_aulas.php?id=<?php echo $curso["id_curso"]; ?>" class="text-dark text-decoration-none"><?php echo $curso["nome"]; ?></a>
												</h5>
												<p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
													<?php echo $curso["descricao"]; ?>
												</p>
												
												<div class="border-top pt-2">
													<p class="card-text text-secondary mb-1" style="font-size: 0.8rem;">
														<strong>Mestre:</strong> <?php echo $curso["professor"]; ?>
													</p>
													<p class="card-text text-muted" style="font-size: 0.75rem;">
														<small>Criado em: <?php echo date('d/m/Y', strtotime($curso["dataCriacao"])); ?></small>
													</p>
												</div>
											</div>
											<div class="card-footer bg-white border-0 pt-0 pb-3 px-3">
												<a href="assistir_aulas.php?id=<?php echo $curso["id_curso"]; ?>" class="btn btn-danger btn-sm btn-block rounded-pill font-weight-bold shadow-sm">Treinar Agora</a>
											</div>
										</div>
									</div>
									<?php 
								}
							}
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include __DIR__ . "/rodape.php"; ?>