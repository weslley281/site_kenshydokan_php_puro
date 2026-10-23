<?php
$pageTitle = 'Pesagem | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = $cid;
$inscricoes = (new Inscricao())->buscarPorCampeonato($idCampeonato);
$categorias = (new Categoria())->buscarPorCampeonato($idCampeonato);

// Só aparecem aqui os atletas de categorias que exigem pesagem
$faltam = [];
$pesados = [];
foreach ($inscricoes as $ins) {
    if ((int)$ins['exige_pesagem'] !== 1) continue;
    if ($ins['status_elegibilidade'] === 'pendente') $faltam[] = $ins;
    else $pesados[] = $ins;
}

function textoLimite(array $ins): string {
    $min = $ins['peso_min'] ? number_format($ins['peso_min'], 1, ',', '') . ' kg' : null;
    $max = $ins['peso_max'] ? number_format($ins['peso_max'] + $ins['tolerancia_peso_kg'], 1, ',', '') . ' kg' : null;
    if ($min && $max) return "de $min até $max";
    if ($max) return "até $max";
    if ($min) return "a partir de $min";
    return 'peso livre';
}
?>

<div class="page-head">
    <div>
        <h1>4. Pesagem</h1>
        <p>Digite o peso que apareceu na balança. O sistema diz na hora se o atleta está <strong class="text-success">no peso</strong> ou <strong class="text-danger">fora do peso</strong>.</p>
    </div>
    <a href="chaves.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary">Próxima etapa: Lutas <i class="fas fa-arrow-right"></i></a>
</div>

<div class="card mb-4">
    <div class="card-header py-3"><i class="fas fa-scale-balanced text-danger me-2"></i>Faltam pesar (<?= count($faltam) ?>)</div>
    <?php if (empty($faltam)): ?>
        <div class="empty"><i class="fas fa-circle-check text-success"></i>Todos os atletas já foram pesados.</div>
    <?php else: ?>
        <ul class="list-group list-group-flush">
        <?php foreach ($faltam as $ins): ?>
            <li class="list-group-item py-3">
                <form method="POST" action="../controllers/pesagemController.php" class="row g-3 align-items-center form-pesagem"
                      data-min="<?= (float)$ins['peso_min'] ?><?= csrfCampo() ?>" data-max="<?= $ins['peso_max'] ? (float)$ins['peso_max'] + (float)$ins['tolerancia_peso_kg'] : 0 ?>">
                    <input type="hidden" name="tipo" value="registrar_pesagem">
                    <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                    <input type="hidden" name="id_inscricao" value="<?= $ins['id_inscricao'] ?>">
                    <div class="col-md-5">
                        <strong class="fs-5"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></strong><br>
                        <span class="text-muted"><?= htmlspecialchars($ins['categoria_nome']) ?> · <?= textoLimite($ins) ?></span>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group input-group-lg">
                            <input type="number" step="0.05" min="10" max="250" name="peso_aferido" class="form-control text-center fw-bold campo-peso" placeholder="0,0" required aria-label="Peso em kg">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-2 text-center">
                        <span class="resultado-peso fw-bold fs-5 text-muted">—</span>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-danger btn-lg w-100">Salvar</button>
                    </div>
                </form>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php if (!empty($pesados)): ?>
<div class="card">
    <div class="card-header py-3">Já pesados (<?= count($pesados) ?>)</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Atleta</th><th>Categoria</th><th>Resultado</th><th class="text-end"></th></tr></thead>
            <tbody>
            <?php foreach ($pesados as $ins): $ok = $ins['status_elegibilidade'] === 'aprovada'; ?>
                <tr>
                    <td><strong><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></strong></td>
                    <td><?= htmlspecialchars($ins['categoria_nome']) ?></td>
                    <td><span class="badge fs-6 <?= $ok ? 'bg-success' : 'bg-danger' ?>"><?= $ok ? 'NO PESO' : 'FORA DO PESO' ?></span></td>
                    <td class="text-end">
                        <?php if (!$ok): ?>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="trocarCategoria(<?= $ins['id_inscricao'] ?>, '<?= htmlspecialchars(addslashes($ins['nome_atleta_snapshot']), ENT_QUOTES) ?>')">
                                <i class="fas fa-right-left"></i> Trocar de categoria
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Trocar de categoria (atleta fora do peso) -->
<div class="modal fade" id="modalTrocar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="../controllers/pesagemController.php"><?= csrfCampo() ?>
                <input type="hidden" name="tipo" value="remanejar_categoria">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_inscricao" id="trocarId">
                <div class="modal-header"><h5 class="modal-title">Trocar de categoria</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button></div>
                <div class="modal-body">
                    <p>Atleta: <strong id="trocarNome"></strong></p>
                    <label class="form-label" for="trocarCat">Nova categoria</label>
                    <select class="form-select mb-3" id="trocarCat" name="nova_categoria_id" required>
                        <option value="">Escolha...</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label class="form-label" for="trocarMotivo">Motivo</label>
                    <input type="text" class="form-control" id="trocarMotivo" name="justificativa" value="Fora do peso na pesagem" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Trocar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.form-pesagem').forEach(function (form) {
    var campo = form.querySelector('.campo-peso');
    var saida = form.querySelector('.resultado-peso');
    var min = parseFloat(form.dataset.min) || 0;
    var max = parseFloat(form.dataset.max) || 0;
    campo.addEventListener('input', function () {
        var p = parseFloat(campo.value);
        if (isNaN(p)) { saida.textContent = '—'; saida.className = 'resultado-peso fw-bold fs-5 text-muted'; return; }
        var ok = p >= min && (max === 0 || p <= max);
        saida.textContent = ok ? 'NO PESO' : 'FORA DO PESO';
        saida.className = 'resultado-peso fw-bold fs-5 ' + (ok ? 'text-success' : 'text-danger');
    });
});

function trocarCategoria(id, nome) {
    document.getElementById('trocarId').value = id;
    document.getElementById('trocarNome').textContent = nome;
    new bootstrap.Modal(document.getElementById('modalTrocar')).show();
}
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
