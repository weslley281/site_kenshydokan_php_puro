<?php
include_once "menu.php";
include_once __DIR__ . "/../../models/filiadoModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";
include_once __DIR__ . "/../../models/estadoModel.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {

	// Busca o filiado pelo ID
	$id_filiado = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$filiadoRepositorio = new FiliadoModel();
	$filiado = $filiadoRepositorio->buscarFiliadoPorId($id_filiado);

	if (!$filiado) {
		echo "<div class='container py-5'><div class='alert alert-danger'>Filiado não encontrado!</div></div>";
		include "rodape.php";
		exit;
	}
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Editar Filiado</h2>
      <a href="index.php?pagina=filiados" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Filiados
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <form enctype="multipart/form-data" action="../../controllers/filiadoController.php" method="post">
          <input type="hidden" name="tipo" value="editar">
          <input type="hidden" name="id_filiado" value="<?php echo $filiado->getIdFiliado(); ?>">

          <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-user-pen mr-2"></i>Informações Pessoais e Graduação</h5>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome Completo</label>
              <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" value="<?php echo htmlspecialchars($filiado->getNome()); ?>" required autofocus>
            </div>
            <div class="form-group col-md-6">
              <label for="email" class="text-secondary small font-weight-bold text-uppercase">E-mail</label>
              <input id="email" type="email" class="form-control form-control-lg bg-light border-0 shadow-sm" name="email" value="<?php echo htmlspecialchars($filiado->getEmail()); ?>" placeholder="contato@filiado.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="codigo" class="text-secondary small font-weight-bold text-uppercase">Código</label>
              <input id="codigo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="codigo" value="<?php echo htmlspecialchars($filiado->getCodigo()); ?>" required>
            </div>
            <div class="form-group col-md-3">
              <label for="data_nascimento" class="text-secondary small font-weight-bold text-uppercase">Data de Nascimento</label>
              <input id="data_nascimento" type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" name="data_nascimento" value="<?php echo htmlspecialchars($filiado->getDataNascimento()); ?>" required>
            </div>
            <div class="form-group col-md-3">
              <label for="id_graduacao" class="text-secondary small font-weight-bold text-uppercase">Graduação</label>
              <select id="id_graduacao" class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" name="id_graduacao">
                <?php
                $graduacaoRepositorio = new Graduacao();
                $graduacoes = $graduacaoRepositorio->listarGraduacoes();
                foreach ($graduacoes as $graduacao) {
                  $selected = $graduacao['id_graduacao'] == $filiado->getIdGraduacao() ? 'selected' : '';
                  echo '<option value="' . $graduacao["id_graduacao"] . '" ' . $selected . '>' . htmlspecialchars($graduacao["graduacao"]) . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="dojo" class="text-secondary small font-weight-bold text-uppercase">Dojo</label>
              <input id="dojo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="dojo" value="<?php echo htmlspecialchars($filiado->getDojo()); ?>" required>
            </div>
          </div>

          <hr class="my-4">

          <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-map-location-dot mr-2"></i>Contato e Endereço</h5>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone / Celular</label>
              <input id="telefone" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="telefone" value="<?php echo htmlspecialchars($filiado->getTelefone()); ?>" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
            </div>
            <div class="form-group col-md-6">
              <label for="endereco" class="text-secondary small font-weight-bold text-uppercase">Endereço</label>
              <input id="endereco" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="endereco" value="<?php echo htmlspecialchars($filiado->getEndereco()); ?>" placeholder="Rua, Número, Bairro" required>
            </div>
            <div class="form-group col-md-3">
              <label for="cidade" class="text-secondary small font-weight-bold text-uppercase">Cidade</label>
              <input id="cidade" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="cidade" value="<?php echo htmlspecialchars($filiado->getCidade()); ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="id_estado" class="text-secondary small font-weight-bold text-uppercase">Estado</label>
              <select id="id_estado" class="form-control form-control-lg bg-light border-0 shadow-sm" name="id_estado" required>
                <?php
                $estadoModelRepo = new EstadoModel();
                $estados = $estadoModelRepo->listarTodos();
                foreach ($estados as $dado) {
                  $selected = $dado["id_estado"] == $filiado->getIdEstado() ? 'selected' : '';
                  echo '<option value="' . $dado["id_estado"] . '" ' . $selected . '>' . htmlspecialchars($dado["estado"]) . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="confirmacao" class="text-secondary small font-weight-bold text-uppercase">Confirmado?</label>
              <select id="confirmacao" class="form-control form-control-lg bg-light border-0 shadow-sm" name="confirmacao" required>
                <option value="sim" <?php echo $filiado->getConfirmacao() == 'sim' ? 'selected' : ''; ?>>Sim</option>
                <option value="nao" <?php echo $filiado->getConfirmacao() == 'nao' ? 'selected' : ''; ?>>Não</option>
              </select>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar Alterações
            </button>
            <a href="index.php?pagina=filiados" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>