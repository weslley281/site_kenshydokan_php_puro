<?php
// views/perfil/meus_certificados.php
$page_title = "Meus Certificados";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../models/certificadoModel.php";
include_once __DIR__ . "/../../models/certificadoManualModel.php";
include_once __DIR__ . "/../../models/certificadoUploadModel.php";
include_once __DIR__ . "/../../models/cursoModel.php";

$certificados = [];
if (isset($_SESSION['id_usuario'])) {
    // Retroactive check: verify and generate certificates for any completed courses
    try {
        include_once __DIR__ . "/../../models/aulaModel.php";
        $aulaModelTmp = new AulaModel();
        $aulas_assistidas = $aulaModelTmp->getAulasAssistidasPorUsuario($_SESSION['id_usuario']);
        $cursos_ids = [];
        foreach ($aulas_assistidas as $aula_id) {
            $aula_detail = $aulaModelTmp->buscarAula($aula_id);
            if ($aula_detail && isset($aula_detail['id_curso'])) {
                $cursos_ids[] = (int)$aula_detail['id_curso'];
            }
        }
        $cursos_ids = array_unique($cursos_ids);
        foreach ($cursos_ids as $id_curso_auto) {
            CertificadoModel::verificarEGerarCertificadoAuto($_SESSION['id_usuario'], $id_curso_auto);
        }
    } catch (Exception $e) {
        error_log("Erro no check retroativo de certificados: " . $e->getMessage());
    }

    $certificadoModelRepo = new CertificadoModel();
    $certificados = $certificadoModelRepo->buscarCertificadosPorUsuario($_SESSION['id_usuario']);
}

$certificados_manuais = [];
if (!empty($filiado['id_filiado'])) {
    $manualModel = new CertificadoManualModel();
    $certificados_manuais = $manualModel->listarPorFiliado($filiado['id_filiado']);
}

$certificados_upload = [];
if (!empty($filiado['id_filiado'])) {
    $uploadModel = new CertificadoUploadModel();
    $certificados_upload = $uploadModel->listarPorFiliado($filiado['id_filiado']);
}

$cursoModelRepo = new CursoModel();
?>

