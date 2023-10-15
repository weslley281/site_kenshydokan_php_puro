<!-- Navigation -->
<?php include "menu.php";?>
<!-- /Navigation -->
<?php
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();
$id_usuario = $_GET['id_usuario'];

$busca_usuario = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

$id_imagem = $usuario["id_imagem"];
$busca_imagem = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
$resultado_imagem = mysqli_query($conexao, $busca_imagem);
$imagem = mysqli_fetch_array($resultado_imagem);

$id_filiado = $usuario["id_fil"];
$busca_filiado = "SELECT * FROM filiados WHERE id_filiado = '$id_filiado'";
$resultado_filiado = mysqli_query($conexao, $busca_filiado);
$filiado = mysqli_fetch_array($resultado_filiado);
$confirmacao = $filiado["confirmacao"];
if ($confirmacao == "sim") {
    $ativo = "Ele está filiado";
} else {
    $ativo = "Aguardando Cofirmação de Filiação, Não Está Filiado Não";
}

$id_graduacao = $filiado["id_graduacao"];
$busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
$resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
$graduacao = mysqli_fetch_array($resultado_graduacao);
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
									<img class="card-img-top" src="../imagens/<?php echo $imagem["nome"] ?>" alt="">
									<div class="card-body">
										<h5 class="card-title"><?php echo $usuario["nome"]; ?></h5>
									</div>
								</div>
							</div>
							<!-- mais informações -->
							<?php
$busca = "SELECT * FROM filiados where id_filiado = '$id_filiado'";
$resultado = mysqli_query($conexao, $busca);
while ($res = mysqli_fetch_array($resultado)) {
    if ($id_filiado == 23) {
        $graduacao = ":<br>Faixa preta 1° dan em Karatê Kenshydokan<br>
                                Faixa preta 1° dan em Judô Kodokan<br>
                                Faixa preta em Jiu Jitsu Brasileiro<br>
                                Faixa roxa 2° kyu em Ju jitsu";
    } elseif ($id_filiado == 14) {
        $graduacao = ":<br>Faixa coral 10° dan em Karatê Kenshydokan<br>
                                Faixa coral 6° dan em Judô Kodokan<br>
                                Faixa coral 7° dan em Ju jitsu";
    } elseif ($id_filiado == 21) {
        $graduacao = ":<br>Faixa preta 1° dan em Karatê Kenshydokan<br>
                                Faixa preta 1° dan em Judô Kodokan";
    } else {
        $graduacao = $graduacao["graduacao"];
    }
    ?>
								<div class="col mb-4">
									<div class="card">
										<h5 class="card-header"><?php echo "$ativo"; ?></h5>
										<div class="card-body">
											<h5 class="card-title">Sua graduação é <?php echo $graduacao; ?></h5>
											<p class="card-text">Dojo: <?php echo $filiado["dojo"]; ?></p>
											<p class="card-text">E-mail: <?php echo $usuario["email"]; ?></p>
											<p class="card-text">Telefone: <?php echo $usuario["telefone"]; ?></p>
										</div>
									</div>
								</div>
							<?php }?>
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

<?php include "rodape.php";?>
