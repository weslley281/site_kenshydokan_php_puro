<?php
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/sumulaKataModel.php";

$idCategoria = (int)($_GET['id'] ?? ($_GET['id_categoria'] ?? 0));
$categoria = (new Categoria())->buscarPorId($idCategoria);
$campAtualId = $categoria ? (int)$categoria['id_campeonato'] : 0;

$pageTitle = 'Notas de Kata | Kenshydokan';
include_once __DIR__ . "/menu.php";

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = (int)$categoria['id_campeonato'];
$inscricoes = (new Inscricao())->buscarPorCategoria($idCategoria);
$kataModel = new SumulaKata();
$ranking = $kataModel->obterRanking($idCategoria, 1);
$rankingDesempate = $kataModel->obterRanking($idCategoria, 2);

$jaApresentaram = array_column($ranking, 'id_inscricao');
$faltam = array_filter($inscricoes, fn($i) => !in_array($i['id_inscricao'], $jaApresentaram));
$medalhas = ['🥇', '🥈', '🥉'];

function campoNotas(): void {
    for ($i = 1; $i <= 5; $i++): ?>
        <div class="col">
            <label class="form-label text-center w-100" for="nota<?= $i ?>">Árbitro <?= $i ?><?= $i > 3 ? ' <small class="text-muted">(se tiver)</small>' : '' ?></label>
            <input type="hidden" name="arbitros[<?= $i ?>]" value="Árbitro <?= $i ?>">
            <input type="number" step="0.1" min="0" max="10" name="notas[<?= $i ?>]" id="nota<?= $i ?>"
                   class="form-control form-control-lg text-center fw-bold fs-3 campo-nota" placeholder="—" <?= $i <= 3 ? 'required' : '' ?>>
        </div>
    <?php endfor;
}
?>

<div class="page-head">
    <div>
        <h1>Kata: <?= htmlspecialchars($categoria['nome']) ?></h1>
        <p>Escolha o atleta, digite as notas dos árbitros (0 a 10) e clique em <strong>Salvar notas</strong>. A média aparece sozinha.</p>
    </div>
    <a href="chaves.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header py-3"><i class="fas fa-pen text-danger me-2"></i>Lançar notas</div>
            <div class="card-body">
                <?php if (empty($inscricoes)): ?>
                    <p class="text-muted mb-0">Nenhum atleta liberado nesta categoria.</p>
                <?php else: ?>
                <form action="../controllers/sumulaKataController.php" method="POST" class="form-notas">
                    <input type="hidden" name="tipo" value="lancar_notas">
                    <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                    <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label" for="kataAtleta">Atleta</label>
                            <select name="id_inscricao" id="kataAtleta" class="form-select form-select-lg" required>
                                <option value="">Escolha...</option>
                                <?php foreach ($faltam as $ins): ?>
                                    <option value="<?= $ins['id_inscricao'] ?>"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></option>
                                <?php endforeach; ?>
                                <?php if (count($faltam) < count($inscricoes)): ?>
                                    <optgroup label="Já apresentaram (corrigir nota)">
                                    <?php foreach ($inscricoes as $ins): if (in_array($ins['id_inscricao'], $jaApresentaram)): ?>
                                        <option value="<?= $ins['id_inscricao'] ?>"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></option>
                                    <?php endif; endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="kataNome">Nome do kata</label>
                            <input type="text" name="kata_nome" id="kataNome" class="form-control form-control-lg" list="listaKatas" placeholder="Ex.: Pinan Sono Ichi" required>
                        </div>
                    </div>

                    <div class="row row-cols-3 row-cols-md-5 g-2 mb-3"><?php campoNotas(); ?></div>

                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div class="fs-4">Média: <strong class="media-ao-vivo text-danger">—</strong></div>
                        <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-check"></i> Salvar notas</button>
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
                <div class="empty"><i class="fas fa-list-ol"></i>Ainda sem notas.</div>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                <?php foreach ($ranking as $i => $r): ?>
                    <li class="list-group-item d-flex align-items-center gap-3 py-3">
                        <span class="fs-4" style="width:2rem"><?= $medalhas[$i] ?? ($i + 1) . 'º' ?></span>
                        <div class="flex-fill">
                            <strong><?= htmlspecialchars($r['nome_atleta_snapshot']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($r['kata_nome'] ?: '') ?></small>
                        </div>
                        <span class="fs-4 fw-bold"><?= number_format((float)$r['media_nota'], 2, ',', '') ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php if (count($ranking) >= 2): ?>
        <details class="card mt-3">
            <summary class="card-header py-3">Deu empate? Lançar desempate</summary>
            <div class="card-body">
                <p class="text-muted">No desempate o atleta apresenta um <strong>kata diferente</strong>.</p>
                <form action="../controllers/sumulaKataController.php" method="POST" class="form-notas">
                    <input type="hidden" name="tipo" value="desempate">
                    <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                    <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">
                    <select name="id_inscricao" class="form-select mb-2" required aria-label="Atleta empatado">
                        <option value="">Atleta empatado...</option>
                        <?php foreach ($ranking as $rk): ?>
                            <option value="<?= $rk['id_inscricao'] ?>"><?= htmlspecialchars($rk['nome_atleta_snapshot']) ?> (<?= number_format((float)$rk['media_nota'], 2, ',', '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="kata_nome" class="form-control mb-2" list="listaKatas" placeholder="Novo kata" required aria-label="Novo kata">
                    <div class="row row-cols-3 g-2 mb-2"><?php campoNotas(); ?></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Média: <strong class="media-ao-vivo">—</strong></span>
                        <button type="submit" class="btn btn-danger">Salvar desempate</button>
                    </div>
                </form>
                <?php if (!empty($rankingDesempate)): ?>
                    <hr>
                    <?php foreach ($rankingDesempate as $i => $rd): ?>
                        <div class="d-flex justify-content-between"><span><?= $i + 1 ?>º <?= htmlspecialchars($rd['nome_atleta_snapshot']) ?></span><strong><?= number_format((float)$rd['media_nota'], 2, ',', '') ?></strong></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </details>
        <?php endif; ?>
    </div>
</div>

<datalist id="listaKatas">
    <option value="Taikyoku Sono Ichi"><option value="Taikyoku Sono Ni"><option value="Taikyoku Sono San">
    <option value="Pinan Sono Ichi"><option value="Pinan Sono Ni"><option value="Pinan Sono San"><option value="Pinan Sono Yon"><option value="Pinan Sono Go">
    <option value="Bassai Dai"><option value="Kanku Dai"><option value="Empi"><option value="Seienchin"><option value="Garyu"><option value="Koryu Kenshydokan">
</datalist>

<script>
document.querySelectorAll('.form-notas').forEach(function (form) {
    var saida = form.querySelector('.media-ao-vivo');
    form.querySelectorAll('.campo-nota').forEach(function (c) {
        c.addEventListener('input', function () {
            var notas = [];
            form.querySelectorAll('.campo-nota').forEach(function (x) { var v = parseFloat(x.value); if (!isNaN(v)) notas.push(v); });
            saida.textContent = notas.length ? (notas.reduce(function (a, b) { return a + b; }, 0) / notas.length).toFixed(2).replace('.', ',') : '—';
        });
    });
});
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
