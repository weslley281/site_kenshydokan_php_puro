<?php
$pageTitle = 'Súmula Oficial de Tameshiwari | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/sumulaTameshiwariModel.php";

$idCategoria = (int)($_GET['id'] ?? ($_GET['id_categoria'] ?? 0));
$categoriaModel = new Categoria();
$categoria = $categoriaModel->buscarPorId($idCategoria);

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$idCampeonato = (int)$categoria['id_campeonato'];
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

$inscricaoModel = new Inscricao();
$inscricoes = $inscricaoModel->buscarPorCategoria($idCategoria);

$tameshiwariModel = new SumulaTameshiwari();
$tentativas = $tameshiwariModel->buscarPorCategoria($idCategoria);
$ranking = $tameshiwariModel->obterRanking($idCategoria);
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo'] ?? '') ?></a></li>
                <li class="breadcrumb-item"><a href="categorias.php?id=<?= $idCampeonato ?>" class="text-decoration-none">Categorias</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($categoria['nome']) ?></li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-hammer text-warning me-2"></i>Súmula de Tameshiwari (Quebramento): <?= htmlspecialchars($categoria['nome']) ?>
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo'] ?? '') ?> &bull; 4 Técnicas Oficiais: Seiken, Shuto, Hiji e Chusoku/Sokuto</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-warning text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalRegistrarQuebramento">
            <i class="fas fa-plus me-1"></i> Registrar Tentativa
        </button>
        <a href="categorias.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<!-- Regras Oficiais de Tameshiwari Kenshydokan -->
