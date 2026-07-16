<?php
include_once "menu.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Adicionar Novo Evento</h2>
      <a href="index.php?pagina=eventos" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Eventos
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 700px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-calendar-plus mr-2"></i>Novo Seminário / Evento</h5>
        
        <form action="../../controllers/eventoController.php" method="post">
          <input type="hidden" name="tipo" value="criar_evento">

          <div class="form-group mb-3">
            <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Título do Evento</label>
            <input id="titulo" type="text" class="form-control bg-light border-0 shadow-sm" name="titulo" placeholder="Ex: Seminário de Karatê de Contato" required autofocus>
          </div>

          <div class="form-group mb-3">
            <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Descrição / Informações</label>
            <textarea id="descricao" class="form-control bg-light border-0 shadow-sm" name="descricao" rows="4" placeholder="Detalhes sobre o cronograma, palestrantes, requisitos, etc."></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="data_evento" class="text-secondary small font-weight-bold text-uppercase">Data e Hora do Evento</label>
              <input id="data_evento" type="datetime-local" class="form-control bg-light border-0 shadow-sm" name="data_evento" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label for="tipo_evento" class="text-secondary small font-weight-bold text-uppercase">Tipo de Evento</label>
              <select id="tipo_evento" class="form-control bg-light border-0 shadow-sm font-weight-bold text-dark" name="tipo_evento" onchange="toggleEventFields()" required>
                <option value="presencial">Presencial (Endereço)</option>
                <option value="online">Online / Webinário (Link para assistir)</option>
              </select>
            </div>
          </div>

          <!-- Campo Endereço (Apenas Presencial) -->
          <div class="form-group mb-3" id="group_endereco">
            <label for="endereco" class="text-secondary small font-weight-bold text-uppercase">Endereço do Evento</label>
            <input id="endereco" type="text" class="form-control bg-light border-0 shadow-sm" name="endereco" placeholder="Ex: Av. Filinto Müller, 1200 - Centro, Várzea Grande - MT" required>
          </div>

          <!-- Campo Link para Assistir (Apenas Online) -->
          <div class="form-group mb-3" id="group_link" style="display: none;">
            <label for="link_assistir" class="text-secondary small font-weight-bold text-uppercase">Link para Assistir (Webinário/Online)</label>
            <input id="link_assistir" type="url" class="form-control bg-light border-0 shadow-sm" name="link_assistir" placeholder="Ex: https://youtube.com/live/..." >
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar Evento
            </button>
            <a href="index.php?pagina=eventos" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
  function toggleEventFields() {
      var tipo = document.getElementById("tipo_evento").value;
      var groupEndereco = document.getElementById("group_endereco");
      var groupLink = document.getElementById("group_link");
      var inputEndereco = document.getElementById("endereco");
      var inputLink = document.getElementById("link_assistir");

      if (tipo === "online") {
          groupEndereco.style.display = "none";
          inputEndereco.required = false;
          inputEndereco.value = "";

          groupLink.style.display = "block";
          inputLink.required = true;
      } else {
          groupEndereco.style.display = "block";
          inputEndereco.required = true;

          groupLink.style.display = "none";
          inputLink.required = false;
          inputLink.value = "";
      }
  }
  </script>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='../login.php'; </script>";
}
?>
