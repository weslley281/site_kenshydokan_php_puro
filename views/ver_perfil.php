<?php
include "menu.php";
include_once "../models/usuarioModel.php";
include_once "../models/imagemModel.php";
include_once "../models/filiadoModel.php";
include_once "../models/graduacaoModel.php";

$id_usuario = $_GET['id_usuario'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

$imagem = Imagem::procura_imagem($usuario["id_imagem"]);

$id_filiado = $usuario["id_fil"];
$filiadoModelRepo = new FiliadoModel();
$filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);

if ($filiado_obj) {
    $filiado = [
        'confirmacao' => $filiado_obj->getConfirmacao(),
        'dojo' => $filiado_obj->getDojo()
    ];
    $id_graduacao = $filiado_obj->getIdGraduacao();
} else {
    $filiado = ['confirmacao' => 'nao', 'dojo' => 'Não informado'];
    $id_graduacao = 0;
}

$confirmacao = $filiado["confirmacao"];
if ($confirmacao == "sim") {
	$ativo = "Ele está filiado";
} else {
	$ativo = "Aguardando Cofirmação de Filiação, Não Está Filiado Não";
}

$graduacao = Graduacao::buscarGraduacao($id_graduacao);
?>

<body>
	<div class="container mt-5">
		<!-- Page Content -->
		<div class="container">

			<div class="row">

				<div class="col-lg-3">

					<h1 class="my-4">Meu Perfil</h1>
					<div class="list-group">
					</div>

				</div>
				<!-- /.col-lg-3 -->
				<!--dados do perfil -->
				<div class="col-lg-9">
					<div id="esconder" class="text-center mt-3 mb-3">
						<div class="row">
							<!-- card do perfil -->
							<div class="col-5 mb-4">
								<div class="card" style="width: 18rem;">
									<img class="card-img-top" src="<?php echo $imagem["caminho"] ?>" alt="<?php echo $usuario["nome"]; ?>">
									<div class="card-body">
										<h5 class="card-title"><?php echo $usuario["nome"]; ?></h5>
									</div>
								</div>
							</div>
							<!-- mais informações -->
							<?php
							if ($filiado_obj) {
								if ($id_filiado == 23) {
									$graduacao_display = ':<br><ul class="list-group mt-1"><li class="list-group-item">Faixa preta 3° dan em Karatê Kenshydokan</li><li class="list-group-item">Faixa preta 2° dan em Judô Kodokan</li><li class="list-group-item">Faixa preta em Jiu Jitsu Brasileiro</li></ul>';
								} elseif ($id_filiado == 14) {
									$graduacao_display = ':<br><ul class="list-group mt-1"><li class="list-group-item">10° Dan Karate Kenshydokan</li><li class="list-group-item">7° Dan Ju jitsu</li><li class="list-group-item">7° Dan em KickBoxing</li><li class="list-group-item">6° Dan Judo Kodokan</li><li class="list-group-item">5° Dan em Karate Kyokushin</li><li class="list-group-item">Faixa Preta Quinto Grau Brasilian Jiu Jitsu</li></ul>';
								} elseif ($id_filiado == 79) {
									$graduacao_display = ':<br><ul class="list-group mt-1"><li class="list-group-item">14° Khan Muay Thai</li><li class="list-group-item">3° Dan Kickboxing</li><li class="list-group-item">2° Dan Karatê</li><li class="list-group-item">Faixa Roxa Jiu Jitsu Brasileiro</li></ul>';
								} else {
									$graduacao_display = $graduacao["graduacao"] ?? 'Sem registro';
								}
							?>
								<div class="col mb-4">
									<div class="card">
										<h5 class="card-header"><?php echo "$ativo"; ?></h5>
										<div class="card-body">
											<h3 class="card-title">Sua graduação é <?php echo $graduacao_display; ?></h3>
											<p class="card-text">Dojo: <?php echo $filiado["dojo"]; ?></p>
											<p class="card-text">E-mail: <?php echo $usuario["email"]; ?></p>
											<p class="card-text">Telefone: <?php echo $usuario["telefone"]; ?></p>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<!--Fim dados do perfil -->
					<hr>
					<div class="row">


					</div>
					<!-- /.row -->

				</div>
				<!-- /.col-lg-9 -->

			</div>
			<!-- /.row -->

		</div>
		<!-- /.container -->
	</div>

	<?php include "rodape.php"; ?>