<div class="card border-0 shadow-sm mb-4 border-start border-4 border-warning">
    <div class="card-body p-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-gavel text-warning me-1"></i> Normas Oficiais de Quebramento Kenshydokan</h6>
                <p class="text-muted small mb-0">
                    O atleta declara a quantidade de materiais antes de cada tentativa. Tempo regulamentar máximo: <strong>60 segundos</strong> por execução.
                    <span class="text-danger fw-bold">Atenção à regra oficial:</span> Falha na tentativa ou quebra parcial de materiais acarreta <strong>nota ZERO</strong> imediata para a técnica.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-warning text-dark fs-7">Atletas Aptos: <?= count($inscricoes) ?></span>
                <span class="badge bg-dark fs-7">Tentativas Registradas: <?= count($tentativas) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Classificação e Ranking Geral de Tameshiwari -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-trophy text-warning me-2"></i>Classificação Geral de Tameshiwari
        </h5>
        <span class="badge bg-dark">Total de Materiais Quebrados</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 80px;">Posição</th>
                        <th>Atleta</th>
                        <th>Dojo</th>
                        <th class="text-center">Tentativas Válidas</th>
                        <th class="text-end pe-4">Total Quebrado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ranking)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Nenhuma tentativa registrada até o momento nesta categoria.
                                <br>
                                <button class="btn btn-sm btn-warning text-dark fw-bold mt-2" data-bs-toggle="modal" data-bs-target="#modalRegistrarQuebramento">
                                    Registrar Primeira Tentativa
                                </button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ranking as $idx => $r): ?>
                            <?php 
                                $colocacao = $idx + 1;
                                $classeBadge = $colocacao === 1 ? 'bg-warning text-dark' : ($colocacao === 2 ? 'bg-secondary' : ($colocacao === 3 ? 'bg-danger' : 'bg-light text-dark border'));
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <span class="badge <?= $classeBadge ?> fs-6 rounded-pill px-3">
                                        <?= $colocacao ?>º
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6"><?= htmlspecialchars($r['nome_atleta_snapshot']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($r['dojo_snapshot']) ?></span>
                                </td>
                                <td class="text-center font-monospace">
                                    <?= $r['tentativas_validas'] ?> válidas
                                </td>
                                <td class="text-end pe-4">
                                    <span class="display-6 fs-4 fw-bold text-warning font-monospace">
                                        <?= (int)$r['total_quebrado'] ?>
                                    </span>
                                    <small class="text-muted d-block font-sans-serif" style="font-size: 0.75rem;">peças quebradas</small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Histórico Detalhado de Tentativas -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-list-check text-primary me-2"></i>Histórico Individual de Tentativas
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-monospace small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Atleta</th>
                        <th>Técnica</th>
                        <th>Material</th>
                        <th class="text-center">Declaradas</th>
                        <th class="text-center">Quebradas</th>
                        <th class="text-center">Resultado</th>
                        <th class="text-center">Tempo</th>
                        <th>Árbitro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tentativas)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted font-sans-serif">Nenhum registro individual adicionado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tentativas as $t): ?>
                            <tr>
                                <td class="ps-3 font-sans-serif fw-bold text-dark"><?= htmlspecialchars($t['nome_atleta_snapshot']) ?></td>
                                <td><span class="badge bg-dark"><?= strtoupper(htmlspecialchars($t['tecnica'])) ?></span></td>
                                <td><span class="text-muted font-sans-serif"><?= ucfirst(htmlspecialchars($t['material'])) ?></span></td>
                                <td class="text-center fw-bold"><?= $t['qtd_declarada'] ?></td>
                                <td class="text-center fw-bold <?= $t['qtd_quebrada'] > 0 ? 'text-success' : 'text-danger' ?>"><?= $t['qtd_quebrada'] ?></td>
                                <td class="text-center">
                                    <?php if ($t['resultado'] === 'sucesso_total'): ?>
                                        <span class="badge bg-success font-sans-serif">SUCESSO TOTAL</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger font-sans-serif">FALHA / PARCIAL (0)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= $t['tempo_segundos'] ?>s</td>
                                <td class="font-sans-serif text-muted small"><?= htmlspecialchars($t['arbitro_responsavel']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: REGISTRAR TENTATIVA DE TAMESHIWARI -->
<div class="modal fade" id="modalRegistrarQuebramento" tabindex="-1" aria-labelledby="modalRegistrarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="../controllers/sumulaTameshiwariController.php" method="POST">
                <input type="hidden" name="tipo" value="registrar_tameshiwari">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold" id="modalRegistrarLabel">
                        <i class="fas fa-hammer text-warning me-2"></i>Registrar Tentativa de Tameshiwari
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Cronômetro de Apoio de 60s -->
                    <div class="p-3 bg-dark text-white rounded mb-3 text-center">
                        <small class="text-muted text-uppercase fw-bold">Cronômetro de Mesa (Limite 60s)</small>
                        <div id="cronometroTameshiwari" class="display-5 fw-bold font-monospace text-warning py-1">00:00</div>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" id="btnTimerTamStart" class="btn btn-sm btn-success fw-bold">Iniciar 60s</button>
                            <button type="button" id="btnTimerTamPause" class="btn btn-sm btn-warning fw-bold">Pausar</button>
                            <button type="button" id="btnTimerTamReset" class="btn btn-sm btn-secondary">Zerar</button>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Atleta Competidor</label>
                            <select name="id_inscricao" class="form-select select2-modal" required>
                                <option value="">-- Selecione o atleta --</option>
                                <?php foreach ($inscricoes as $ins): ?>
                                    <option value="<?= $ins['id_inscricao'] ?>">
                                        <?= htmlspecialchars($ins['nome_atleta_snapshot']) ?> (<?= htmlspecialchars($ins['dojo_snapshot']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Técnica Oficial Kenshydokan</label>
                            <select name="tecnica" class="form-select" required>
                                <option value="seiken">Seiken (Punho Frontal)</option>
                                <option value="shuto">Shuto (Faca da Mão)</option>
                                <option value="hiji">Hiji / Empi (Cotovelo)</option>
                                <option value="chusoku">Chusoku (Bola do Pé)</option>
                                <option value="sokuto">Sokuto (Faca do Pé)</option>
                                <option value="kakato">Kakato (Calcanhar)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Material Padronizado</label>
                            <select name="material" class="form-select">
                                <option value="madeira">Tábuas de Madeira</option>
                                <option value="telha">Telhas</option>
                                <option value="bloco">Blocos de Concreto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Qtd. Declarada</label>
                            <input type="number" min="1" max="25" name="qtd_declarada" id="inputDeclarada" class="form-control fw-bold" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">Qtd. Quebrada</label>
                            <input type="number" min="0" max="25" name="qtd_quebrada" id="inputQuebrada" class="form-control fw-bold" value="1" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Resultado Oficial da Tentativa</label>
                            <select name="resultado" id="selectResultado" class="form-select fw-bold" required>
                                <option value="sucesso_total" class="text-success">Sucesso Total (Quebra completa)</option>
                                <option value="falha_ou_parcial" class="text-danger">Falha ou Quebra Parcial (Zero Pontos)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tempo Utilizado (Segundos)</label>
                            <input type="number" min="0" max="120" name="tempo_segundos" id="inputTempoSeg" class="form-control" value="15">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Árbitro Responsável</label>
                            <input type="text" name="arbitro_responsavel" class="form-control" value="Árbitro de Tameshiwari" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Observações da Mesa</label>
                            <input type="text" name="observacoes" class="form-control" placeholder="Detalhes técnicos, condição dos blocos...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Gravar Tentativa de Quebramento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Timer de apoio do modal de Tameshiwari
let tempoTam = 0;
let timerTamInterval = null;

document.getElementById('btnTimerTamStart').addEventListener('click', function() {
    clearInterval(timerTamInterval);
    timerTamInterval = setInterval(function() {
        tempoTam++;
        const min = Math.floor(tempoTam / 60);
        const seg = tempoTam % 60;
        document.getElementById('cronometroTameshiwari').innerText = (min < 10 ? '0' : '') + min + ':' + (seg < 10 ? '0' : '') + seg;
        document.getElementById('inputTempoSeg').value = tempoTam;
        if (tempoTam >= 60) {
            alertify.error('TEMPO LIMITE DE 60 SEGUNDOS ATINGIDO!');
            clearInterval(timerTamInterval);
        }
    }, 1000);
});

document.getElementById('btnTimerTamPause').addEventListener('click', function() {
    clearInterval(timerTamInterval);
});

document.getElementById('btnTimerTamReset').addEventListener('click', function() {
    clearInterval(timerTamInterval);
    tempoTam = 0;
    document.getElementById('cronometroTameshiwari').innerText = '00:00';
    document.getElementById('inputTempoSeg').value = 0;
});

// Automatiza regra de zerar se for parcial
document.getElementById('selectResultado').addEventListener('change', function() {
    if (this.value === 'falha_ou_parcial') {
        document.getElementById('inputQuebrada').value = 0;
    }
});
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
