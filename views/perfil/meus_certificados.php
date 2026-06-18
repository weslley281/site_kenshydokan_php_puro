<?php
$page_title = "Meus Certificados";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../models/certificadoModel.php";
include_once __DIR__ . "/../../models/cursoModel.php";

$certificados = [];
if (isset($_SESSION['id_usuario'])) {
    $certificadoModelRepo = new CertificadoModel();
    $certificados = $certificadoModelRepo->buscarCertificadosPorUsuario($_SESSION['id_usuario']);
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
					<?php if (empty($certificados)) : ?>
						<div class="col-12 text-center">
							<h3>Você ainda não possui certificados.</h3>
						</div>
					<?php else : ?>
						<div class="col-12">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Curso</th>
										<th>Data de Emissão</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($certificados as $certificado) : ?>
										<?php $curso = $cursoModelRepo->buscarCurso($certificado['id_curso']); ?>
										<tr>
											<td><?php echo htmlspecialchars($curso['nome'] ?? 'Curso Desconhecido'); ?></td>
											<td><?php echo date('d/m/Y', strtotime($certificado['data_emissao'])); ?></td>
											<td>
												<a href="<?php echo htmlspecialchars("../" . $certificado['caminho_arquivo']); ?>" class="btn btn-sm btn-success" download>Baixar</a>
												<a href="../../controllers/verificar_certificado.php?codigo=<?php echo htmlspecialchars($certificado['codigo_verificacao']); ?>" class="btn btn-sm btn-info" target="_blank">Verificar</a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

<?php include __DIR__ . "/rodape.php"; ?>
