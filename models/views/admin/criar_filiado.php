<?php
include_once "menu.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";
include_once __DIR__ . "/../../models/estadoModel.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    $graduacaoRepositorio = new Graduacao();
    $graduacoes = $graduacaoRepositorio->listarGraduacoes();
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Cadastrar Novo Filiado</h2>
      <a href="index.php?pagina=filiados" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Filiados
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <form enctype="multipart/form-data" action="../../controllers/filiadoController.php" method="post">
          <input type="hidden" name="tipo" value="inserir">

          <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-user-plus mr-2"></i>Informações Pessoais e Graduação</h5>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome Completo</label>
              <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" placeholder="Digite o nome completo" required autofocus>
            </div>
            <div class="form-group col-md-6">
              <label for="email" class="text-secondary small font-weight-bold text-uppercase">E-mail</label>
              <input id="email" type="email" class="form-control form-control-lg bg-light border-0 shadow-sm" name="email" value="naosei@gmail.com" placeholder="contato@filiado.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="codigo" class="text-secondary small font-weight-bold text-uppercase">Código</label>
              <input id="codigo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="codigo" value="12345" required>
            </div>
            <div class="form-group col-md-3">
              <label for="data_nascimento" class="text-secondary small font-weight-bold text-uppercase">Data de Nascimento</label>
              <input id="data_nascimento" type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" name="data_nascimento" required>
            </div>
            <div class="form-group col-md-3">
              <label for="id_graduacao" class="text-secondary small font-weight-bold text-uppercase">Graduação</label>
              <select id="id_graduacao" class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" name="id_graduacao">
                <?php
                foreach ($graduacoes as $graduacao) {
                  echo '<option value="' . $graduacao["id_graduacao"] . '">' . htmlspecialchars($graduacao["graduacao"]) . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="dojo" class="text-secondary small font-weight-bold text-uppercase">Dojo</label>
              <input id="dojo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="dojo" value="Kenshydokan" required>
            </div>
          </div>

          <hr class="my-4">

          <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-map-location-dot mr-2"></i>Contato e Endereço</h5>

          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone / Celular</label>
              <input id="telefone" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="telefone" value="123456" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
            </div>
            <div class="form-group col-md-6">
              <label for="endereco" class="text-secondary small font-weight-bold text-uppercase">Endereço</label>
              <input id="endereco" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="a" name="endereco" placeholder="Rua, Número, Bairro">
            </div>
            <div class="form-group col-md-3">
              <label for="cidade" class="text-secondary small font-weight-bold text-uppercase">Cidade</label>
              <input id="cidade" type="text" value="Várzea Grande" class="form-control form-control-lg bg-light border-0 shadow-sm" name="cidade">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="id_estado" class="text-secondary small font-weight-bold text-uppercase">Estado</label>
              <select id="id_estado" class="form-control form-control-lg bg-light border-0 shadow-sm" name="id_estado">
                <?php
                $estadoModel = new EstadoModel();
                $estados = $estadoModel->listarTodos();
                if (!empty($estados)) {
                  foreach ($estados as $dado) {
                    echo '<option value="' . $dado["id_estado"] . '">' . htmlspecialchars($dado["estado"]) . '</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="confirmacao" class="text-secondary small font-weight-bold text-uppercase">Status de Confirmação</label>
              <input id="confirmacao" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="confirmacao" value="sim" readonly required>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar Filiado
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