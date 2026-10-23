<?php
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/lutaModel.php";

$idCategoria = (int)($_GET['id'] ?? 0);
$categoria = (new Categoria())->buscarPorId($idCategoria);
$campAtualId = $categoria ? (int)$categoria['id_campeonato'] : 0; // informa o campeonato ao menu

$pageTitle = 'Lutas da categoria | Kenshydokan';
include_once __DIR__ . "/menu.php";

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$chave = (new Chave())->buscarPorCategoria($idCategoria);
$lutas = $chave ? (new Luta())->buscarPorChave((int)$chave['id_chave']) : [];

$porRodada = [];
foreach ($lutas as $l) {
    $porRodada[(int)$l['rodada']][] = $l;
}
$totalRodadas = $chave ? (int)$chave['total_rodadas'] : 0;

function nomeRodada(int $r, int $total): string {
    if ($r === $total) return 'Final';
    if ($r === $total - 1) return 'Semifinal';
    if ($r === $total - 2) return 'Quartas de final';
    return "{$r}ª rodada";
}
?>

<div class="page-head">
    <div>
        <h1><?= htmlspecialchars($categoria['nome']) ?></h1>
        <p>Clique em <strong>Começar luta</strong> para abrir o placar.</p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($chave): ?>
            <a href="../controllers/relatorioController.php?id_campeonato=<?= $categoria['id_campeonato'] ?>&tipo=chave&id_categoria=<?= $idCategoria ?>" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-print"></i> Imprimir</a>
        <?php endif; ?>
        <a href="chaves.php?id=<?= $categoria['id_campeonato'] ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
</div>

<?php if (!$chave): ?>
    <div class="card"><div class="empty">
        <i class="fas fa-shuffle"></i>As lutas desta categoria ainda não foram sorteadas.
        <form method="POST" action="../controllers/chaveamentoController.php" class="mt-3"><?= csrfCampo() ?>
            <input type="hidden" name="tipo" value="gerar_chave">
            <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">
            <button type="submit" class="btn btn-danger btn-lg"><i class="fas fa-shuffle"></i> Sortear lutas</button>
        </form>
    </div></div>
