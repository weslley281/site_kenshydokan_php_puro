<div class="card border-0 shadow-sm rounded-lg bg-white my-4">
    <div class="card-body p-4">
        <!-- Header -->
        <div class="mb-4">
            <a href="index.php?pagina=campeonatos" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
                <i class="fa-solid fa-arrow-left mr-2"></i>Voltar
            </a>
            <h4 class="font-weight-bold text-dark mb-1">
                <i class="fa-solid fa-plus text-danger mr-2"></i>Cadastrar Novo Campeonato
            </h4>
            <p class="text-muted small mb-0">Insira as informacoes do novo torneio ou campeonato.</p>
        </div>

        <form action="../../controllers/campeonatoController.php" method="POST">
            <input type="hidden" name="tipo" value="criar_campeonato">

            <div class="row">
                <!-- Titulo -->
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Titulo do Campeonato *</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" id="titulo" name="titulo" placeholder="Ex: I Copa Varzea-grandense de Karate de Contato" required>
                </div>

                <!-- Subtitulo -->
                <div class="col-md-6 mb-3">
                    <label for="subtitulo" class="text-secondary small font-weight-bold text-uppercase">Subtitulo / Destaque *</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" id="subtitulo" name="subtitulo" placeholder="Ex: Evento oficial de graduacao e integracao" required>
                </div>
            </div>

            <div class="row">
                <!-- Endereco -->
                <div class="col-md-6 mb-3">
                    <label for="endereco" class="text-secondary small font-weight-bold text-uppercase">Endereco / Local *</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" id="endereco" name="endereco" placeholder="Ex: Ginasio Fiotao, Varzea Grande - MT" required>
                </div>

                <!-- Data Realizacao -->
                <div class="col-md-3 mb-3">
                    <label for="dataCriacao" class="text-secondary small font-weight-bold text-uppercase">Data de Realizacao *</label>
                    <input type="date" class="form-control bg-light border-0 shadow-sm" id="dataCriacao" name="dataCriacao" required>
                </div>

                <!-- Status Inscricao -->
                <div class="col-md-3 mb-3">
                    <label for="ativo" class="text-secondary small font-weight-bold text-uppercase">Inscricoes Abertas? *</label>
                    <select class="form-control bg-light border-0 shadow-sm" id="ativo" name="ativo" required>
                        <option value="sim">Sim (Inscricoes Abertas)</option>
                        <option value="nao">Nao (Campeonato Realizado)</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Tipo Campeonato -->
                <div class="col-md-4 mb-3">
                    <label for="tipo_campeonato" class="text-secondary small font-weight-bold text-uppercase">Tipo do Campeonato *</label>
                    <select class="form-control bg-light border-0 shadow-sm" id="tipo_campeonato" name="tipo_campeonato" required>
                        <option value="interno">Interno (Inscricao direta pelo site)</option>
                        <option value="externo">Externo (Link para site externo)</option>
                    </select>
                </div>

                <!-- Link Inscricao Externa -->
                <div class="col-md-8 mb-3" id="container_link_externo" style="display: none;">
                    <label for="link_externo" class="text-secondary small font-weight-bold text-uppercase">Link de Inscricao Externa *</label>
                    <input type="url" class="form-control bg-light border-0 shadow-sm" id="link_externo" name="link_externo" placeholder="https://exemplo.com/inscricao">
                </div>
            </div>

            <div class="text-right mt-4">
                <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Salvar Campeonato
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var tipoSelect = document.getElementById("tipo_campeonato");
    var linkContainer = document.getElementById("container_link_externo");
    var linkInput = document.getElementById("link_externo");

    function toggleLinkField() {
        if (tipoSelect.value === "externo") {
            linkContainer.style.display = "block";
            linkInput.setAttribute("required", "required");
        } else {
            linkContainer.style.display = "none";
            linkInput.removeAttribute("required");
            linkInput.value = "";
        }
    }

    tipoSelect.addEventListener("change", toggleLinkField);
    toggleLinkField(); // Executa no carregamento inicial
});
</script>
