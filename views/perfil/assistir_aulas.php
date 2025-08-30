<?php
$page_title = "Criar Postagens";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
include_once __DIR__ . "/../../repositorios/AulaRepositorio.php";
include_once __DIR__ . "/../../repositorios/CursoRepositorio.php";

$aulaRepositorio = new AulaRepositorio();
$aulas_assistidas_ids = [];
if (isset($_SESSION['id_usuario'])) {
	$aulas_assistidas_ids = $aulaRepositorio->getAulasAssistidasPorUsuario($_SESSION['id_usuario']);
}
?>

<body>
	<div class="container mt-5">
		<div class="row">
			<?php include __DIR__ . "/_perfil_menu.php"; ?>
			<div class="col-lg-9 mb-4">
				<?php include __DIR__ . "/_perfil_info_card.php"; ?>

				<hr>
				<div class="text-center">
					<h1 class="my-4"><strong>Aulas</strong></h1>
				</div>
				<hr>
				<?php
				$id_curso = @$_GET["id"];
				$busca_curso = "SELECT * FROM cursos where id_curso = '$id_curso'";
				$resultado_curso = mysqli_query($conexao, $busca_curso);
				$curso = mysqli_fetch_array($resultado_curso);
				?>
				<?php
				$total_aulas = $aulaRepositorio->getTotalAulasPorCurso($id_curso);
				$aulas_assistidas_no_curso = 0;

				// Filter watched lessons to only include those from the current course
				foreach ($aulas_assistidas_ids as $aula_id) {
					$aula_detail = $aulaRepositorio->buscarAula($aula_id);
					if ($aula_detail && $aula_detail['id_curso'] == $id_curso) {
						$aulas_assistidas_no_curso++;
					}
				}

				$percentual_conclusao = ($total_aulas > 0) ? ($aulas_assistidas_no_curso / $total_aulas) * 100 : 0;
				$percentual_necessario = $curso['percentual_conclusao_certificado'] ?? 100; // Assuming column exists

				$pode_emitir_certificado = ($percentual_conclusao >= $percentual_necessario);
				?>
				<div class="text-center">
					<h1>
						<strong>Curso de <?php echo $curso["nome"] ?></strong>
						<?php if ($pode_emitir_certificado && $curso['temCertificado'] == 'sim') : ?>
							<a href="../../controllers/gerar_certificado.php?id_curso=<?php echo $id_curso; ?>" class="btn btn-primary ml-3">Emitir Certificado</a>
						<?php endif; ?>
					</h1>
					<p>Progresso: <?php echo round($percentual_conclusao, 2); ?>% (Necessário: <?php echo $percentual_necessario; ?>%)</p>
				</div>
				<div class="row">
					<table class="table">
						<thead>
							<tr>
								<th>AULAS</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$busca = "SELECT * FROM aulas WHERE id_curso = $id_curso";
							$resultado = mysqli_query($conexao, $busca);
							$linha = mysqli_num_rows($resultado);

							if ($linha == 0) {
								echo "<h3> Não há aulas cadastradas nesse curso!! </h3>";
							} else {
								while ($aula = mysqli_fetch_array($resultado)) {
							?>
									<tr>
										<th scope="col">
											<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#aulaModal<?php echo $aula["id_aula"]; ?>">
												<?php echo $aula["titulo"]; ?>
												<?php if (in_array($aula['id_aula'], $aulas_assistidas_ids)) : ?>
													<span class="text-success ml-2" title="Aula Assistida">&#10003;</span> <!-- Unicode checkmark -->
												<?php endif; ?>
											</button>
										</th>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>


				</div>
			</div>
			<!-- /.col-lg-9 -->

		</div>
		<!-- /.row -->

	</div>
	<!-- /.container -->
	</div>
	<script src="https://vjs.zencdn.net/7.8.4/video.js"></script>
	<?php
	$busca = "SELECT * FROM aulas WHERE id_curso = $id_curso";
	$resultado = mysqli_query($conexao, $busca);
	$linha = mysqli_num_rows($resultado);

	if ($linha > 0) {
		while ($aula = mysqli_fetch_array($resultado)) {
	?>
			<!-- Modal para aula -->
			<div class="modal fade" id="aulaModal<?php echo $aula["id_aula"]; ?>" tabindex="-1" role="dialog" aria-labelledby="aulaModalLabel<?php echo $aula["id_aula"]; ?>" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="aulaModalLabel<?php echo $aula["id_aula"]; ?>"><?php echo $aula["titulo"]; ?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<iframe src="<?php echo $aula["link"]; ?>" width="100%" height="500" frameborder="0"></iframe>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
							<button type="button" class="btn btn-success marcar-assistido" data-id-aula="<?php echo $aula['id_aula']; ?>">Marcar como Assistido</button>
						</div>
					</div>
				</div>
			</div>
	<?php
		}
	}
	?>
	<script src="https://vjs.zencdn.net/7.8.4/video.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const userId = <?php echo json_encode($_SESSION['id_usuario'] ?? null); ?>; // Get user ID from session
			const watchedLessonIds = <?php echo json_encode($aulas_assistidas_ids); ?>; // Get watched lesson IDs from PHP

			// Function to mark an aula as watched
			async function markAulaAsWatched(aulaId, button) {
				if (!userId) {
					alert('Você precisa estar logado para marcar aulas como assistidas.');
					return;
				}
				try {
					const response = await fetch('../../controllers/marcar_aula_assistida.php', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: `id_aula=${aulaId}` // id_usuario is sent from session in controller
					});
					const data = await response.json();

					if (data.success) {
						button.textContent = 'Assistido';
						button.classList.remove('btn-success');
						button.classList.add('btn-secondary');
						button.disabled = true;
						alert(data.message);
					} else {
						alert(data.message);
					}
				} catch (error) {
					console.error('Error marking aula as watched:', error);
					alert('Erro ao marcar aula como assistida.');
				}
			}

			// Initialize buttons on page load
			document.querySelectorAll('.marcar-assistido').forEach(button => {
				const aulaId = parseInt(button.dataset.idAula);
				if (watchedLessonIds.includes(aulaId)) {
					button.textContent = 'Assistido';
					button.classList.remove('btn-success');
					button.classList.add('btn-secondary');
					button.disabled = true;
				}

				// Attach event listener
				button.addEventListener('click', function() {
					markAulaAsWatched(aulaId, this);
				});
			});
		});
	</script>
	<?php
	include __DIR__ . "/../rodape.php";
	?>