<?php else: ?>
    <?php foreach ($porRodada as $rodada => $lista): ?>
        <h2 class="h5 mt-4 mb-2"><?= nomeRodada($rodada, $totalRodadas) ?></h2>
        <div class="card">
            <ul class="list-group list-group-flush">
            <?php foreach ($lista as $luta):
                $temDois = !empty($luta['id_inscricao_aka']) && !empty($luta['id_inscricao_shiro']);
                $venceuAka = $luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_aka'];
                $venceuShiro = $luta['id_vencedor'] && $luta['id_vencedor'] == $luta['id_inscricao_shiro'];
                $nomeShiro = $luta['nome_shiro'] ?? (!empty($luta['id_inscricao_aka']) && $luta['status'] === 'concluida' ? 'Passa direto (sem adversário)' : 'A definir');
            ?>
                <li class="list-group-item py-3 d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <span class="text-muted fw-bold">Luta <?= (int)$luta['numero_luta'] ?></span>
                        <span class="badge bg-danger fs-6 <?= $venceuAka ? '' : ($luta['id_vencedor'] ? 'opacity-50' : '') ?>">
                            <?= $venceuAka ? '🏆 ' : '' ?><?= htmlspecialchars($luta['nome_aka'] ?? 'A definir') ?>
                        </span>
                        <span class="text-muted">x</span>
                        <span class="badge bg-light text-dark border fs-6 <?= $venceuShiro ? '' : ($luta['id_vencedor'] ? 'opacity-50' : '') ?>">
                            <?= $venceuShiro ? '🏆 ' : '' ?><?= htmlspecialchars($nomeShiro) ?>
                        </span>
                    </div>
                    <div>
                        <?php if ($luta['status'] === 'concluida'): ?>
                            <span class="badge bg-success fs-6"><i class="fas fa-check"></i> Terminada</span>
                        <?php elseif ($temDois): ?>
                            <a href="sumula_kumite.php?id=<?= $luta['id_luta'] ?>" class="btn btn-danger btn-lg">
                                <i class="fas fa-play"></i> <?= $luta['status'] === 'em_andamento' ? 'Continuar luta' : 'Começar luta' ?>
                            </a>
                            <?php if (usuarioTemPerfil(['mesario'])): ?>
                                <button type="button" class="btn btn-outline-secondary btn-lg btn-sem-luta"
                                        data-id="<?= (int)$luta['id_luta'] ?>"
                                        data-num="<?= (int)$luta['numero_luta'] ?>"
                                        data-aka="<?= htmlspecialchars($luta['nome_aka'] ?? 'AKA', ENT_QUOTES, 'UTF-8') ?>"
                                        data-shiro="<?= htmlspecialchars($luta['nome_shiro'] ?? 'SHIRO', ENT_QUOTES, 'UTF-8') ?>"
                                        data-bs-toggle="modal" data-bs-target="#modalSemLuta">
                                    <i class="fas fa-user-xmark"></i> Não houve luta
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">Aguardando lutas anteriores</span>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Modal: luta encerrada sem combate (W.O., desistência, desclassificação) -->
<div class="modal fade" id="modalSemLuta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/lutaController.php" onsubmit="return confirm('Confirmar? O vencedor passa direto para a próxima fase.');"><?= csrfCampo() ?>
                <input type="hidden" name="tipo" value="resultado_sem_luta">
                <input type="hidden" name="id_luta" id="semLutaId">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fas fa-user-xmark text-danger me-2"></i>Luta <span id="semLutaNum"></span>: não houve luta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold mb-2">O que aconteceu?</p>
                    <div class="d-grid gap-2 mb-3">
                        <input type="radio" class="btn-check" name="forma_vitoria" id="formaWo" value="wo" required checked>
                        <label class="btn btn-outline-dark btn-lg text-start" for="formaWo"><strong>W.O.</strong> - um atleta não apareceu</label>
                        <input type="radio" class="btn-check" name="forma_vitoria" id="formaDesist" value="desistencia">
                        <label class="btn btn-outline-dark btn-lg text-start" for="formaDesist"><strong>Desistência</strong> - um atleta desistiu</label>
                        <input type="radio" class="btn-check" name="forma_vitoria" id="formaDesc" value="desclassificacao">
                        <label class="btn btn-outline-dark btn-lg text-start" for="formaDesc"><strong>Desclassificação</strong> - um atleta foi desclassificado</label>
                    </div>

                    <p class="fw-bold mb-2">Quem passa para a próxima fase?</p>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="vencedor_lado" id="vencAka" value="aka" required>
                            <label class="btn btn-outline-danger btn-lg w-100" for="vencAka"><small class="d-block">Vermelho</small><span id="semLutaAka"></span></label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="vencedor_lado" id="vencShiro" value="shiro">
                            <label class="btn btn-outline-dark btn-lg w-100" for="vencShiro"><small class="d-block">Branco</small><span id="semLutaShiro"></span></label>
                        </div>
                    </div>

                    <label for="semLutaMotivo" class="form-label fw-bold">Motivo</label>
                    <input type="text" class="form-control form-control-lg" id="semLutaMotivo" name="motivo" required maxlength="255" placeholder="Ex.: não compareceu após 3 chamadas">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Confirmar resultado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.btn-sem-luta').forEach(function (botao) {
    botao.addEventListener('click', function () {
        document.getElementById('semLutaId').value = botao.dataset.id;
        document.getElementById('semLutaNum').textContent = botao.dataset.num;
        document.getElementById('semLutaAka').textContent = botao.dataset.aka;
        document.getElementById('semLutaShiro').textContent = botao.dataset.shiro;
        document.getElementById('vencAka').checked = false;
        document.getElementById('vencShiro').checked = false;
        document.getElementById('semLutaMotivo').value = '';
    });
});
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
