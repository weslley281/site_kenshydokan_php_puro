<?php
include_once __DIR__ . "/../../models/campeonatoModel.php";

$id_campeonato = isset($_GET['id_campeonato']) ? intval($_GET['id_campeonato']) : 0;
$campeonatoModel = new Campeonato();
$camp = $campeonatoModel->buscarPorId($id_campeonato);

if (!$camp) {
    echo "<script>alert('Campeonato nao encontrado.'); window.location='index.php?pagina=campeonatos';</script>";
    exit();
}
?>

<div class="card border-0 shadow-sm rounded-lg bg-white my-4">
    <div class="card-body p-4">
        <!-- Header -->
        <div class="mb-4">
            <a href="index.php?pagina=campeonatos" class="btn btn-sm btn-outline-secondary rounded-pill mb-3">
                <i class="fa-solid fa-arrow-left mr-2"></i>Voltar
            </a>
            <h4 class="font-weight-bold text-dark mb-1">
                <i class="fa-solid fa-pen text-danger mr-2"></i>Editar Campeonato
            </h4>
            <p class="text-muted small mb-0">Modifique as informacoes do campeonato cadastrado.</p>
        </div>

        <form action="../../controllers/campeonatoController.php" method="POST">
            <input type="hidden" name="tipo" value="editar_campeonato">
            <input type="hidden" name="id_campeonato" value="<?php echo $camp['id_campeonato']; ?>">

            <div class="row">
                <!-- Titulo -->
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="text-secondary small font-weight-bold text-uppercase">Titulo do Campeonato *</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" id="titulo" name="titulo" value="<?php echo htmlspecialchars($camp['titulo']); ?>" required>
                </div>

                <!-- Subtitulo -->
                <div class="col-md-6 mb-3">
                    <label for="subtitulo" class="text-secondary small font-weight-bold text-uppercase">Subtitulo / Destaque *</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" id="subtitulo" name="subtitulo" value="<?php echo htmlspecialchars($camp['subtitulo']); ?>" required>
                </div>
            </div>

            <div class="row">
                <!-- Endereco -->
                <div class="col-md-6 mb-3">
                    <label for="endereco" class="text-secondary small font-weight-bold text-uppercase">Endereco / Local *</label>
                    <input type="text" class="form-control bg-light border-0 shadow-sm" id="endereco" name="endereco" value="<?php echo htmlspecialchars($camp['endereco']); ?>" required>
                </div>

                <!-- Data Realizacao -->
                <div class="col-md-3 mb-3">
                    <label for="dataCriacao" class="text-secondary small font-weight-bold text-uppercase">Data de Realizacao *</label>
                    <input type="date" class="form-control bg-light border-0 shadow-sm" id="dataCriacao" name="dataCriacao" value="<?php echo htmlspecialchars($camp['dataCriacao']); ?>" required>
                </div>

                <!-- Status Inscricao -->
                <div class="col-md-3 mb-3">
                    <label for="ativo" class="text-secondary small font-weight-bold text-uppercase">Inscricoes Abertas? *</label>
                    <select class="form-control bg-light border-0 shadow-sm" id="ativo" name="ativo" required>
                        <option value="sim" <?php echo ($camp['ativo'] === 'sim') ? 'selected' : ''; ?>>Sim (Inscricoes Abertas)</option>
                        <option value="nao" <?php echo ($camp['ativo'] === 'nao') ? 'selected' : ''; ?>>Nao (Campeonato Realizado)</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Tipo Campeonato -->
                <div class="col-md-4 mb-3">
                    <label for="tipo_campeonato" class="text-secondary small font-weight-bold text-uppercase">Tipo do Campeonato *</label>
                    <select class="form-control bg-light border-0 shadow-sm" id="tipo_campeonato" name="tipo_campeonato" required>
                        <option value="interno" <?php echo ($camp['tipo'] === 'interno') ? 'selected' : ''; ?>>Interno (Inscricao direta pelo site)</option>
                        <option value="externo" <?php echo ($camp['tipo'] === 'externo') ? 'selected' : ''; ?>>Externo (Link para site externo)</option>
                    </select>
                </div>

                <!-- Link Inscricao Externa -->
                <div class="col-md-8 mb-3" id="container_link_externo" style="display: none;">
                    <label for="link_externo" class="text-secondary small font-weight-bold text-uppercase">Link de Inscricao Externa *</label>
                    <input type="url" class="form-control bg-light border-0 shadow-sm" id="link_externo" name="link_externo" value="<?php echo htmlspecialchars($camp['link_externo']); ?>" placeholder="https://exemplo.com/inscricao">
                </div>
            </div>

            <div class="text-right mt-4">
                <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Salvar Alteracoes
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
        }
    }

    tipoSelect.addEventListener("change", toggleLinkField);
    toggleLinkField(); // Executa no carregamento inicial
});
</script>
