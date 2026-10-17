<?php
/**
 * Placar da luta (Kumite) - tela cheia, sem menu, feita para o mesário.
 */
require_once __DIR__ . "/../controllers/authHelper.php";
exigirLogin('login.php');
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
$sumula = $sumulaModel->buscarOuCriarInicial($idLuta);
$categoria = (new Categoria())->buscarPorId((int)$luta['id_categoria']);
$duracao = (int)($categoria['duracao_luta_segundos'] ?? 180) ?: 180;
$avaliacaoAtual = $sumulaModel->avaliarRegrasKumite($sumula);
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
        .barra .btn { min-height: 48px; font-weight: 700; }
        .opcoes-penal .btn { min-height: 64px; font-size: 1.2rem; font-weight: 700; text-align: left; }
        .vencer .btn { min-height: 90px; font-size: 1.5rem; font-weight: 800; }
        .newaza { color: #67e8f9; font-size: 1rem; font-weight: 800; letter-spacing: .12em; }
        @media (max-width: 768px) {
            body { overflow: auto; }
            .placar { grid-template-columns: 1fr; grid-template-rows: 1fr 1fr; height: calc(100dvh - 64px); min-height: 520px; }
            .lado { padding: 12px; gap: 6px; }
            .lado .nome { font-size: clamp(1.2rem, 5vw, 1.8rem); }
            .lado .pontos { font-size: clamp(4rem, 15vw, 7rem); }
            .botoes .btn { min-height: 48px; font-size: 1rem; }
            .relogio { top: 50%; padding: 8px 14px; transform: translate(-50%, -50%); border: 2px solid #fff; }
            .relogio .tempo { font-size: clamp(2.4rem, 10vw, 3.8rem); }
            .relogio .btn { min-width: 140px; min-height: 48px; font-size: 1.05rem; }
            .barra { height: auto; min-height: 64px; flex-wrap: wrap; justify-content: center; padding: 8px; }
            .barra > .btn, .barra .btn { min-height: 48px; padding: 8px 12px; font-size: .9rem; }
        }
    </style>
</head>
<body>

<div class="placar">
    <?php foreach (['aka' => 'VERMELHO', 'shiro' => 'BRANCO'] as $lado => $cor): ?>
    <section class="lado <?= $lado ?>">
        <div>
            <div class="cor"><?= $lado === 'aka' ? 'AKA · VERMELHO' : 'SHIRO · BRANCO' ?></div>
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
    <div class="newaza" id="newaza" hidden>NE WAZA · 60 S</div>
    <div class="tempo" id="tempo">0:00</div>
    <button type="button" class="btn btn-success" id="btnRelogio" onclick="alternarRelogio()"><i class="fas fa-play"></i> Iniciar</button>
</div>

<div class="barra">
    <a href="<?= $voltar ?>" class="btn btn-outline-light" id="btnSair"><i class="fas fa-arrow-left"></i> Sair</a>
    <span class="d-none d-md-inline"><?= htmlspecialchars($luta['categoria_nome'] ?? '') ?> · Luta <?= (int)$luta['numero_luta'] ?></span>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-info" id="btnNeWaza" onclick="ativarNeWaza()">Ativar Ne Waza (60 s)</button>
        <button type="button" class="btn btn-outline-light" id="btnDesfazer" onclick="desfazer()" disabled><i class="fas fa-rotate-left"></i> Desfazer último</button>
        <button type="button" class="btn btn-warning" id="btnTerminar" onclick="perguntarVencedor('Confirme o resultado.')"><i class="fas fa-flag-checkered"></i> Confirmar resultado</button>
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
                <input type="hidden" name="tempo_decorrido_segundos" id="tempoFinalLuta" value="<?= (int)$sumula['tempo_decorrido_segundos'] ?>">
                <input type="hidden" name="forma_vitoria" id="formaVitoria" value="pontos">
                <input type="hidden" name="motivo" id="motivoVitoria" value="">
                <div class="modal-header"><h5 class="modal-title fs-3" id="tituloVencedor">Quem venceu?</h5></div>
                <div class="modal-body">
                    <p class="text-muted" id="textoVencedor">Escolha o vencedor. Se a luta empatou, é a decisão dos árbitros.</p>
                    <div class="row g-3 vencer">
                        <div class="col-6"><button type="submit" name="vencedor_lado" value="aka" class="btn btn-danger w-100" id="venceAka" onclick="selecionarVencedor('aka')"><?= htmlspecialchars($luta['nome_aka'] ?? 'Vermelho') ?></button></div>
                        <div class="col-6"><button type="submit" name="vencedor_lado" value="shiro" class="btn btn-light border border-dark w-100" id="venceShiro" onclick="selecionarVencedor('shiro')"><?= htmlspecialchars($luta['nome_shiro'] ?? 'Branco') ?></button></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-warning" id="btnGolden" onclick="iniciarGolden()">Empate: Ponto de ouro</button>
                    <button type="button" class="btn btn-outline-danger" id="btnDesfazerModal" onclick="fecharEdesfazer()" disabled><i class="fas fa-rotate-left"></i> Desfazer último</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnVoltarPlacar" onclick="retomarPlacar()">Voltar ao placar</button>
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
var avaliacaoAtual = <?= json_encode($avaliacaoAtual) ?>;

var segundos = parseInt(sumula.tempo_decorrido_segundos || 0, 10);
var golden = parseInt(sumula.golden_score_ativo || 0, 10) === 1;
var rodando = false, timer = null;
var historico = []; // toques que podem ser desfeitos
var ladoPenal = 'aka';
var aguardandoVencedor = false, eventoPendente = false;
var newazaAtivo = false, newazaSegundos = 60;
var vencedorSugerido = null;

function fmt(s) { s = Math.max(0, s); return Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0'); }

function desenharRelogio() {
    var el = document.getElementById('tempo');
    if (newazaAtivo) {
        el.textContent = fmt(newazaSegundos);
        el.classList.remove('acabou');
    } else if (golden) {
        el.textContent = fmt(segundos - DURACAO);
        el.classList.remove('acabou');
    } else {
        var resta = DURACAO - segundos;
        el.textContent = fmt(resta);
        el.classList.toggle('acabou', resta <= 0);
    }
    document.getElementById('golden').style.display = golden ? 'block' : 'none';
    document.getElementById('newaza').hidden = !newazaAtivo;
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
    document.getElementById('btnDesfazer').disabled = historico.length === 0 || terminada || eventoPendente;
    document.getElementById('btnDesfazerModal').disabled = historico.length === 0 || terminada || eventoPendente;
    document.getElementById('btnNeWaza').disabled = terminada || aguardandoVencedor || newazaAtivo || eventoPendente;
    document.querySelectorAll('.botoes .btn').forEach(function (b) {
        b.disabled = terminada || aguardandoVencedor || eventoPendente;
    });
    var pontosAka = parseFloat(sumula.pontos_aka || 0);
    var pontosShiro = parseFloat(sumula.pontos_shiro || 0);
    var pontosDecisivos = pontosAka >= 3 || pontosShiro >= 3;
    var pontoDeOuro = golden && pontosAka !== pontosShiro;
    var podeConfirmar = pontosDecisivos || (golden ? pontoDeOuro : segundos >= DURACAO);
    document.getElementById('btnTerminar').disabled = terminada || aguardandoVencedor || eventoPendente || !podeConfirmar;
    document.getElementById('btnRelogio').disabled = terminada || aguardandoVencedor || eventoPendente;
}

function alternarRelogio() {
    if (terminada || aguardandoVencedor || eventoPendente) return;
    rodando = !rodando;
    var b = document.getElementById('btnRelogio');
    if (rodando) {
        b.className = 'btn btn-warning';
        b.innerHTML = '<i class="fas fa-pause"></i> Pausar';
        timer = setInterval(function () {
            if (newazaAtivo) {
                newazaSegundos--;
                if (newazaSegundos <= 0) {
                    newazaSegundos = 0;
                    newazaAtivo = false;
                    pararRelogio();
                    alert('Ne Waza encerrado. O cronômetro da luta está pausado.');
                    desenharRelogio();
                    desenharPlacar();
                    return;
                }
            } else {
                segundos++;
            }
            desenharRelogio();
            if (!newazaAtivo && segundos > 0 && segundos % 10 === 0) enviar('tempo', 'geral', true);
            if (!newazaAtivo && !golden && segundos >= DURACAO) {
                pararRelogio();
                enviar('tempo', 'geral', true);
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
    if (terminada || eventoPendente) return;
    var dados = { id_luta: ID_LUTA, tipo: tipo, lado: lado, segundo_luta: segundos };
    if (extra) Object.assign(dados, extra);
    eventoPendente = true;
    desenharPlacar();
    var request = new XMLHttpRequest();
    var url = '../controllers/sumulaKumiteController.php?acao=evento';
    var corpo = Object.keys(dados).map(function (chave) {
        return encodeURIComponent(chave) + '=' + encodeURIComponent(dados[chave]);
    }).join('&');
    var finalizado = false;

    function liberarBotoes() {
        if (finalizado) return false;
        finalizado = true;
        eventoPendente = false;
        desenharPlacar();
        return true;
    }

    function falha(mensagem, detalhe, resposta) {
        if (!liberarBotoes()) return;
        console.error('[Súmula Kumite] Falha ao registrar evento.', {
            evento: tipo,
            lado: lado,
            status: request.status,
            statusText: request.statusText,
            url: url,
            erro: mensagem,
            detalhe: detalhe,
            resposta: resposta
        });
        if (!silencioso) {
            alert(mensagem || 'Não foi possível salvar o toque. Confira o console para ver o erro.');
        }
    }

    try {
        request.open('POST', url, true);
        request.timeout = 15000;
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.onreadystatechange = function () {
            if (request.readyState !== XMLHttpRequest.DONE || finalizado) return;

            var resposta;
            try {
                resposta = JSON.parse(request.responseText);
            } catch (erroJson) {
                falha(
                    'O servidor retornou uma resposta inválida. O toque pode não ter sido salvo.',
                    erroJson.name + ': ' + erroJson.message,
                    request.responseText.substring(0, 4000)
                );
                return;
            }

            if (request.status < 200 || request.status >= 300) {
                falha(
                    resposta.error || 'Erro HTTP ' + request.status + '. O toque não foi confirmado.',
                    'HTTP ' + request.status + ' ' + request.statusText,
                    resposta
                );
                return;
            }

            if (!resposta.success) {
                falha(resposta.error || 'Não foi possível salvar o toque.', 'A aplicação recusou o evento.', resposta);
                return;
            }

            if (!liberarBotoes()) return;
            sumula = resposta.sumula;
            if (tipo === 'desfazer') {
                historico.pop();
                aguardandoVencedor = false;
            } else if (['wazaari', 'ippon', 'shido', 'chui', 'saida', 'hansoku_make'].indexOf(tipo) >= 0) {
                historico.push({ tipo: tipo, lado: lado });
            }
            desenharPlacar();
            if (resposta.avaliacao && resposta.avaliacao.finalizada) {
                pararRelogio();
                perguntarVencedor('', resposta.avaliacao.vencedor_lado, resposta.avaliacao.motivo, resposta.avaliacao.forma_vitoria);
            } else if (tipo === 'desfazer' && !golden && segundos >= DURACAO) {
                perguntarVencedor('Acabou o tempo!');
            }
        };
        request.onerror = function () {
            falha('Falha de rede ao salvar o toque.', 'XMLHttpRequest encontrou um erro de rede.', request.responseText);
        };
        request.ontimeout = function () {
            falha('O servidor demorou para responder. Verifique a luta antes de tentar novamente.', 'Tempo limite de 15 segundos excedido.', request.responseText);
        };
        request.onabort = function () {
            falha('O envio do toque foi interrompido.', 'A requisição foi cancelada.', request.responseText);
        };
        request.send(corpo);
    } catch (erroEnvio) {
        falha(
            'Não foi possível enviar o toque ao servidor.',
            erroEnvio.name + ': ' + erroEnvio.message,
            null
        );
    }
}

function desfazer() {
    var ultimo = historico[historico.length - 1];
    if (!ultimo) return;
    enviar('desfazer', ultimo.lado, false, { desfazer_tipo: ultimo.tipo });
}

function fecharEdesfazer() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('modalVencedor'));
    if (modal) modal.hide();
    desfazer();
}

function retomarPlacar() {
    aguardandoVencedor = false;
    desenharPlacar();
}

function ativarNeWaza() {
    if (terminada || aguardandoVencedor || eventoPendente || newazaAtivo) return;
    newazaAtivo = true;
    newazaSegundos = 60;
    pararRelogio();
    enviar('tempo', 'geral', true);
    desenharRelogio();
    desenharPlacar();
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

function perguntarVencedor(titulo, vencedorLado, motivo, formaVitoria) {
    var pontosAka = parseFloat(sumula.pontos_aka || 0);
    var pontosShiro = parseFloat(sumula.pontos_shiro || 0);
    var atingiuLimite = pontosAka >= 3 || pontosShiro >= 3;
    var podeConfirmar = atingiuLimite || (golden
        ? pontosAka !== pontosShiro
        : segundos >= DURACAO);
    if (!podeConfirmar) return;

    pararRelogio();
    aguardandoVencedor = true;
    vencedorSugerido = vencedorLado || null;
    document.getElementById('tempoFinalLuta').value = segundos;
    document.getElementById('tituloVencedor').textContent = (titulo ? titulo + ' ' : '') + 'Quem venceu?';
    var a = parseFloat(sumula.pontos_aka || 0), s = parseFloat(sumula.pontos_shiro || 0);
    document.getElementById('formaVitoria').value = formaVitoria || ((a === s) ? 'hantei' : 'pontos');
    document.getElementById('motivoVitoria').value = motivo || '';
    document.getElementById('textoVencedor').textContent = motivo
        ? NOMES[vencedorLado] + ' pontuou. ' + motivo + '. Confirme o resultado ou desfaça o último toque.'
        : (a === s
            ? 'Empate no placar. Escolha quem venceu ou inicie o ponto de ouro.'
            : 'Placar: ' + NOMES.aka + ' ' + a + ' × ' + s + ' ' + NOMES.shiro + '. Confirme quem venceu.');
    document.getElementById('btnGolden').hidden = !!vencedorLado || a !== s;
    desenharPlacar();
    new bootstrap.Modal(document.getElementById('modalVencedor')).show();
}

function selecionarVencedor(lado) {
    if (vencedorSugerido && lado !== vencedorSugerido) {
        document.getElementById('formaVitoria').value = 'pontos';
        document.getElementById('motivoVitoria').value = 'Decisão oficial da arbitragem.';
    }
}

function iniciarGolden() {
    bootstrap.Modal.getInstance(document.getElementById('modalVencedor')).hide();
    aguardandoVencedor = false;
    golden = true;
    newazaAtivo = false;
    vencedorSugerido = null;
    if (segundos < DURACAO) segundos = DURACAO;
    enviar('golden_score', 'geral');
    desenharRelogio();
}

desenharRelogio();
desenharPlacar();
if (terminada) {
    document.querySelectorAll('.botoes .btn, #btnRelogio, #btnTerminar, #btnNeWaza, #btnDesfazer, #btnDesfazerModal').forEach(function (b) { b.disabled = true; });
    document.getElementById('fimTitulo').textContent = 'Esta luta já terminou';
    document.getElementById('fimMotivo').textContent = <?= json_encode($luta['nome_vencedor'] ? 'Vencedor: ' . $luta['nome_vencedor'] : '') ?>;
    new bootstrap.Modal(document.getElementById('modalFim')).show();
} else if (avaliacaoAtual.finalizada) {
    perguntarVencedor('', avaliacaoAtual.vencedor_lado, avaliacaoAtual.motivo, avaliacaoAtual.forma_vitoria);
} else if (segundos >= DURACAO && !golden) {
    perguntarVencedor('Acabou o tempo!');
}

// Atalhos de teclado: espaço = iniciar/pausar
document.addEventListener('keydown', function (e) {
    if (e.code === 'Space' && !document.querySelector('.modal.show')) { e.preventDefault(); alternarRelogio(); }
});
</script>
</body>
</html>
