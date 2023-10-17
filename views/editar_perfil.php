<!-- Navigation -->
<?php include "menu.php";?>
<!-- /Navigation -->
<?php
include_once "../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();

$id_usuario = $_SESSION['id_usuario'];

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
    $ativo = "Você está filiado";
} else {
    $ativo = "Aguardando Cofirmação de Filiação, Não Está Filiado Não";
}

$id_graduacao = $filiado["id_graduacao"];
$busca_graduacao = "SELECT * FROM graduacao WHERE id_graduacao = '$id_graduacao'";
$resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
$graduacao = mysqli_fetch_array($resultado_graduacao);

if ($filiado != "") {
    ?>

	<body>
		<div class="container mt-5">
			<!-- Page Content -->
			<div class="container">

				<div class="row">

					<div class="col-lg-3">

						<h1 class="my-4">Editar Perfil</h1>
						<div class="list-group">
							<a href="perfil.php" class="list-group-item bg-light text-dark">Perfil</a>
							<a href="editar_perfil.php" class="list-group-item bg-danger text-dark">Editar Perfil</a>
<?php if ($usuario["nivel"] == "admin" || $usuario["nivel"] == "sensei") {?>
								<a href="exame_graduacao.php" class="list-group-item bg-light text-dark">Exame de Graduação</a>
								<a href="criar_postagem.php" class="list-group-item bg-light text-dark">Criar Postagem</a>
								<a href="postagens.php" class="list-group-item bg-light text-dark">Suas Postagens</a>
								<a href="documentos.php" class="list-group-item bg-light text-dark">Arquivos para Baixar</a>
<?php }?>
							<a href="eventos.php" class="list-group-item bg-light text-dark">Eventos Online</a>
							<a href="../controllers/sair.php" class="list-group-item bg-light text-dark">Sair</a>
						</div>

					</div>
					<!-- /.col-lg-3 -->
					<!--dados do perfil -->
					<div class="col-lg-9">
						<div class="text-center mt-3 mb-3">
							<div class="row d-inline-flex">
								<!-- card do perfil -->
								<div class="col d-inline-flex mb-4">
									<div class="card" style="width: 18rem;">
										<img class="card-img-top" src="../imagens/<?php echo $imagem["nome"] ?>" alt="">
										<div class="card-body">
											<h5 class="card-title"><?php echo $usuario["nome"]; ?></h5>
										</div>
									</div>
								</div>
								<!-- mais informações -->
								<div class="col">

									<form action="../controllers/editar_imagem.php" method="post" enctype="multipart/form-data">
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario" readonly>
										<input class="form-control mb-2" type="file" value="<?php echo $usuario["nome"]; ?>" name="foto">
										<input class="btn btn-secondary mb-2" type="submit" name="atualizar" value="atualizar foto">
									</form>
									<form action="../controllers/editar_perfil.php" method="post">
										<input class="form-control mb-2" type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" name="id_usuario" readonly>
										<input class="form-control mb-2" type="text" value="<?php echo $usuario["nome"]; ?>" name="nome" required>
										<input class="form-control mb-2" type="email" value="<?php echo $usuario["email"]; ?>" name="email" readonly>
										<input class="form-control mb-2" type="text" value="<?php echo $usuario["telefone"]; ?>" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
										<input class="btn btn-success" type="submit" value="Salvar Alterações" name="editar">
									</form>

									<div id="trocar_senha">
										<form v-if="senha" action="../controllers/editar_senha.php" method="post">
											<input type="hidden" value="<?php echo $usuario["id_usuario"] ?>" name="id_usuario">
											<input type="password" class="form-control mt-2 mb-2" placeholder="Digite a Senha Antiga" name="senha_antiga">
											<input type="password" class="form-control mb-2" placeholder="Digite a Senha a Nova Senha" name="senha1">
											<input type="password" class="form-control mb-2" placeholder="Repita a Senha a Nova Senha" name="senha2">
											<input type="submit" class="btn btn-success" value="Salvar Senha" name="">
										</form>
									</div>
								</div>
							</div>
						</div>
						<!--Fim dados do perfil -->
						<!-- /.row -->
					</div>
					<!-- /.col-lg-9 -->

				</div>
				<!-- /.row -->

			</div>
			<!-- /.container -->
		</div>

	<?php
include "rodape.php";
} else {
    header("location:login.php");
}
?>

	<!-- Scripts de Telefone -->
	<script type="text/javascript">
		function mask(o, f) {
			setTimeout(function() {
				var v = mphone(o.value);
				if (v != o.value) {
					o.value = v;
				}
			}, 1);
		}

		function mphone(v) {
			var r = v.replace(/\D/g, "");
			r = r.replace(/^0/, "");
			if (r.length > 10) {
				r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
			} else if (r.length > 5) {
				r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
			} else if (r.length > 2) {
				r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
			} else {
				r = r.replace(/^(\d*)/, "($1");
			}
			return r;
		}
	</script>