<body>
	<div class="container mt-5">
		<div class="row">
			<?php include __DIR__ . "/_perfil_menu.php"; ?>
			<div class="col-lg-9 mb-4">
				<?php include __DIR__ . "/_perfil_info_card.php"; ?>

				<hr>
				<div class="text-center">
					<h1 class="my-4"><strong>Meus Certificados</strong></h1>
				</div>
				<hr>

				<div class="row">
					<?php if (empty($certificados) && empty($certificados_manuais) && empty($certificados_upload)) : ?>
						<div class="col-12 text-center py-4">
							<div class="alert alert-light border shadow-sm p-4 rounded-lg">
								<h5 class="text-secondary mb-0"><i class="fa-solid fa-folder-open mr-2"></i>Você ainda não possui certificados emitidos.</h5>
							</div>
						</div>
					<?php else : ?>
						<!-- Certificados de Cursos Online -->
						<div class="col-12 mb-5">
							<h5 class="font-weight-bold text-danger mb-3 border-bottom pb-2">
								<i class="fa-solid fa-graduation-cap mr-2"></i>Certificados de Cursos Online
							</h5>
							<?php if (empty($certificados)): ?>
								<p class="text-muted small pl-1">Nenhum certificado de curso online concluído.</p>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-hover bg-white border rounded shadow-sm">
										<thead>
											<tr class="bg-light text-secondary small font-weight-bold">
												<th>Nome do Curso</th>
												<th>Data de Emissão</th>
												<th style="width: 200px;">Ações</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($certificados as $certificado) : ?>
												<?php $curso = $cursoModelRepo->buscarCurso($certificado['id_curso']); ?>
												<tr>
													<td class="align-middle font-weight-bold text-dark"><?php echo htmlspecialchars($curso['nome'] ?? 'Curso Desconhecido'); ?></td>
													<td class="align-middle text-secondary small"><?php echo date('d/m/Y', strtotime($certificado['data_emissao'])); ?></td>
													<td class="align-middle">
														<a href="<?php echo htmlspecialchars("../" . $certificado['caminho_arquivo']); ?>" class="btn btn-sm btn-outline-success font-weight-bold px-3 rounded-pill" download>Baixar</a>
														<a href="../../controllers/verificar_certificado.php?codigo=<?php echo htmlspecialchars($certificado['codigo_verificacao']); ?>" class="btn btn-sm btn-outline-info font-weight-bold px-3 rounded-pill" target="_blank">Verificar</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>

						<!-- Certificados Oficiais / Manuais -->
						<div class="col-12">
							<h5 class="font-weight-bold text-danger mb-3 border-bottom pb-2">
								<i class="fa-solid fa-certificate mr-2"></i>Certificados e Outorgas Oficiais do Dojô
							</h5>
							<?php if (empty($certificados_manuais)): ?>
								<p class="text-muted small pl-1">Nenhum certificado oficial emitido pela diretoria.</p>
							<?php else: ?>
								<div class="table-responsive">
									<table class="table table-hover bg-white border rounded shadow-sm">
										<thead>
											<tr class="bg-light text-secondary small font-weight-bold">
												<th>Título / Outorga</th>
												<th>Dojô Referência</th>
												<th>Data de Emissão</th>
												<th style="width: 200px;" class="text-center">Ações</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($certificados_manuais as $cert) : ?>
												<tr>
													<td class="align-middle font-weight-bold text-dark"><?php echo htmlspecialchars($cert['titulo'] ?? 'Certificado Oficial'); ?></td>
													<td class="align-middle text-secondary small text-capitalize"><?php echo htmlspecialchars($cert['dojo'] ?? 'Dojo Principal'); ?></td>
													<td class="align-middle text-secondary small"><?php echo date('d/m/Y', strtotime($cert['data_emissao'])); ?></td>
													<td class="align-middle text-center">
														<a href="../../controllers/gerar_certificado_manual.php?id=<?php echo $cert['id']; ?>" class="btn btn-sm btn-danger font-weight-bold px-4 rounded-pill shadow-sm" target="_blank">
															<i class="fa-solid fa-file-pdf mr-1"></i>Visualizar / Baixar
														</a>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>

						<!-- Certificados em Imagem -->
						<div class="col-12 mt-5">
							<h5 class="font-weight-bold text-danger mb-3 border-bottom pb-2">
								<i class="fa-solid fa-file-image mr-2"></i>Certificados em Imagem
							</h5>
							<?php if (empty($certificados_upload)): ?>
								<p class="text-muted small pl-1">Nenhum certificado em imagem enviado pela administração.</p>
							<?php else: ?>
								<div class="row">
									<?php foreach ($certificados_upload as $cert) : ?>
										<div class="col-md-4 mb-4">
											<div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
												<div style="height: 180px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #dee2e6; position: relative;">
													<img src="../../img/certificados/<?php echo htmlspecialchars($cert['imagem']); ?>" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;" alt="<?php echo htmlspecialchars($cert['titulo']); ?>">
												</div>
												<div class="card-body p-3 d-flex flex-column justify-content-between">
													<div>
														<h6 class="font-weight-bold text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($cert['titulo']); ?>"><?php echo htmlspecialchars($cert['titulo']); ?></h6>
														<small class="text-muted d-block mb-3">Enviado em: <?php echo date('d/m/Y', strtotime($cert['data_upload'])); ?></small>
													</div>
													<div class="d-flex justify-content-between">
														<a href="../../img/certificados/<?php echo htmlspecialchars($cert['imagem']); ?>" target="_blank" class="btn btn-sm btn-outline-danger font-weight-bold px-3 rounded-pill">
															<i class="fa-solid fa-eye mr-1"></i>Ver
														</a>
														<a href="../../img/certificados/<?php echo htmlspecialchars($cert['imagem']); ?>" download="<?php echo htmlspecialchars($cert['titulo'] . '.' . pathinfo($cert['imagem'], PATHINFO_EXTENSION)); ?>" class="btn btn-sm btn-danger font-weight-bold px-3 rounded-pill shadow-sm">
															<i class="fa-solid fa-download mr-1"></i>Baixar
														</a>
													</div>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

<?php include __DIR__ . "/rodape.php"; ?>
