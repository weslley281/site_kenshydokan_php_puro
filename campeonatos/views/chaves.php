<?php
$pageTitle = 'Chaves e Sorteios | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/chaveModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$categoriaModel = new Categoria();
$inscricaoModel = new Inscricao();
$chaveModel = new Chave();

$categorias = $categoriaModel->buscarPorCampeonato($idCampeonato);
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Chaves</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-sitemap text-danger me-2"></i>Chaveamento e Sorteios
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Sorteio com potências de 2, isolamento de dojo e Byes automáticos</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Painel do Evento
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Categoria</th>
                        <th>Modalidade</th>
                        <th>Idades / Sexo</th>
                        <th>Inscritos / Aptos</th>
                        <th>Situação da Chave</th>
                        <th class="text-end pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $idx => $cat): ?>
                        <?php
                        $chave = $chaveModel->buscarPorCategoria((int)$cat['id_categoria']);
                        $inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato, (int)$cat['id_categoria']);
                        $totalIns = count($inscricoes);
                        $aptos = count(array_filter($inscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada'));
                        ?>
                        <tr>
                            <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                            <td>
                                <strong><?= htmlspecialchars($cat['nome']) ?></strong>
                            </td>
                            <td>
                                <?php if ($cat['modalidade'] === 'kumite'): ?>
                                    <span class="badge bg-danger">KUMITE</span>
                                <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                    <span class="badge bg-primary">KATA</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">TAMESHIWARI</span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?> anos &bull; 
                                <?= $cat['sexo'] === 'M' ? 'Masc' : ($cat['sexo'] === 'F' ? 'Fem' : 'Misto') ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= $totalIns ?> inscritos (<strong><?= $aptos ?> aptos</strong>)
                                </span>
                            </td>
                            <td>
                                <?php if ($cat['modalidade'] === 'kumite'): ?>
                                    <?php if ($chave): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Chave Gerada (<?= $chave['total_atletas'] ?> atletas)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>Pendente de Sorteio</span>
                                    <?php endif; ?>
                                <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                    <span class="badge bg-info text-dark">Súmula de Formas (3 a 5 árbitros)</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Súmula de Quebramento</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <?php if ($cat['modalidade'] === 'kumite'): ?>
                                    <?php if ($chave): ?>
                                        <a href="ver_chave.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i> Ver Chave
                                        </a>
                                        <a href="../controllers/relatorioController.php?id_campeonato=<?= $idCampeonato ?>&tipo=chave&id_categoria=<?= $cat['id_categoria'] ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning" title="Regerar Sorteio" onclick="confirmarSorteio(<?= $cat['id_categoria'] ?>, '<?= addslashes($cat['nome']) ?>', <?= $aptos ?>)">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-danger" onclick="confirmarSorteio(<?= $cat['id_categoria'] ?>, '<?= addslashes($cat['nome']) ?>', <?= $aptos ?>)" <?= $aptos < 2 ? 'disabled title="Mínimo 2 atletas aptos"' : '' ?>>
                                            <i class="fas fa-random me-1"></i> Sortear Chave
                                        </button>
                                    <?php endif; ?>
                                <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                    <a href="sumula_kata.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-user-ninja me-1"></i> Súmula Kata
                                    </a>
                                <?php else: ?>
                                    <a href="sumula_tameshiwari.php?id=<?= $cat['id_categoria'] ?>" class="btn btn-sm btn-outline-warning text-dark">
                                        <i class="fas fa-hammer me-1"></i> Tameshiwari
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Confirmação de Sorteio -->
<div class="modal fade" id="modalSorteio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/chaveamentoController.php">
                <input type="hidden" name="tipo" value="gerar_chave">
                <input type="hidden" name="id_categoria" id="sorteioIdCategoria" value="">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-random text-danger me-2"></i>Sorteio Oficial de Chave Eliminatória
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Categoria: <strong id="sorteioNomeCat" class="text-danger"></strong><br>
                        Atletas Aptos: <strong id="sorteioAptos" class="text-success"></strong>
                    </p>
                    <div class="alert alert-info border-0 small">
                        O algoritmo Kenshydokan distribuirá os competidores aplicando:
                        <ul class="mb-0 mt-1 ps-3">
                            <li>Isolamento de mesmo Dojo nas primeiras fases;</li>
                            <li>Atribuição automática de Byes (isenções);</li>
                            <li>Geração automática das lutas encadeadas até a final.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Executar Sorteio Agora</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarSorteio(idCat, nomeCat, aptos) {
    if (aptos < 2) {
        alertify.alert('Atenção', 'São necessários ao menos 2 atletas aptos na pesagem para realizar o sorteio.');
        return;
    }
    document.getElementById('sorteioIdCategoria').value = idCat;
    document.getElementById('sorteioNomeCat').textContent = nomeCat;
    document.getElementById('sorteioAptos').textContent = aptos + ' atletas';
    var modal = new bootstrap.Modal(document.getElementById('modalSorteio'));
    modal.show();
}
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
