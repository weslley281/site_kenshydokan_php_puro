<?php
include_once __DIR__ . "/../../models/campeonatoModel.php";
$campeonatoModel = new Campeonato();
$campeonatos = $campeonatoModel->buscarTodos();
?>

<div class="card border-0 shadow-sm rounded-lg bg-white my-4">
    <div class="card-body p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap: 15px;">
            <div>
                <h4 class="font-weight-bold text-dark mb-1">
                    <i class="fa-solid fa-trophy text-danger mr-2"></i>Gerenciar Campeonatos
                </h4>
                <p class="text-muted small mb-0">Cadastre, edite e acompanhe os campeonatos internos e externos.</p>
            </div>
            <a href="index.php?pagina=criar_campeonato" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
                <i class="fa-solid fa-plus mr-2"></i>Cadastrar Campeonato
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table id="tabelaCampeonatos" class="table table-hover align-middle" style="width: 100%;">
                <thead>
                    <tr class="text-secondary small font-weight-bold border-bottom">
                        <th>Título</th>
                        <th>Subtítulo</th>
                        <th>Tipo</th>
                        <th>Data Realização</th>
                        <th>Endereço</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 150px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campeonatos as $camp): ?>
                        <tr>
                            <td class="align-middle font-weight-bold text-dark">
                                <?php echo htmlspecialchars($camp['titulo']); ?>
                            </td>
                            <td class="align-middle text-secondary small">
                                <?php echo htmlspecialchars($camp['subtitulo']); ?>
                            </td>
                            <td class="align-middle">
                                <?php if ($camp['tipo'] === 'externo'): ?>
                                    <span class="badge badge-info text-uppercase px-2 py-1 small">
                                        <i class="fa-solid fa-square-arrow-up-right mr-1"></i>Externo
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-success text-uppercase px-2 py-1 small">
                                        <i class="fa-solid fa-house-chimney mr-1"></i>Interno
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle font-weight-bold text-secondary small">
                                <?php echo date("d/m/Y", strtotime($camp['dataCriacao'])); ?>
                            </td>
                            <td class="align-middle text-secondary small">
                                <?php echo htmlspecialchars($camp['endereco']); ?>
                            </td>
                            <td class="align-middle text-center">
                                <?php if ($camp['ativo'] === 'sim'): ?>
                                    <span class="badge badge-success px-3 py-1 rounded-pill text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Inscrições Abertas</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary px-3 py-1 rounded-pill text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Realizado</span>
                                <?php endif; ?>
                            </td>
                            <td class="align-middle text-center">
                                <a href="index.php?pagina=editar_campeonato&id_campeonato=<?php echo $camp['id_campeonato']; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar" style="width: 32px; height: 32px; padding: 5px 0;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-deletar" data-id="<?php echo $camp['id_campeonato']; ?>" data-titulo="<?php echo htmlspecialchars($camp['titulo']); ?>" data-toggle="modal" data-target="#modalExcluirCampeonato" title="Excluir" style="width: 32px; height: 32px; padding: 5px 0;">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Unico Reutilizavel de Exclusao -->
<div class="modal fade" id="modalExcluirCampeonato" tabindex="-1" role="dialog" aria-labelledby="modalExcluirCampeonatoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title font-weight-bold" id="modalExcluirCampeonatoLabel">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../../controllers/campeonatoController.php" method="POST">
                <input type="hidden" name="tipo" value="excluir_campeonato">
                <input type="hidden" name="id_campeonato" id="excluir_id">
                <div class="modal-body p-4">
                    <p class="mb-1">Você tem certeza que deseja excluir o campeonato:</p>
                    <h5 class="font-weight-bold text-dark mb-3" id="excluir_titulo"></h5>
                    <p class="text-muted small mb-0">Esta ação é permanente e não poderá ser desfeita.</p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary font-weight-bold rounded-pill px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger font-weight-bold rounded-pill px-4 shadow-sm">Confirmar Exclusão</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script do DataTable e Modal -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tabelaCampeonatos').DataTable({
            "order": [[3, "desc"]], // Ordena pela data de realização por padrão
            "pageLength": 10,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
            }
        });
    }

    // Modal de Exclusao Dinamico
    $(document).on("click", ".btn-deletar", function() {
        var id = $(this).data("id");
        var titulo = $(this).data("titulo");
        $("#excluir_id").val(id);
        $("#excluir_titulo").text(titulo);
    });
});
</script>
