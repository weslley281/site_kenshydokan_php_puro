<?php
include_once "menu.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";
include_once __DIR__ . "/../../models/estadoModel.php";
include_once __DIR__ . "/../../models/arteModel.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    $graduacaoRepositorio = new Graduacao();
    $graduacoes = $graduacaoRepositorio->listarGraduacoes();

    $arteRepositorio = new ArteMarcial();
    $artes_marciais = $arteRepositorio->listarArtes();
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

          <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-user-plus mr-2"></i>Informações Pessoais</h5>

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
            <div class="form-group col-md-4">
              <label for="codigo" class="text-secondary small font-weight-bold text-uppercase">Código</label>
              <input id="codigo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="codigo" value="12345" required>
            </div>
            <div class="form-group col-md-4">
              <label for="data_nascimento" class="text-secondary small font-weight-bold text-uppercase">Data de Nascimento</label>
              <input id="data_nascimento" type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" name="data_nascimento" required>
            </div>
            <div class="form-group col-md-4">
              <label for="dojo" class="text-secondary small font-weight-bold text-uppercase">Dojo</label>
              <input id="dojo" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="dojo" value="Kenshydokan" required>
            </div>
          </div>

          <!-- Seção de Graduações Dinâmicas -->
          <h5 class="text-danger font-weight-bold mb-3 mt-4"><i class="fa-solid fa-medal mr-2"></i>Graduações em Artes Marciais</h5>
          <div class="card bg-light border-0 p-4 mb-4 rounded-lg">
              <div id="graduacoes-dinamicas-container" class="mb-3">
                  <!-- Linhas dinâmicas inseridas via JavaScript -->
              </div>
              <div>
                  <button type="button" id="btn-adicionar-graduacao" class="btn btn-sm btn-danger font-weight-bold rounded-pill shadow-sm px-4">
                      <i class="fas fa-plus mr-1"></i> Adicionar Graduação
                  </button>
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

  <script>
  document.addEventListener("DOMContentLoaded", function() {
      const container = document.getElementById("graduacoes-dinamicas-container");
      const btnAdd = document.getElementById("btn-adicionar-graduacao");
      let indiceRow = 0;

      const listaArtes = <?php echo json_encode($artes_marciais); ?>;
      const listaGraduacoes = <?php echo json_encode($graduacoes); ?>;

      function criarLinhaGraduacao(idArte = "", idGraduacao = "") {
          const row = document.createElement("div");
          row.className = "row align-items-center mb-2 dynamic-graduacao-row";
          row.id = `grad-row-${indiceRow}`;

          // Options de Artes
          let artesOptions = '<option value="">Selecione a Arte Marcial...</option>';
          listaArtes.forEach(a => {
              const selected = a.id_arte == idArte ? "selected" : "";
              artesOptions += `<option value="${a.id_arte}" ${selected}>${a.nome}</option>`;
          });

          // Options de Graduações
          let graduacoesOptions = '<option value="">Selecione a Graduação...</option>';
          listaGraduacoes.forEach(g => {
              const selected = g.id_graduacao == idGraduacao ? "selected" : "";
              graduacoesOptions += `<option value="${g.id_graduacao}" ${selected}>${g.graduacao}</option>`;
          });

          row.innerHTML = `
              <div class="col-md-6 mb-2 mb-md-0">
                  <select name="graduacoes[${indiceRow}][id_arte]" class="form-control bg-white border-0 shadow-sm" required>
                      ${artesOptions}
                  </select>
              </div>
              <div class="col-md-5 mb-2 mb-md-0">
                  <select name="graduacoes[${indiceRow}][id_graduacao]" class="form-control bg-white border-0 shadow-sm" required>
                      ${graduacoesOptions}
                  </select>
              </div>
              <div class="col-md-1 text-center">
                  <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-remover-row" data-row-id="grad-row-${indiceRow}">
                      <i class="fa-solid fa-trash-can"></i>
                  </button>
              </div>
          `;

          container.appendChild(row);
          
          // Ativar remoção
          row.querySelector(".btn-remover-row").addEventListener("click", function() {
              const rowId = this.getAttribute("data-row-id");
              document.getElementById(rowId).remove();
          });

          indiceRow++;
      }

      btnAdd.addEventListener("click", () => criarLinhaGraduacao());

      // Linha inicial padrão
      criarLinhaGraduacao("1", ""); // Karatê Kenshydokan pré-selecionado por padrão
  });
  </script>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>