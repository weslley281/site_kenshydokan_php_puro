<?php
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/sumulaTameshiwariModel.php";

$idCategoria = (int)($_GET['id'] ?? ($_GET['id_categoria'] ?? 0));
$categoria = (new Categoria())->buscarPorId($idCategoria);
$campAtualId = $categoria ? (int)$categoria['id_campeonato'] : 0;

$pageTitle = 'Quebramento | Kenshydokan';
include_once __DIR__ . "/menu.php";

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = (int)$categoria['id_campeonato'];
$inscricoes = (new Inscricao())->buscarPorCategoria($idCategoria);
$tamModel = new SumulaTameshiwari();
$tentativas = $tamModel->buscarPorCategoria($idCategoria);
$ranking = $tamModel->obterRanking($idCategoria);
$medalhas = ['🥇', '🥈', '🥉'];
$tecnicas = ['seiken' => 'Seiken (soco)', 'shuto' => 'Shuto (mão em faca)', 'hiji' => 'Hiji (cotovelo)', 'chusoku_sokuto_kakato' => 'Pé (chusoku / sokuto)'];
?>

<div class="page-head">
    <div>
        <h1>Quebramento: <?= htmlspecialchars($categoria['nome']) ?></h1>
        <p>Escolha o atleta e a técnica, informe quantas peças ele vai quebrar e marque o resultado. Quebra parcial vale <strong>zero</strong>.</p>
    </div>
    <a href="chaves.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-pen text-danger me-2"></i>Registrar tentativa</span>
                <span class="d-flex align-items-center gap-2">
                    <strong class="fs-4 font-monospace" id="relogioTam">60</strong><small class="text-muted">s</small>
                    <button type="button" class="btn btn-sm btn-success" id="btnRelogioTam" onclick="relogioTam()"><i class="fas fa-play"></i></button>
                </span>
            </div>
            <div class="card-body">
                <?php if (empty($inscricoes)): ?>
                    <p class="text-muted mb-0">Nenhum atleta liberado nesta categoria.</p>
                <?php else: ?>
                <form action="../controllers/sumulaTameshiwariController.php" method="POST" id="formTam">
                    <input type="hidden" name="tipo" value="registrar_tameshiwari">
                    <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                    <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">
                    <input type="hidden" name="material" value="madeira">
                    <input type="hidden" name="arbitro_responsavel" value="Mesa">
                    <input type="hidden" name="tempo_segundos" id="tempoSeg" value="0">
                    <input type="hidden" name="qtd_quebrada" id="qtdQuebrada" value="0">

                    <label class="form-label" for="tamAtleta">1. Atleta</label>
                    <select name="id_inscricao" id="tamAtleta" class="form-select form-select-lg mb-3" required>
                        <option value="">Escolha...</option>
                        <?php foreach ($inscricoes as $ins): ?>
                            <option value="<?= $ins['id_inscricao'] ?>"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-label">2. Técnica</div>
                    <div class="row g-2 mb-3">
                        <?php $primeira = true; foreach ($tecnicas as $valor => $texto): ?>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="tecnica" id="tec-<?= $valor ?>" value="<?= $valor ?>" <?= $primeira ? 'checked' : '' ?>>
                                <label class="btn btn-outline-dark btn-lg w-100" for="tec-<?= $valor ?>"><?= $texto ?></label>
                            </div>
                        <?php $primeira = false; endforeach; ?>
                    </div>

                    <label class="form-label" for="qtdDeclarada">3. Quantas peças vai quebrar?</label>
                    <input type="number" min="1" max="25" name="qtd_declarada" id="qtdDeclarada" class="form-control form-control-lg text-center fw-bold fs-3 mb-4" value="1" required style="max-width:160px">

                    <div class="form-label">4. Resultado</div>
                    <div class="row g-2">
                        <div class="col-6"><button type="submit" name="resultado" value="sucesso_total" class="btn btn-success btn-lg w-100 py-3" id="btnQuebrou"><i class="fas fa-check"></i> Quebrou tudo</button></div>
                        <div class="col-6"><button type="submit" name="resultado" value="falha_ou_parcial" class="btn btn-outline-danger btn-lg w-100 py-3" id="btnFalhou"><i class="fas fa-xmark"></i> Não quebrou tudo</button></div>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header py-3"><i class="fas fa-trophy text-warning me-2"></i>Classificação</div>
            <?php if (empty($ranking)): ?>
                <div class="empty"><i class="fas fa-list-ol"></i>Ainda sem tentativas.</div>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                <?php foreach ($ranking as $i => $r): ?>
                    <li class="list-group-item d-flex align-items-center gap-3 py-3">
                        <span class="fs-4" style="width:2rem"><?= $medalhas[$i] ?? ($i + 1) . 'º' ?></span>
                        <strong class="flex-fill"><?= htmlspecialchars($r['nome_atleta_snapshot']) ?></strong>
                        <span class="fs-4 fw-bold"><?= (int)$r['total_quebrado'] ?> <small class="fs-6 text-muted">peças</small></span>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (!empty($tentativas)): ?>
        <details class="card mt-3">
            <summary class="card-header py-3">Ver todas as tentativas (<?= count($tentativas) ?>)</summary>
            <ul class="list-group list-group-flush">
            <?php foreach ($tentativas as $t): $ok = $t['resultado'] === 'sucesso_total'; ?>
                <li class="list-group-item d-flex justify-content-between">
                    <span><?= htmlspecialchars($t['nome_atleta_snapshot']) ?> · <?= htmlspecialchars(ucfirst($t['tecnica'])) ?></span>
                    <span class="<?= $ok ? 'text-success' : 'text-danger' ?> fw-bold"><?= $ok ? (int)$t['qtd_quebrada'] . ' peças' : 'zero' ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        </details>
        <?php endif; ?>
    </div>
</div>

<script>
var formTam = document.getElementById('formTam');
if (formTam) {
    formTam.addEventListener('submit', function (e) {
        var sucesso = e.submitter && e.submitter.value === 'sucesso_total';
        document.getElementById('qtdQuebrada').value = sucesso ? document.getElementById('qtdDeclarada').value : 0;
        document.getElementById('tempoSeg').value = usados;
    });
}

var usados = 0, timerTam = null;
function relogioTam() {
    var b = document.getElementById('btnRelogioTam');
    if (timerTam) { clearInterval(timerTam); timerTam = null; b.innerHTML = '<i class="fas fa-play"></i>'; return; }
    usados = 0;
    b.innerHTML = '<i class="fas fa-stop"></i>';
    timerTam = setInterval(function () {
        usados++;
        var resta = 60 - usados;
        var el = document.getElementById('relogioTam');
        el.textContent = Math.max(0, resta);
        el.classList.toggle('text-danger', resta <= 10);
        if (resta <= 0) { clearInterval(timerTam); timerTam = null; b.innerHTML = '<i class="fas fa-play"></i>'; alert('Acabou o tempo (60 segundos)!'); }
    }, 1000);
}
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
