<?php
$pageTitle = 'Resultados e Pódios | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/classificacaoModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$categoriaModel = new Categoria();
$categorias = $categoriaModel->buscarPorCampeonato($idCampeonato);

$classificacaoModel = new Classificacao();
$quadroMedalhas = $classificacaoModel->obterQuadroMedalhas($idCampeonato);
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Resultados Oficiais</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-trophy text-warning me-2"></i>Resultados Oficiais e Pódios
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Homologação Final de Resultados</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="../controllers/relatorioController.php?id_campeonato=<?= $idCampeonato ?>&tipo=medalhas" target="_blank" class="btn btn-outline-danger">
            <i class="fas fa-file-pdf me-1"></i> PDF Quadro Medalhas
        </a>
        <a href="certificados.php?id=<?= $idCampeonato ?>" class="btn btn-success">
            <i class="fas fa-award me-1"></i> Emitir Certificados
        </a>
        <a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<!-- Quadro Geral de Medalhas por Dojo -->
<div class="card border-0 shadow-sm mb-4 border-top border-4 border-warning">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-medal text-warning me-2"></i>Quadro Geral de Medalhas por Dojo
        </h5>
        <span class="badge bg-dark">Oficial Kenshydokan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 80px;">Posição</th>
                        <th>Dojo / Academia</th>
                        <th class="text-center" style="width: 120px;">
                            <span class="badge bg-warning text-dark"><i class="fas fa-circle me-1"></i> Ouro (1º)</span>
                        </th>
                        <th class="text-center" style="width: 120px;">
                            <span class="badge bg-secondary"><i class="fas fa-circle me-1"></i> Prata (2º)</span>
                        </th>
                        <th class="text-center" style="width: 120px;">
                            <span class="badge bg-danger"><i class="fas fa-circle me-1"></i> Bronze (3º)</span>
                        </th>
                        <th class="text-center pe-3" style="width: 120px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quadroMedalhas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Nenhuma categoria homologada ainda. As medalhas aparecerão assim que você homologar os pódios abaixo.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($quadroMedalhas as $idx => $qm): ?>
                            <?php 
                                $pos = $idx + 1;
                                $badgeClass = $pos === 1 ? 'bg-warning text-dark' : ($pos === 2 ? 'bg-secondary' : ($pos === 3 ? 'bg-danger' : 'bg-light text-dark border'));
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <span class="badge <?= $badgeClass ?> fs-6 rounded-pill px-3">
                                        <?= $pos ?>º
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6"><?= htmlspecialchars($qm['dojo']) ?></strong>
                                </td>
                                <td class="text-center fw-bold fs-5 text-warning font-monospace">
                                    <?= $qm['ouro'] ?>
                                </td>
                                <td class="text-center fw-bold fs-5 text-secondary font-monospace">
                                    <?= $qm['prata'] ?>
                                </td>
                                <td class="text-center fw-bold fs-5 text-danger font-monospace">
                                    <?= $qm['bronze'] ?>
                                </td>
                                <td class="text-center pe-3 fw-bold fs-5 text-dark font-monospace">
                                    <?= $qm['total_medalhas'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Homologação de Categorias e Pódios -->
<h5 class="fw-bold text-dark mb-3">
    <i class="fas fa-check-double text-danger me-2"></i>Pódios e Homologação por Categoria
</h5>

<div class="row g-4">
    <?php if (empty($categorias)): ?>
        <div class="col-12">
            <div class="alert alert-light border text-center py-4 text-muted">
                Nenhuma categoria cadastrada neste evento.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($categorias as $cat): ?>
            <?php 
                $podio = $classificacaoModel->buscarPorCategoria((int)$cat['id_categoria']); 
                $homologada = !empty($podio);
            ?>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 <?= $homologada ? 'border-start border-4 border-success' : 'border-start border-4 border-warning' ?>">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($cat['nome']) ?></h6>
                            <span class="badge bg-dark fs-7"><?= strtoupper($cat['modalidade']) ?></span>
                            <span class="badge bg-light text-dark border fs-7"><?= $cat['sexo'] === 'M' ? 'Masc' : ($cat['sexo'] === 'F' ? 'Fem' : 'Misto') ?></span>
                        </div>
                        <div>
                            <?php if ($homologada): ?>
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> HOMOLOGADA</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> PENDENTE</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <?php if ($homologada): ?>
                            <!-- Lista do Pódio Oficial -->
                            <div class="list-group list-group-flush mb-3">
                                <?php foreach ($podio as $p): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if ($p['medalha'] === 'ouro'): ?>
                                                <span class="badge bg-warning text-dark fs-6"><i class="fas fa-medal me-1"></i> 1º OURO</span>
                                            <?php elseif ($p['medalha'] === 'prata'): ?>
                                                <span class="badge bg-secondary fs-6"><i class="fas fa-medal me-1"></i> 2º PRATA</span>
                                            <?php elseif ($p['medalha'] === 'bronze'): ?>
                                                <span class="badge bg-danger fs-6"><i class="fas fa-medal me-1"></i> 3º BRONZE</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark border fs-6"><?= $p['colocacao'] ?>º LUGAR</span>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($p['nome_atleta']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars($p['dojo']) ?></small>
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-primary border font-monospace">+<?= $p['pontos_ranking'] ?> pts</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-hourglass-half fa-2x mb-2 text-warning opacity-50"></i>
                                <p class="small mb-0">Esta categoria ainda não possui resultados homologados no sistema.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-light py-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <?php if ($cat['modalidade'] === 'kumite'): ?>
                                <a href="ver_chave.php?id=<?= $cat['id_categoria'] ?>" class="text-decoration-none">Ver Chaveamento</a>
                            <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                <a href="sumula_kata.php?id=<?= $cat['id_categoria'] ?>" class="text-decoration-none">Ver Notas de Kata</a>
                            <?php else: ?>
                                <a href="sumula_tameshiwari.php?id=<?= $cat['id_categoria'] ?>" class="text-decoration-none">Ver Quebramento</a>
                            <?php endif; ?>
                        </small>

                        <!-- Botão de Homologação Automática -->
                        <form action="../controllers/homologacaoController.php" method="POST" onsubmit="return confirm('Deseja homologar oficialmente esta categoria? O pódio será calculado automaticamente com base nas súmulas e chaves.');">
                            <input type="hidden" name="tipo" value="homologar_categoria">
                            <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                            <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                            <button type="submit" class="btn btn-sm <?= $homologada ? 'btn-outline-success' : 'btn-danger' ?>">
                                <i class="fas fa-stamp me-1"></i> <?= $homologada ? 'Re-Homologar' : 'Homologar Pódio' ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
