<?php
// views/admin/chamada.php
include_once __DIR__ . "/../../models/arteModel.php";
include_once __DIR__ . "/../../models/filiadoModel.php";
include_once __DIR__ . "/../../models/listaPresencaModel.php";

$arteRepo = new ArteMarcial();
$filiadoRepo = new FiliadoModel();
$presencaRepo = new ListaPresencaModel();

$modalidades = $arteRepo->listarArtes();

$id_arte = isset($_GET['id_arte']) ? intval($_GET['id_arte']) : 0;
$data_presenca = isset($_GET['data_presenca']) ? $_GET['data_presenca'] : date('Y-m-d');

$alunos = [];
$chamadaSalva = [];
$conteudo_aula = "";

if ($id_arte > 0) {
    // Busca alunos matriculados nessa arte marcial
    // No nosso novo schema, são filiados que possuem registro em filiados_graduacoes para esse id_arte
    include_once __DIR__ . "/../../db/conexao.php";
    $db = new Conexao();
    $conn = $db->conectar();
    
    $stmt = $conn->prepare("
        SELECT f.id_filiado, f.nome, f.codigo, f.dojo, g.graduacao
        FROM filiados f
        INNER JOIN filiados_graduacoes fg ON f.id_filiado = fg.id_filiado
        INNER JOIN graduacoes g ON fg.id_graduacao = g.id_graduacao
        WHERE fg.id_arte = ? AND f.confirmacao = 'sim'
        ORDER BY f.nome ASC
    ");
    $stmt->bind_param("i", $id_arte);
    $stmt->execute();
    $alunos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Busca chamada já salva se houver
    $chamadaSalva = $presencaRepo->buscarChamadaPorDataEArte($id_arte, $data_presenca);
    
    // Busca o conteúdo da aula daquele dia se houver
    $stmtCont = $conn->prepare("
        SELECT conteudo_aula 
        FROM lista_presenca 
        WHERE id_arte = ? AND data_presenca = ? 
        LIMIT 1
    ");
    $stmtCont->bind_param("is", $id_arte, $data_presenca);
    $stmtCont->execute();
    $resCont = $stmtCont->get_result()->fetch_assoc();
    if ($resCont) {
        $conteudo_aula = $resCont['conteudo_aula'];
    }
    $stmtCont->close();
}
?>

<style>
.btn-chamada-control {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    font-weight: bold;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #dee2e6;
    user-select: none;
}
.btn-chamada-control:hover {
    transform: scale(1.12);
}
.btn-chamada-control.active-p {
    background-color: #28a745;
    border-color: #28a745;
    color: #fff !important;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.35);
}
.btn-chamada-control.active-f {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff !important;
    box-shadow: 0 4px 10px rgba(220, 53, 69, 0.35);
}
.btn-chamada-control.inactive {
    background-color: #f8f9fa;
    color: #6c757d !important;
}
.btn-chamada-control.inactive:hover {
    background-color: #e2e6ea;
    border-color: #ced4da;
    color: #495057 !important;
}
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-weight-bold text-dark mb-0">Lista de Presença / Chamada</h2>
    </div>

    <!-- Filtro de Chamada -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body p-4">
            <form method="GET" action="index.php" class="row align-items-end">
                <input type="hidden" name="pagina" value="chamada">
                <input type="hidden" name="referencia" value="<?php echo htmlspecialchars($referencia ?? date('Y-m')); ?>">
                
                <div class="col-md-5 form-group mb-0">
                    <label for="id_arte" class="text-secondary small font-weight-bold text-uppercase">Modalidade / Arte Marcial</label>
                    <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="id_arte" name="id_arte" required onchange="this.form.submit()">
                        <option value="">Selecione uma modalidade...</option>
                        <?php foreach ($modalidades as $mod): ?>
                            <option value="<?php echo $mod['id_arte']; ?>" <?php echo ($mod['id_arte'] == $id_arte) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mod['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 form-group mb-0">
                    <label for="data_presenca" class="text-secondary small font-weight-bold text-uppercase">Data da Aula</label>
                    <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_presenca" name="data_presenca" value="<?php echo $data_presenca; ?>" required onchange="this.form.submit()">
                </div>

                <div class="col-md-3 form-group mb-0">
                    <button type="submit" class="btn btn-danger btn-lg btn-block font-weight-bold rounded-pill shadow-sm">
                        <i class="fa-solid fa-sync mr-2"></i> Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($id_arte > 0): ?>
        <!-- Planilha de Chamada -->
        <div class="card border-0 shadow-sm rounded-lg">
            <form action="../../controllers/presencaController.php" method="POST">
                <input type="hidden" name="tipo" value="salvar_chamada">
                <input type="hidden" name="id_arte" value="<?php echo $id_arte; ?>">
                <input type="hidden" name="data_presenca" value="<?php echo $data_presenca; ?>">

                <div class="card-body p-4">
                    <!-- Conteúdo da Aula -->
                    <div class="form-group mb-4">
                        <label for="conteudo_aula" class="text-secondary small font-weight-bold text-uppercase">Conteúdo Ministrado na Aula</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="conteudo_aula" name="conteudo_aula" value="<?php echo htmlspecialchars($conteudo_aula); ?>" placeholder="Ex: Treinamento de Katas básicos e Kumite" required>
                    </div>

                    <?php if (empty($alunos)): ?>
                        <div class="alert alert-warning border-0 rounded p-4 text-center">
                            <i class="fa-solid fa-triangle-exclamation fa-2xl mb-2"></i>
                            <p class="mb-0 font-weight-bold">Nenhum filiado confirmado matriculado nesta modalidade.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="text-secondary small font-weight-bold border-bottom">
                                        <th scope="col" style="width: 80px;">Status</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Nome do Aluno (Kohai/Sempai)</th>
                                        <th scope="col">Dojô</th>
                                        <th scope="col">Graduação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alunos as $aluno): 
                                        $aid = $aluno['id_filiado'];
                                        // Padrão é falta se não houver chamada anterior, senão recupera o status salvo
                                        $isPresent = !empty($chamadaSalva) && (isset($chamadaSalva[$aid]) && $chamadaSalva[$aid] === 'P');
                                    ?>
                                        <tr>
                                             <td class="align-middle text-center">
                                                 <!-- Hidden input para garantir o envio do ID de todos os alunos -->
                                                 <input type="hidden" name="filiados_ids[]" value="<?php echo $aid; ?>">
                                                 
                                                 <!-- V / X Selector -->
                                                 <div class="d-flex align-items-center justify-content-center" style="gap: 8px;">
                                                     <label class="mb-0">
                                                         <input type="radio" name="status[<?php echo $aid; ?>]" value="P" <?php echo $isPresent ? 'checked' : ''; ?> class="d-none presenca-radio-<?php echo $aid; ?>">
                                                         <span class="btn-chamada-control btn-presenca-<?php echo $aid; ?> <?php echo $isPresent ? 'active-p' : 'inactive'; ?>" onclick="marcarPresenca(<?php echo $aid; ?>, 'P')">V</span>
                                                     </label>
                                                     <label class="mb-0">
                                                         <input type="radio" name="status[<?php echo $aid; ?>]" value="F" <?php echo !$isPresent ? 'checked' : ''; ?> class="d-none presenca-radio-<?php echo $aid; ?>">
                                                         <span class="btn-chamada-control btn-falta-<?php echo $aid; ?> <?php echo !$isPresent ? 'active-f' : 'inactive'; ?>" onclick="marcarPresenca(<?php echo $aid; ?>, 'F')">X</span>
                                                     </label>
                                                 </div>
                                             </td>
                                            <td class="align-middle text-secondary font-weight-bold">#<?php echo htmlspecialchars($aluno['codigo'] ?? $aid); ?></td>
                                            <td class="align-middle font-weight-bold text-dark text-capitalize">
                                                <?php echo htmlspecialchars($aluno['nome']); ?>
                                            </td>
                                            <td class="align-middle text-muted small text-capitalize"><?php echo htmlspecialchars($aluno['dojo']); ?></td>
                                            <td class="align-middle text-dark font-weight-bold small text-capitalize"><?php echo htmlspecialchars($aluno['graduacao']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2">
                                <i class="fa-solid fa-save mr-2"></i> Salvar Chamada
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-lg">
            <div class="card-body p-5 text-center text-secondary">
                <i class="fa-solid fa-clipboard-user fa-3x mb-3 text-danger"></i>
                <h5>Selecione uma modalidade e data no painel acima para abrir a folha de chamadas.</h5>
            </div>
        </div>
    <?php endif; ?>

    <!-- Aulas já Realizadas -->
    <div class="card border-0 shadow-sm rounded-lg mt-5">
        <div class="card-body p-4">
            <h4 class="font-weight-bold text-dark mb-4"><i class="fa-solid fa-list-check mr-2 text-danger"></i>Aulas já Realizadas / Histórico</h4>
            
            <?php
            $aulasRealizadas = $presencaRepo->listarAulasRealizadas();
            ?>
            <div class="table-responsive">
                <table id="minhaTabela" class="table table-hover align-middle" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-secondary small font-weight-bold border-bottom">
                            <th scope="col">Modalidade</th>
                            <th scope="col">Data da Aula</th>
                            <th scope="col">Conteúdo Ministrado</th>
                            <th scope="col" class="text-center">Alunos Confirmados</th>
                            <th scope="col" class="text-center">Alunos Presentes</th>
                            <th scope="col" class="text-center" style="width: 150px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($aulasRealizadas)): ?>
                            <?php foreach ($aulasRealizadas as $index => $aula): 
                                $id_art = $aula['id_arte'];
                                $data_p = $aula['data_presenca'];
                                $modalidade = $aula['modalidade'];
                                $conteudo = $aula['conteudo_aula'];
                                $total_alunos = $aula['total_alunos'];
                                $total_presentes = $aula['total_presentes'];
                            ?>
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($modalidade); ?></td>
                                    <td class="align-middle text-dark font-weight-bold"><?php echo date("d/m/Y", strtotime($data_p)); ?></td>
                                    <td class="align-middle text-secondary small"><?php echo htmlspecialchars($conteudo); ?></td>
                                    <td class="align-middle text-center text-dark font-weight-bold"><?php echo $total_alunos; ?></td>
                                    <td class="align-middle text-center text-success font-weight-bold"><?php echo $total_presentes; ?></td>
                                    <td class="align-middle text-center">
                                        <!-- Editar Aula -->
                                        <a href="index.php?pagina=chamada&id_arte=<?php echo $id_art; ?>&data_presenca=<?php echo $data_p; ?>&referencia=<?php echo htmlspecialchars($referencia ?? date('Y-m')); ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle mr-1" title="Editar Frequência" style="width: 32px; height: 32px; padding: 5px 0;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Excluir Aula -->
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" data-toggle="modal" data-target="#modalExcluirAula<?php echo $index; ?>" title="Excluir Aula" style="width: 32px; height: 32px; padding: 5px 0;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>

                                        <!-- Modal de Confirmação de Exclusão -->
                                        <div class="modal fade" id="modalExcluirAula<?php echo $index; ?>" tabindex="-1" role="dialog" aria-labelledby="excluirAulaLabel<?php echo $index; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content border-0 shadow-lg text-left">
                                                    <div class="modal-header bg-danger text-white border-0 py-3">
                                                        <h5 class="modal-title font-weight-bold" id="excluirAulaLabel<?php echo $index; ?>">
                                                            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Confirmar Exclusão
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body p-4 text-center">
                                                        <p class="lead mb-2">Deseja realmente excluir a aula de <b><?php echo htmlspecialchars($modalidade); ?></b>?</p>
                                                        <h5 class="font-weight-bold text-danger mb-2">Data: <?php echo date("d/m/Y", strtotime($data_p)); ?></h5>
                                                        <p class="text-secondary small">Isso excluirá permanentemente o histórico de presença de todos os alunos nesta aula.</p>
                                                    </div>
                                                    <div class="modal-footer border-0 bg-light py-3 text-center d-flex justify-content-center">
                                                        <button type="button" class="btn btn-secondary px-4 rounded-pill font-weight-bold mr-2" data-dismiss="modal">Cancelar</button>
                                                        <form action="../../controllers/presencaController.php" method="post" class="d-inline">
                                                            <input type="hidden" name="tipo" value="excluir_aula">
                                                            <input type="hidden" name="id_arte" value="<?php echo $id_art; ?>">
                                                            <input type="hidden" name="data_presenca" value="<?php echo $data_p; ?>">
                                                            <button type="submit" class="btn btn-danger px-4 rounded-pill font-weight-bold shadow-sm">Excluir</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function marcarPresenca(id, status) {
    const radioP = document.querySelector(`.presenca-radio-${id}[value="P"]`);
    const radioF = document.querySelector(`.presenca-radio-${id}[value="F"]`);
    const btnP = document.querySelector(`.btn-presenca-${id}`);
    const btnF = document.querySelector(`.btn-falta-${id}`);
    
    if (status === 'P') {
        radioP.checked = true;
        btnP.className = `btn-chamada-control btn-presenca-${id} active-p`;
        btnF.className = `btn-chamada-control btn-falta-${id} inactive`;
    } else {
        radioF.checked = true;
        btnF.className = `btn-chamada-control btn-falta-${id} active-f`;
        btnP.className = `btn-chamada-control btn-presenca-${id} inactive`;
    }
}
</script>
