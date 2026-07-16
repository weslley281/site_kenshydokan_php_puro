<?php
$pageTitle = "Filiados Credenciados | Instituto Kenshydokan";
$pageDescription = "Veja a relação de filiados oficiais (atletas, instrutores e dojôs) credenciados e reconhecidos pelo Instituto Kenshydokan.";
include "menu.php";
include_once "../models/filiadoModel.php";
include_once "../models/arteModel.php";

$arteModel = new ArteMarcial();
$artes = $arteModel->listarArtes();

$filiadoModel = new FiliadoModel();
?>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="font-weight-bold text-dark mb-2">Filiados por Modalidade</h2>
        <p class="text-muted">Selecione uma arte marcial abaixo para ver a lista de filiados e suas respectivas graduações.</p>
    </div>

    <!-- Menu de Seleção de Arte Marcial -->
    <ul class="nav nav-pills nav-fill mb-5 p-1 bg-white rounded-pill shadow-sm border" id="pills-tab" role="tablist" style="gap: 5px; max-width: 900px; margin: 0 auto;">
        <?php
        $first = true;
        foreach ($artes as $arte) {
            $active_class = $first ? 'active bg-danger text-white' : 'text-secondary';
            $selected = $first ? 'true' : 'false';
            $safe_id = "pill-arte-" . $arte['id_arte'];
            ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link rounded-pill font-weight-bold <?php echo $active_class; ?>" 
                   id="<?php echo $safe_id; ?>-tab" 
                   data-toggle="pill" 
                   href="#<?php echo $safe_id; ?>" 
                   role="tab" 
                   aria-controls="<?php echo $safe_id; ?>" 
                   aria-selected="<?php echo $selected; ?>"
                   style="transition: all 0.3s ease;">
                    <i class="fa-solid fa-hand-fist mr-1"></i> <?php echo htmlspecialchars($arte['nome']); ?>
                </a>
            </li>
            <?php
            $first = false;
        }
        ?>
    </ul>

    <!-- Conteúdo dos Painéis -->
    <div class="tab-content" id="pills-tabContent">
        <?php
        $first = true;
        foreach ($artes as $arte) {
            $active_pane = $first ? 'show active' : '';
            $safe_id = "pill-arte-" . $arte['id_arte'];
            $filiados = $filiadoModel->listarFiliadosPorArte($arte['id_arte']);
            ?>
            <div class="tab-pane fade <?php echo $active_pane; ?>" id="<?php echo $safe_id; ?>" role="tabpanel" aria-labelledby="<?php echo $safe_id; ?>-tab">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                    <div class="card-header bg-dark text-white p-3 d-flex align-items-center">
                        <i class="fa-solid fa-medal mr-2 text-danger"></i>
                        <h5 class="mb-0 font-weight-bold"><?php echo htmlspecialchars($arte['nome']); ?></h5>
                        <span class="badge badge-danger ml-auto font-weight-bold px-3 py-1 rounded-pill" style="background-color: var(--primary-red);">
                            <?php echo count($filiados); ?> Filiados
                        </span>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle dynamic-datatables" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-secondary small font-weight-bold border-bottom">
                                        <th scope="col" style="width: 100px;">Código</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Dojô</th>
                                        <th scope="col">Graduação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($filiados)) {
                                        foreach ($filiados as $res) {
                                            $id_filiado = $res["id_filiado"];
                                            $nome = $res["nome"];
                                            $dojo = $res["dojo"];
                                            $graduacao_nome = $res["graduacao_nome"];
                                    ?>
                                            <tr>
                                                <th class="font-weight-bold text-secondary" scope="row">#<?php echo $id_filiado; ?></th>
                                                <td class="text-capitalize text-dark font-weight-bold"><?php echo htmlspecialchars($nome); ?></td>
                                                <td class="text-capitalize text-muted"><?php echo htmlspecialchars($dojo); ?></td>
                                                <td class="text-capitalize"><span class="badge badge-light border text-danger font-weight-bold px-2 py-1"><?php echo htmlspecialchars($graduacao_nome); ?></span></td>
                                            </tr>
                                    <?php 
                                        }
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $first = false;
        }
        ?>
    </div>
</div>

<!-- Script para inicialização dinâmica dos DataTables -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializa o DataTable para todas as tabelas dinâmicas
        $('.dynamic-datatables').DataTable({
            "order": [[1, "asc"]], // Ordena por nome (coluna 1)
            "pageLength": 10,
            "searching": true,
            "destroy": true, // Permite reinstanciar
            "language": {
                "search": "Pesquisar:",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando de _START_ até _END_ de _TOTAL_ filiados",
                "infoEmpty": "Mostrando 0 até 0 de 0 filiados",
                "infoFiltered": "(filtrado de _MAX_ filiados no total)",
                "zeroRecords": "Nenhum filiado encontrado",
                "emptyTable": "Nenhum filiado ativo cadastrado com graduação nesta modalidade",
                "paginate": {
                    "first": "Primeiro",
                    "previous": "Anterior",
                    "next": "Próximo",
                    "last": "Último"
                }
            }
        });
    });
</script>

<?php
include "rodape.php";
?>