<?php
include_once "menu.php";
include_once __DIR__ . "/../../db/conexao.php";
include_once __DIR__ . "/../../models/filiadoModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$c = new Conexao();
$conexao = $c->conectar();

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {

	// Busca o filiado pelo ID
	$id_filiado = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$filiadoRepositorio = new FiliadoModel();
	$filiado = $filiadoRepositorio->buscarFiliadoPorId($id_filiado);

	if (!$filiado) {
		echo "<h3>Filiado não encontrado!</h3>";
		include "rodape.php";
		exit;
	}
?>
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
				<div class="card card-signin my-5">
					<div class="card-body">
						<h5 class="card-title text-center">Editar Filiado</h5>
						<form class="form-signin" action="/controllers/filiadoController.php" method="post">
							<input type="hidden" name="tipo" value="editar">
							<input type="hidden" name="id_filiado" value="<?php echo $filiado->getIdFiliado(); ?>">

							<div class="form-group">
								<label for="nome">Nome: </label>
								<input id="nome" type="text" class="form-control" name="nome" required autofocus value="<?php echo htmlspecialchars($filiado->getNome()); ?>">
							</div>

							<div class="form-group">
								<label for="codigo">Código: </label>
								<input id="codigo" type="text" class="form-control" name="codigo" required value="<?= htmlspecialchars($filiado->getCodigo()); ?>">
							</div>

							<div class="form-group">
								<label for="id_graduacao">Graduação: </label>
								<select id="id_graduacao" class="form-select form-control" name="id_graduacao" required>
									<?php
									$graduacaoRepositorio = new Graduacao();
									$graduacoes = $graduacaoRepositorio->listarGraduacoes();
									foreach ($graduacoes as $graduacao) {
										$selected = $graduacao['id_graduacao'] == $filiado->getIdGraduacao() ? 'selected' : '';
										echo '<option value="' . $graduacao['id_graduacao'] . '" ' . $selected . '>' . htmlspecialchars($graduacao['graduacao']) . '</option>';
									}
									?>
								</select>
							</div>

							<div class="form-group">
								<label for="dojo">Dojo: </label>
								<input id="dojo" type="text" class="form-control" name="dojo" required value="<?php echo htmlspecialchars($filiado->getDojo()); ?>">
							</div>

							<div class="form-group">
								<label for="telefone">Telefone: </label>
								<input id="telefone" type="text" class="form-control" name="telefone" required value="<?php echo htmlspecialchars($filiado->getTelefone()); ?>">
							</div>



							<div class="form-group">
								<label for="data_nascimento">Data de Nascimento: </label>
								<input id="data_nascimento" type="date" class="form-control" name="data_nascimento" value="<?php echo htmlspecialchars($filiado->getDataNascimento()); ?>" required>
							</div>

							<div class="form-group">
								<label for="email">Email: </label>
								<input id="email" type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($filiado->getEmail()); ?>">
							</div>

							<div class="form-group">
								<label for="endereco">Endereço: </label>
								<input id="endereco" type="text" class="form-control" name="endereco" required value="<?php echo htmlspecialchars($filiado->getEndereco()); ?>">
							</div>

							<div class="form-group">
								<label for="cidade">Cidade: </label>
								<input id="cidade" type="text" class="form-control" name="cidade" required value="<?php echo htmlspecialchars($filiado->getCidade()); ?>">
							</div>

							<div class="form-group">
								<label for="id_estado">Estado: </label>
								<select id="id_estado" class="form-select form-control" name="id_estado" required>
									<?php
									$consulta = "SELECT id_estado, estado FROM estados";
									$resultado = mysqli_query($conexao, $consulta);
									while ($dado = mysqli_fetch_array($resultado)) {
										$selected = $dado["id_estado"] == $filiado->getIdEstado() ? 'selected' : '';
										echo '<option value="' . $dado["id_estado"] . '" ' . $selected . '>' . $dado["estado"] . '</option>';
									}
									?>
								</select>
							</div>

							<div class="form-group">
								<label for="confirmacao">Confirmado?</label>
								<select id="confirmacao" class="form-select form-control" name="confirmacao" required>
									<option value="sim" <?php echo $filiado->getConfirmacao() == 'sim' ? 'selected' : ''; ?>>Sim</option>
									<option value="nao" <?php echo $filiado->getConfirmacao() == 'nao' ? 'selected' : ''; ?>>Não</option>
								</select>
							</div>

							<input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	include "rodape.php";
} else {
	echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>