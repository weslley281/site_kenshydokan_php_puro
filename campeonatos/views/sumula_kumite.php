<?php
/**
 * Placar da luta (Kumite) - tela cheia, sem menu, feita para o mesário.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/../models/sumulaKumiteModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";

$idLuta = (int)($_GET['id'] ?? 0);
$luta = (new Luta())->buscarPorId($idLuta);
if (!$luta) {
    echo "Luta não encontrada. <a href='inicio.php'>Voltar</a>";
    exit;
}
$_SESSION['camp_atual'] = (int)$luta['id_campeonato'];

$sumulaModel = new SumulaKumite();
$sumula = $sumulaModel->buscarPorLuta($idLuta);
if (!$sumula) {
    $idSumula = $sumulaModel->criarSumula($idLuta, (int)$luta['id_campeonato']);
    $sumula = $sumulaModel->buscarPorId($idSumula);
}
$categoria = (new Categoria())->buscarPorId((int)$luta['id_categoria']);
$duracao = (int)($categoria['duracao_luta_segundos'] ?? 180) ?: 180;
$terminada = $luta['status'] === 'concluida';
$voltar = "ver_chave.php?id=" . (int)$luta['id_categoria'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Placar - Luta <?= (int)$luta['numero_luta'] ?> | Kenshydokan</title>
    <link rel="icon" href="../img/wkka.jpg" type="image/jpg">
    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/custom.css?v=3">
    <style>
        body { margin: 0; background: #111; font-family: 'Montserrat', 'Roboto', sans-serif; overflow: hidden; }
        .placar { display: grid; grid-template-columns: 1fr 1fr; height: calc(100vh - 64px); }
        .lado { display: flex; flex-direction: column; align-items: center; justify-content: space-between; padding: 24px 16px; text-align: center; }
        .lado.aka { background: #c81e28; color: #fff; }
        .lado.shiro { background: #f4f4f4; color: #111; }
        .lado .cor { font-size: 1.1rem; font-weight: 700; letter-spacing: .15em; opacity: .85; }
        .lado .nome { font-size: clamp(1.4rem, 3vw, 2.4rem); font-weight: 800; line-height: 1.1; }
        .lado .dojo { opacity: .8; }
        .lado .pontos { font-size: clamp(6rem, 18vw, 14rem); font-weight: 900; line-height: 1; }
        .lado .marcas { font-size: 1.05rem; min-height: 1.6em; }
        .botoes { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; width: 100%; max-width: 520px; }
        .botoes .btn { min-height: 72px; font-size: 1.35rem; font-weight: 800; border-radius: 14px; }
        .aka .btn-ponto { background: #fff; color: #c81e28; border: 0; }
        .shiro .btn-ponto { background: #111; color: #fff; border: 0; }
        .btn-penal { grid-column: span 2; min-height: 56px !important; font-size: 1.1rem !important; }
        .aka .btn-penal { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,.7); }
        .shiro .btn-penal { background: transparent; color: #111; border: 2px solid #111; }
        .relogio { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -60%); background: #111; color: #fff;
                   border-radius: 24px; padding: 16px 28px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,.5); z-index: 5; }
        .relogio .tempo { font-size: clamp(3rem, 8vw, 6rem); font-weight: 900; font-variant-numeric: tabular-nums; line-height: 1; }
        .relogio .tempo.acabou { color: #ffcc00; }
        .relogio .btn { min-width: 180px; min-height: 60px; font-size: 1.3rem; font-weight: 800; margin-top: 10px; }
        .relogio .golden { color: #ffcc00; font-weight: 800; display: none; }
        .barra { height: 64px; background: #1b1b1b; color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0 16px; gap: 8px; }
        .barra .btn { min-height: 46px; font-weight: 700; }
        .opcoes-penal .btn { min-height: 64px; font-size: 1.2rem; font-weight: 700; text-align: left; }
        .vencer .btn { min-height: 90px; font-size: 1.5rem; font-weight: 800; }
        @media (max-width: 768px) { .relogio { top: auto; bottom: 74px; transform: translateX(-50%); } }
    </style>
</head>
<body>

<div class="placar">
    <?php foreach (['aka' => 'VERMELHO', 'shiro' => 'BRANCO'] as $lado => $cor): ?>
    <section class="lado <?= $lado ?>">
        <div>
            <div class="cor"><?= $cor ?></div>
            <div class="nome"><?= htmlspecialchars($luta["nome_$lado"] ?? '—') ?></div>
            <div class="dojo"><?= htmlspecialchars($luta["dojo_$lado"] ?? '') ?></div>
        </div>
        <div>
            <div class="pontos" id="pontos-<?= $lado ?>">0</div>
            <div class="marcas" id="marcas-<?= $lado ?>"></div>
        </div>
        <div class="botoes">
            <button type="button" class="btn btn-ponto" onclick="enviar('wazaari','<?= $lado ?>')" id="btn-wazaari-<?= $lado ?>">+ Waza-ari</button>
            <button type="button" class="btn btn-ponto" onclick="enviar('ippon','<?= $lado ?>')" id="btn-ippon-<?= $lado ?>">+ Ippon</button>
            <button type="button" class="btn btn-penal" onclick="abrirPenalidade('<?= $lado ?>')" id="btn-penal-<?= $lado ?>"><i class="fas fa-triangle-exclamation"></i> Penalidade</button>
        </div>
    </section>
    <?php endforeach; ?>
</div>

<div class="relogio">
    <div class="golden" id="golden">PONTO DE OURO</div>
    <div class="tempo" id="tempo">0:00</div>
    <button type="button" class="btn btn-success" id="btnRelogio" onclick="alternarRelogio()"><i class="fas fa-play"></i> Iniciar</button>
</div>

<div class="barra">
    <a href="<?= $voltar ?>" class="btn btn-outline-light" id="btnSair"><i class="fas fa-arrow-left"></i> Sair</a>
    <span class="d-none d-md-inline"><?= htmlspecialchars($luta['categoria_nome'] ?? '') ?> · Luta <?= (int)$luta['numero_luta'] ?></span>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-light" id="btnDesfazer" onclick="desfazer()" disabled><i class="fas fa-rotate-left"></i> Desfazer último</button>
        <button type="button" class="btn btn-warning" id="btnTerminar" onclick="perguntarVencedor()"><i class="fas fa-flag-checkered"></i> Terminar luta</button>
    </div>
</div>

<!-- Penalidades -->
<div class="modal fade" id="modalPenal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Penalidade para <span id="penalLado"></span></h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button></div>
            <div class="modal-body d-grid gap-2 opcoes-penal">
                <button type="button" class="btn btn-outline-dark" onclick="penalidade('shido')"><strong>Shido</strong> — aviso leve</button>
                <button type="button" class="btn btn-outline-warning text-dark" onclick="penalidade('chui')"><strong>Chui</strong> — aviso (2 avisos = ponto para o outro)</button>
                <button type="button" class="btn btn-outline-secondary" onclick="penalidade('saida')"><strong>Saiu da área</strong> (2 saídas = ponto para o outro)</button>
                <button type="button" class="btn btn-outline-danger" onclick="if(confirm('Desclassificar este atleta?')) penalidade('hansoku_make')"><strong>Desclassificar</strong></button>
            </div>
        </div>
    </div>
</div>

<!-- Quem venceu? -->
<div class="modal fade" id="modalVencedor" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="../controllers/sumulaKumiteController.php">
                <input type="hidden" name="tipo" value="finalizar_luta">
                <input type="hidden" name="id_luta" value="<?= $idLuta ?>">
                <input type="hidden" name="forma_vitoria" id="formaVitoria" value="pontos">
                <div class="modal-header"><h5 class="modal-title fs-3" id="tituloVencedor">Quem venceu?</h5></div>
                <div class="modal-body">
                    <p class="text-muted" id="textoVencedor">Escolha o vencedor. Se a luta empatou, é a decisão dos árbitros.</p>
                    <div class="row g-3 vencer">
                        <div class="col-6"><button type="submit" name="vencedor_lado" value="aka" class="btn btn-danger w-100" id="venceAka"><?= htmlspecialchars($luta['nome_aka'] ?? 'Vermelho') ?></button></div>
                        <div class="col-6"><button type="submit" name="vencedor_lado" value="shiro" class="btn btn-light border border-dark w-100" id="venceShiro"><?= htmlspecialchars($luta['nome_shiro'] ?? 'Branco') ?></button></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-warning" id="btnGolden" onclick="iniciarGolden()">Empate: Ponto de ouro</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnVoltarPlacar">Voltar ao placar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Luta terminada automaticamente -->
<div class="modal fade" id="modalFim" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <div class="fs-1">🏆</div>
            <h2 class="h3" id="fimTitulo">Luta terminada</h2>
            <p class="text-muted" id="fimMotivo"></p>
            <a href="<?= $voltar ?>" class="btn btn-danger btn-lg">Ver próximas lutas</a>
        </div>
    </div>
</div>

<script src="../libs/bootstrap/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
var ID_LUTA = <?= $idLuta ?>;
var DURACAO = <?= $duracao ?>;
var NOMES = { aka: <?= json_encode($luta['nome_aka'] ?? 'Vermelho') ?>, shiro: <?= json_encode($luta['nome_shiro'] ?? 'Branco') ?> };
var sumula = <?= json_encode($sumula) ?>;
var terminada = <?= $terminada ? 'true' : 'false' ?>;

var segundos = parseInt(sumula.tempo_decorrido_segundos || 0, 10);
var golden = parseInt(sumula.golden_score_ativo || 0, 10) === 1;
var rodando = false, timer = null;
var historico = []; // toques que podem ser desfeitos
var ladoPenal = 'aka';

function fmt(s) { s = Math.max(0, s); return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0'); }

function desenharRelogio() {
    var el = document.getElementById('tempo');
    if (golden) {
        el.textContent = fmt(segundos - DURACAO);
        el.classList.remove('acabou');
    } else {
        var resta = DURACAO - segundos;
        el.textContent = fmt(resta);
        el.classList.toggle('acabou', resta <= 0);
    }
    document.getElementById('golden').style.display = golden ? 'block' : 'none';
}

function desenharPlacar() {
    ['aka', 'shiro'].forEach(function (l) {
        var p = parseFloat(sumula['pontos_' + l] || 0);
        document.getElementById('pontos-' + l).textContent = (p % 1 === 0) ? p.toFixed(0) : p.toFixed(1).replace('.', ',');
        var m = [];
        if (+sumula['wazaari_' + l]) m.push(sumula['wazaari_' + l] + ' waza-ari');
        if (+sumula['ippon_' + l]) m.push('IPPON');
        if (+sumula['shido_' + l]) m.push(sumula['shido_' + l] + ' shido');
        if (+sumula['chui_' + l]) m.push(sumula['chui_' + l] + ' chui');
        if (+sumula['saidas_' + l]) m.push(sumula['saidas_' + l] + ' saída(s)');
        if (+sumula['hansoku_make_' + l]) m.push('DESCLASSIFICADO');
        document.getElementById('marcas-' + l).textContent = m.join(' · ');
    });
    document.getElementById('btnDesfazer').disabled = historico.length === 0 || terminada;
}

function alternarRelogio() {
    if (terminada) return;
    rodando = !rodando;
    var b = document.getElementById('btnRelogio');
    if (rodando) {
        b.className = 'btn btn-warning';
        b.innerHTML = '<i class="fas fa-pause"></i> Pausar';
        timer = setInterval(function () {
            segundos++;
            desenharRelogio();
            if (segundos % 10 === 0) enviar('tempo', 'geral', true);
            if (!golden && segundos >= DURACAO) {
                pararRelogio();
                perguntarVencedor('Acabou o tempo!');
            }
        }, 1000);
    } else {
        pararRelogio();
        enviar('tempo', 'geral', true);
    }
}

function pararRelogio() {
    rodando = false;
    clearInterval(timer);
    var b = document.getElementById('btnRelogio');
    b.className = 'btn btn-success';
    b.innerHTML = '<i class="fas fa-play"></i> Iniciar';
}

function enviar(tipo, lado, silencioso, extra) {
    if (terminada) return;
    var dados = { id_luta: ID_LUTA, tipo: tipo, lado: lado, segundo_luta: segundos };
    if (extra) Object.assign(dados, extra);
    $.post('../controllers/sumulaKumiteController.php?acao=evento', dados, null, 'json')
        .done(function (r) {
            if (!r.success) { alert(r.error || 'Não foi possível salvar.'); return; }
            sumula = r.sumula;
            if (['wazaari', 'ippon', 'shido', 'chui', 'saida', 'hansoku_make'].indexOf(tipo) >= 0) {
                historico.push({ tipo: tipo, lado: lado });
            }
            desenharPlacar();
            if (r.avaliacao && r.avaliacao.finalizada) {
                terminada = true;
                pararRelogio();
                document.getElementById('fimTitulo').textContent = 'Venceu: ' + NOMES[r.avaliacao.vencedor_lado];
                document.getElementById('fimMotivo').textContent = r.avaliacao.motivo || '';
                new bootstrap.Modal(document.getElementById('modalFim')).show();
            }
        })
        .fail(function () { if (!silencioso) alert('Sem conexão. O toque não foi salvo, tente de novo.'); });
}

function desfazer() {
    var ultimo = historico.pop();
    if (!ultimo) return;
    enviar('desfazer', ultimo.lado, false, { desfazer_tipo: ultimo.tipo });
}

function abrirPenalidade(lado) {
    ladoPenal = lado;
    document.getElementById('penalLado').textContent = NOMES[lado];
    new bootstrap.Modal(document.getElementById('modalPenal')).show();
}

function penalidade(tipo) {
    bootstrap.Modal.getInstance(document.getElementById('modalPenal')).hide();
    enviar(tipo, ladoPenal);
}

function perguntarVencedor(titulo) {
    pararRelogio();
    document.getElementById('tituloVencedor').textContent = (titulo ? titulo + ' ' : '') + 'Quem venceu?';
    var a = parseFloat(sumula.pontos_aka || 0), s = parseFloat(sumula.pontos_shiro || 0);
    document.getElementById('formaVitoria').value = (a === s) ? 'hantei' : 'pontos';
    document.getElementById('textoVencedor').textContent = (a === s)
        ? 'Empate no placar. Escolha pela decisão dos árbitros ou vá para o ponto de ouro.'
        : 'Quem tem mais pontos: ' + (a > s ? NOMES.aka : NOMES.shiro) + '.';
    new bootstrap.Modal(document.getElementById('modalVencedor')).show();
}

function iniciarGolden() {
    bootstrap.Modal.getInstance(document.getElementById('modalVencedor')).hide();
    golden = true;
    if (segundos < DURACAO) segundos = DURACAO;
    enviar('golden_score', 'geral');
    desenharRelogio();
}

desenharRelogio();
desenharPlacar();
if (terminada) {
    document.querySelectorAll('.botoes .btn, #btnRelogio, #btnTerminar').forEach(function (b) { b.disabled = true; });
    document.getElementById('fimTitulo').textContent = 'Esta luta já terminou';
    document.getElementById('fimMotivo').textContent = <?= json_encode($luta['nome_vencedor'] ? 'Vencedor: ' . $luta['nome_vencedor'] : '') ?>;
    new bootstrap.Modal(document.getElementById('modalFim')).show();
}

// Atalhos de teclado: espaço = iniciar/pausar
document.addEventListener('keydown', function (e) {
    if (e.code === 'Space' && !document.querySelector('.modal.show')) { e.preventDefault(); alternarRelogio(); }
});
</script>
</body>
</html>
