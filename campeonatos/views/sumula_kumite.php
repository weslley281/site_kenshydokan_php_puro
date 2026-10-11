<?php
$pageTitle = 'Súmula Digital de Kumite | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/../models/sumulaKumiteModel.php";
include_once __DIR__ . "/../models/tatameModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";

$idLuta = (int)($_GET['id'] ?? 0);
$lutaModel = new Luta();
$sumulaModel = new SumulaKumite();
$tatameModel = new Tatame();
$campeonatoModel = new Campeonato();

$luta = $lutaModel->buscarPorId($idLuta);

if (!$luta) {
    echo "<div class='alert alert-danger'>Combate #{$idLuta} não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$campeonato = $campeonatoModel->buscarPorId((int)$luta['id_campeonato']);
$sumula = $sumulaModel->buscarPorLuta($idLuta);

if (!$sumula) {
    $idSumula = $sumulaModel->criarSumula($idLuta, (int)$luta['id_campeonato']);
    $sumula = $sumulaModel->buscarPorId($idSumula);
}

$eventos = $sumulaModel->buscarEventos($idLuta);
$tatame = !empty($luta['id_tatame']) ? $tatameModel->buscarPorId((int)$luta['id_tatame']) : null;
?>

<div class="row mb-3 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $luta['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo'] ?? '') ?></a></li>
                <?php if ($tatame): ?>
                    <li class="breadcrumb-item"><a href="painel_tatame.php?id=<?= $tatame['id_tatame'] ?>" class="text-decoration-none"><?= htmlspecialchars($tatame['nome']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active" aria-current="page">Súmula #<?= $luta['numero_luta'] ?></li>
            </ol>
        </nav>
        <h2 class="h4 fw-bold mb-0 text-dark">
            <i class="fas fa-stopwatch text-danger me-2"></i>Súmula Digital Oficial: Combate #<?= $luta['numero_luta'] ?>
        </h2>
        <span class="badge bg-dark mt-1"><?= htmlspecialchars($luta['categoria_nome'] ?? 'Categoria') ?></span>
        <span class="badge bg-secondary mt-1">Rodada <?= $luta['rodada'] ?></span>
        <?php if ($luta['status'] === 'concluida'): ?>
            <span class="badge bg-success mt-1">CONCLUÍDA</span>
        <?php else: ?>
            <span class="badge bg-danger mt-1">AO VIVO</span>
        <?php endif; ?>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <?php if ($tatame): ?>
            <a href="telao.php?id=<?= $tatame['id_tatame'] ?>" target="_blank" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-tv me-1"></i> Abrir Telão
            </a>
            <a href="painel_tatame.php?id=<?= $tatame['id_tatame'] ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Mesa Tatame
            </a>
        <?php endif; ?>
        <button type="button" class="btn btn-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalFinalizar">
            <i class="fas fa-flag-checkered me-1"></i> Encerrar Combate
        </button>
    </div>
</div>

<!-- Alerta de Vitória Imediata (Waza-ari Awasete Ippon ou Golden Score) -->
<div id="alertaVitoria" class="alert alert-success d-none shadow-sm border-2 fw-bold text-center fs-5 mb-3">
    <i class="fas fa-trophy text-warning me-2"></i> <span id="textoVitoria"></span>
</div>

<!-- CRONÔMETROS (Combate Regulamentar e Ne Waza) -->
<div class="row g-3 mb-4">
    <!-- Cronômetro Geral (3:00 min) -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-lg bg-dark text-white text-center py-4 rounded-4" style="background: radial-gradient(circle at 50% 0%, #1e293b 0%, #090d16 100%) !important; border: 2px solid rgba(255,255,255,0.08) !important;">
            <div class="d-flex justify-content-between px-4 align-items-center mb-2">
                <span class="text-white-50 text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="fas fa-clock text-warning me-1"></i> TEMPO REGULAMENTAR (3 MINUTOS)
                </span>
                <span id="badgeFase" class="badge bg-danger px-3 py-1">TEMPO REGULAMENTAR</span>
            </div>
            
            <div id="displayTempo" class="digital-glow-timer py-2">
                03:00
            </div>

            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                <button type="button" id="btnPlayPause" class="btn btn-success btn-lg px-4 shadow">
                    <i class="fas fa-play me-1"></i> INICIAR
                </button>
                <button type="button" id="btnResetTempo" class="btn btn-secondary btn-lg" title="Reiniciar Cronômetro">
                    <i class="fas fa-undo"></i>
                </button>
                <button type="button" id="btnMais10s" class="btn btn-secondary btn-sm" title="+10 segundos">+10s</button>
                <button type="button" id="btnMenos10s" class="btn btn-secondary btn-sm" title="-10 segundos">-10s</button>
                <button type="button" id="btnGoldenScore" class="btn btn-warning btn-sm fw-bold">
                    <i class="fas fa-star me-1"></i> Golden Score (1 min)
                </button>
            </div>
        </div>
    </div>

    <!-- Cronômetro Ne Waza (Solo - Máx 60s) -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-lg bg-dark text-white text-center py-4 rounded-4" style="background: radial-gradient(circle at 50% 0%, #162436 0%, #090d16 100%) !important; border: 2px solid rgba(56, 189, 248, 0.3) !important;">
            <div class="d-flex justify-content-between px-4 align-items-center mb-2">
                <span class="text-info text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.1em;">
                    <i class="fas fa-arrows-alt-v me-1"></i> NE WAZA (SOLO - MÁX 60s)
                </span>
                <span id="badgeNeWaza" class="badge bg-secondary px-3 py-1">PARADO</span>
            </div>
            
            <div id="displayNeWaza" class="display-3 fw-bold font-monospace text-info py-2" style="text-shadow: 0 0 25px rgba(56, 189, 248, 0.6); font-family: 'Montserrat', monospace;">
                00:00
            </div>

            <div class="d-flex justify-content-center gap-2 mt-3">
                <button type="button" id="btnNeWazaToggle" class="btn btn-info btn-sm px-4 fw-bold text-dark shadow">
                    <i class="fas fa-play me-1"></i> Iniciar Solo
                </button>
                <button type="button" id="btnNeWazaReset" class="btn btn-secondary btn-sm">
                    <i class="fas fa-undo"></i> Reset Solo
                </button>
            </div>
            <small class="text-white-50 mt-2 d-block" style="font-size: 0.75rem;">Ao atingir 60s, a luta deve ser reiniciada em pé pelo árbitro.</small>
        </div>
    </div>
</div>

<!-- PLACAR OFICIAL (AKA x SHIRO) -->
<div class="row g-4 mb-4">
    <!-- CANTO VERMELHO (AKA) -->
    <div class="col-md-6">
        <div class="card border-0 shadow-lg corner-card-aka h-100">
            <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center border-0">
                <span class="fw-bold tracking-wider fs-7 text-uppercase"><i class="fas fa-circle me-1"></i> CANTO VERMELHO (AKA)</span>
                <span class="badge bg-black bg-opacity-35 px-3 py-1 fs-7">ATLETA 1</span>
            </div>
            <div class="card-body p-4 text-center">
                <h3 class="fw-bold text-white mb-0"><?= htmlspecialchars($luta['nome_aka'] ?? 'ATLETA AKA') ?></h3>
                <span class="badge bg-white bg-opacity-10 text-white-50 border border-white border-opacity-10 px-3 py-1 mt-1 mb-3">
                    <?= htmlspecialchars($luta['dojo_aka'] ?? 'Dojo') ?>
                </span>

                <!-- PONTUAÇÃO TOTAL AKA -->
                <div class="p-3 rounded-4 mb-4" style="background: rgba(220, 38, 38, 0.15); border: 2px solid rgba(220, 38, 38, 0.4);">
                    <div class="small text-danger fw-bold text-uppercase tracking-wider">Pontuação Total</div>
                    <div id="pontosAka" class="digital-score-aka py-1">
                        <?= number_format((float)$sumula['pontos_aka'], 1) ?>
                    </div>
                    <small class="text-white-50">6 Waza-ari (3.0 pts) = Vitória por Ippon</small>
                </div>

                <!-- BOTÕES DE GOLPES (PONTOS) -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <button type="button" class="btn btn-danger w-100 py-3 fw-bold btn-acao" data-tipo="wazaari" data-lado="aka">
                            <i class="fas fa-plus-circle me-1"></i> WAZA-ARI (+0.5)
                        </button>
                        <div class="small text-muted mt-1">Total: <strong id="qtdWazaariAka"><?= $sumula['wazaari_aka'] ?></strong></div>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-dark w-100 py-3 fw-bold btn-acao" data-tipo="ippon" data-lado="aka">
                            <i class="fas fa-star me-1"></i> IPPON (+1.0)
                        </button>
                        <div class="small text-muted mt-1">Total: <strong id="qtdIpponAka"><?= $sumula['ippon_aka'] ?></strong></div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- PENALIDADES AKA -->
                <h6 class="text-muted fw-bold small text-uppercase mb-3 text-start">
                    <i class="fas fa-exclamation-triangle text-warning me-1"></i> Penalidades Oficiais
                </h6>
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-warning w-100 btn-sm fw-bold btn-acao" data-tipo="shido" data-lado="aka">
                            + SHIDO
                        </button>
                        <small class="d-block text-muted mt-1">Shido: <strong id="qtdShidoAka"><?= $sumula['shido_aka'] ?></strong></small>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-warning w-100 btn-sm fw-bold btn-acao" data-tipo="chui" data-lado="aka" title="2º Chui dá 0.5 pt ao adversário">
                            + CHUI
                        </button>
                        <small class="d-block text-muted mt-1">Chui: <strong id="qtdChuiAka"><?= $sumula['chui_aka'] ?></strong></small>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary w-100 btn-sm fw-bold btn-acao" data-tipo="saida" data-lado="aka" title="2ª saída dá 0.5 pt ao adversário">
                            + JOGAI
                        </button>
                        <small class="d-block text-muted mt-1">Saídas: <strong id="qtdSaidasAka"><?= $sumula['saidas_aka'] ?></strong></small>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 fw-bold btn-acao" data-tipo="hansoku_make" data-lado="aka" onclick="return confirm('Confirma HANSOKU-MAKE (desclassificação direta) para AKA?');">
                            <i class="fas fa-ban me-1"></i> HANSOKU-MAKE (DESCLASSIFICAÇÃO)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CANTO BRANCO (SHIRO) -->
    <div class="col-md-6">
        <div class="card border-0 shadow-lg corner-card-shiro h-100">
            <div class="card-header bg-secondary text-white py-3 d-flex justify-content-between align-items-center border-0" style="background: #334155 !important;">
                <span class="fw-bold tracking-wider fs-7 text-uppercase"><i class="far fa-circle me-1"></i> CANTO BRANCO (SHIRO)</span>
                <span class="badge bg-black bg-opacity-35 px-3 py-1 fs-7">ATLETA 2</span>
            </div>
            <div class="card-body p-4 text-center">
                <h3 class="fw-bold text-white mb-0"><?= htmlspecialchars($luta['nome_shiro'] ?? 'ATLETA SHIRO') ?></h3>
                <span class="badge bg-white bg-opacity-10 text-white-50 border border-white border-opacity-10 px-3 py-1 mt-1 mb-3">
                    <?= htmlspecialchars($luta['dojo_shiro'] ?? 'Dojo') ?>
                </span>

                <!-- PONTUAÇÃO TOTAL SHIRO -->
                <div class="p-3 rounded-4 mb-4" style="background: rgba(148, 163, 184, 0.15); border: 2px solid rgba(203, 213, 225, 0.4);">
                    <div class="small text-secondary fw-bold text-uppercase tracking-wider">Pontuação Total</div>
                    <div id="pontosShiro" class="digital-score-shiro py-1">
                        <?= number_format((float)$sumula['pontos_shiro'], 1) ?>
                    </div>
                    <small class="text-white-50">6 Waza-ari (3.0 pts) = Vitória por Ippon</small>
                </div>

                <!-- BOTÕES DE GOLPES (PONTOS) -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary w-100 py-3 fw-bold btn-acao" data-tipo="wazaari" data-lado="shiro">
                            <i class="fas fa-plus-circle me-1"></i> WAZA-ARI (+0.5)
                        </button>
                        <div class="small text-muted mt-1">Total: <strong id="qtdWazaariShiro"><?= $sumula['wazaari_shiro'] ?></strong></div>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-dark w-100 py-3 fw-bold btn-acao" data-tipo="ippon" data-lado="shiro">
                            <i class="fas fa-star me-1"></i> IPPON (+1.0)
                        </button>
                        <div class="small text-muted mt-1">Total: <strong id="qtdIpponShiro"><?= $sumula['ippon_shiro'] ?></strong></div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- PENALIDADES SHIRO -->
                <h6 class="text-muted fw-bold small text-uppercase mb-3 text-start">
                    <i class="fas fa-exclamation-triangle text-warning me-1"></i> Penalidades Oficiais
                </h6>
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-warning w-100 btn-sm fw-bold btn-acao" data-tipo="shido" data-lado="shiro">
                            + SHIDO
                        </button>
                        <small class="d-block text-muted mt-1">Shido: <strong id="qtdShidoShiro"><?= $sumula['shido_shiro'] ?></strong></small>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-warning w-100 btn-sm fw-bold btn-acao" data-tipo="chui" data-lado="shiro" title="2º Chui dá 0.5 pt ao adversário">
                            + CHUI
                        </button>
                        <small class="d-block text-muted mt-1">Chui: <strong id="qtdChuiShiro"><?= $sumula['chui_shiro'] ?></strong></small>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-secondary w-100 btn-sm fw-bold btn-acao" data-tipo="saida" data-lado="shiro" title="2ª saída dá 0.5 pt ao adversário">
                            + JOGAI
                        </button>
                        <small class="d-block text-muted mt-1">Saídas: <strong id="qtdSaidasShiro"><?= $sumula['saidas_shiro'] ?></strong></small>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 fw-bold btn-acao" data-tipo="hansoku_make" data-lado="shiro" onclick="return confirm('Confirma HANSOKU-MAKE (desclassificação direta) para SHIRO?');">
                            <i class="fas fa-ban me-1"></i> HANSOKU-MAKE (DESCLASSIFICAÇÃO)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HISTÓRICO DE EVENTOS EM TEMPO REAL -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-list-alt text-primary me-2"></i>Histórico Oficial do Combate (Súmula de Mesa)
        </h5>
        <span class="badge bg-light text-dark border">
            Atalhos: [Espaço] Cronômetro | [N] Ne Waza | [A] +Waza-ari Aka | [S] +Waza-ari Shiro
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0 font-monospace small">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Tempo</th>
                        <th style="width: 100px;">Lado</th>
                        <th style="width: 120px;">Tipo</th>
                        <th>Descrição do Lance</th>
                        <th style="width: 80px;" class="text-end">Pts</th>
                    </tr>
                </thead>
                <tbody id="listaEventos">
                    <?php if (empty($eventos)): ?>
                        <tr id="linhaVazia"><td colspan="5" class="text-center py-3 text-muted font-sans-serif">Nenhum evento registrado ainda. Inicie o combate.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_reverse($eventos) as $ev): ?>
                            <tr>
                                <td><?= sprintf('%02d:%02d', floor($ev['segundo_luta'] / 60), $ev['segundo_luta'] % 60) ?></td>
                                <td>
                                    <?php if ($ev['lado'] === 'aka'): ?>
                                        <span class="badge bg-danger">AKA</span>
                                    <?php elseif ($ev['lado'] === 'shiro'): ?>
                                        <span class="badge bg-secondary">SHIRO</span>
                                    <?php else: ?>
                                        <span class="badge bg-dark">MESA</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="text-uppercase"><?= htmlspecialchars($ev['tipo_evento']) ?></span></td>
                                <td class="font-sans-serif"><?= htmlspecialchars($ev['descricao']) ?></td>
                                <td class="text-end fw-bold"><?= $ev['pontos_atribuidos'] > 0 ? '+' . number_format($ev['pontos_atribuidos'], 1) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL PARA ENCERRAMENTO OFICIAL / DECISÃO DE ÁRBITROS (HANTEI) -->
<div class="modal fade" id="modalFinalizar" tabindex="-1" aria-labelledby="modalFinalizarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../controllers/sumulaKumiteController.php" method="POST">
                <input type="hidden" name="tipo" value="finalizar_luta">
                <input type="hidden" name="id_luta" value="<?= $idLuta ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold" id="modalFinalizarLabel">
                        <i class="fas fa-gavel text-warning me-2"></i>Decisão Final e Encerramento de Combate
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Vencedor Oficial</label>
                        <select name="vencedor_lado" class="form-select form-select-lg fw-bold" required>
                            <option value="aka" class="text-danger">CANTO VERMELHO (AKA) - <?= htmlspecialchars($luta['nome_aka'] ?? 'AKA') ?></option>
                            <option value="shiro" class="text-secondary">CANTO BRANCO (SHIRO) - <?= htmlspecialchars($luta['nome_shiro'] ?? 'SHIRO') ?></option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Forma da Vitória</label>
                        <select name="forma_vitoria" class="form-select" required>
                            <option value="pontos">Vitória por Pontos (Decisão Regulamentar)</option>
                            <option value="ippon">Ippon Direto</option>
                            <option value="wazaari_awasete_ippon">Waza-ari Awasete Ippon (Acúmulo de Waza-ari)</option>
                            <option value="golden_score">Golden Score (Ponto de Ouro na Prorrogação)</option>
                            <option value="hantei">Hantei (Decisão dos Árbitros - Empate Técnico)</option>
                            <option value="desclassificacao">Hansoku-make (Desclassificação do Oponente)</option>
                            <option value="desistencia">Kiken-gachi (Desistência/Abandono)</option>
                        </select>
                    </div>

                    <div class="p-3 bg-light rounded border mb-3 small">
                        <strong>Critérios Oficiais Kenshydokan para Hantei:</strong>
                        <ol class="mb-0 ps-3 mt-1 text-muted">
                            <li>Iniciativa de ataque e combatividade</li>
                            <li>Eficácia dos golpes e controle técnico</li>
                            <li>Defesa e postura marcial</li>
                            <li>Tameshiwari (se houver empate absoluto comprovado)</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Observações da Mesa Arbitral</label>
                        <textarea name="motivo" class="form-control" rows="2" placeholder="Decisão unânime dos árbitros centrais e laterais..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Confirmar e Gravar Resultado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Motor de Cronômetro e AJAX da Súmula Kenshydokan
const idLuta = <?= $idLuta ?>;
let tempoTotalSegundos = 180; // 3 minutos
let tempoDecorrido = <?= (int)$sumula['tempo_decorrido_segundos'] ?>;
let cronometroAtivo = false;
let intervaloCronometro = null;

// Cronômetro Ne Waza (Solo)
let neWazaSegundos = 0;
let neWazaAtivo = false;
let intervaloNeWaza = null;

// Sintetizador de Áudio (Buzzer de Mesa)
function tocarBuzzer(duracaoMs = 800, freq = 440) {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
        gain.gain.setValueAtTime(0.3, audioCtx.currentTime);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        setTimeout(() => {
            osc.stop();
            audioCtx.close();
        }, duracaoMs);
    } catch(e) {}
}

function formatarTempo(segundos) {
    const min = Math.floor(segundos / 60);
    const seg = segundos % 60;
    return (min < 10 ? '0' : '') + min + ':' + (seg < 10 ? '0' : '') + seg;
}

function atualizarDisplayTempo() {
    let restante = Math.max(0, tempoTotalSegundos - tempoDecorrido);
    document.getElementById('displayTempo').innerText = formatarTempo(restante);
    if (restante <= 10 && restante > 0) {
        document.getElementById('displayTempo').classList.remove('text-warning');
        document.getElementById('displayTempo').classList.add('text-danger');
    }
}

// Iniciar / Pausar Cronômetro Geral
document.getElementById('btnPlayPause').addEventListener('click', function() {
    if (cronometroAtivo) {
        clearInterval(intervaloCronometro);
        cronometroAtivo = false;
        this.innerHTML = '<i class="fas fa-play me-1"></i> CONTINUAR';
        this.classList.remove('btn-warning');
        this.classList.add('btn-success');
    } else {
        cronometroAtivo = true;
        this.innerHTML = '<i class="fas fa-pause me-1"></i> PAUSAR';
        this.classList.remove('btn-success');
        this.classList.add('btn-warning');

        intervaloCronometro = setInterval(function() {
            tempoDecorrido++;
            atualizarDisplayTempo();

            if (tempoDecorrido >= tempoTotalSegundos) {
                clearInterval(intervaloCronometro);
                cronometroAtivo = false;
                document.getElementById('btnPlayPause').innerHTML = '<i class="fas fa-check me-1"></i> TEMPO ESGOTADO';
                tocarBuzzer(1500, 300); // Apito longo de término
                alertify.warning('TEMPO REGULAMENTAR ESGOTADO!');
            }
        }, 1000);
    }
});

// Reset Cronômetro Geral
document.getElementById('btnResetTempo').addEventListener('click', function() {
    clearInterval(intervaloCronometro);
    cronometroAtivo = false;
    tempoDecorrido = 0;
    document.getElementById('btnPlayPause').innerHTML = '<i class="fas fa-play me-1"></i> INICIAR';
    document.getElementById('btnPlayPause').classList.remove('btn-warning');
    document.getElementById('btnPlayPause').classList.add('btn-success');
    document.getElementById('displayTempo').classList.remove('text-danger');
    document.getElementById('displayTempo').classList.add('text-warning');
    atualizarDisplayTempo();
});

// Ajustes Finos de Tempo (+10s / -10s)
document.getElementById('btnMais10s').addEventListener('click', function() {
    tempoDecorrido = Math.max(0, tempoDecorrido - 10);
    atualizarDisplayTempo();
});
document.getElementById('btnMenos10s').addEventListener('click', function() {
    tempoDecorrido += 10;
    atualizarDisplayTempo();
});

// Golden Score (Prorrogação de 1 minuto)
document.getElementById('btnGoldenScore').addEventListener('click', function() {
    tempoTotalSegundos = 60;
    tempoDecorrido = 0;
    document.getElementById('badgeFase').innerText = 'GOLDEN SCORE (1 MIN)';
    document.getElementById('badgeFase').className = 'badge bg-warning text-dark';
    atualizarDisplayTempo();
    enviarEvento('golden_score', 'geral', 'Início da Prorrogação de Ponto de Ouro');
});

// Cronômetro de Ne Waza (Solo - Máx 60s)
document.getElementById('btnNeWazaToggle').addEventListener('click', function() {
    const badge = document.getElementById('badgeNeWaza');
    if (neWazaAtivo) {
        clearInterval(intervaloNeWaza);
        neWazaAtivo = false;
        this.innerHTML = '<i class="fas fa-play me-1"></i> Iniciar Solo';
        badge.innerText = 'PARADO';
        badge.className = 'badge bg-secondary';
    } else {
        neWazaAtivo = true;
        this.innerHTML = '<i class="fas fa-pause me-1"></i> Pausar Solo';
        badge.innerText = 'SOLO ATIVO';
        badge.className = 'badge bg-info text-dark';

        intervaloNeWaza = setInterval(function() {
            neWazaSegundos++;
            document.getElementById('displayNeWaza').innerText = formatarTempo(neWazaSegundos);

            if (neWazaSegundos >= 60) {
                clearInterval(intervaloNeWaza);
                neWazaAtivo = false;
                document.getElementById('btnNeWazaToggle').innerHTML = '<i class="fas fa-stop me-1"></i> LIMITE 60s!';
                tocarBuzzer(1000, 600); // Alerta agudo de 60s
                alertify.error('LIMITE DE 60 SEGUNDOS DE NE WAZA ATINGIDO! Luta deve reiniciar em pé.');
            }
        }, 1000);
    }
});

document.getElementById('btnNeWazaReset').addEventListener('click', function() {
    clearInterval(intervaloNeWaza);
    neWazaAtivo = false;
    neWazaSegundos = 0;
    document.getElementById('displayNeWaza').innerText = '00:00';
    document.getElementById('btnNeWazaToggle').innerHTML = '<i class="fas fa-play me-1"></i> Iniciar Solo';
    document.getElementById('badgeNeWaza').innerText = 'PARADO';
    document.getElementById('badgeNeWaza').className = 'badge bg-secondary';
});

// Envio de Eventos AJAX para o Servidor
function enviarEvento(tipo, lado, descricao = '') {
    const formData = new FormData();
    formData.append('id_luta', idLuta);
    formData.append('tipo', tipo);
    formData.append('lado', lado);
    formData.append('segundo_luta', tempoDecorrido);
    formData.append('descricao', descricao);

    fetch('../controllers/sumulaKumiteController.php?acao=evento', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alertify.error(data.error || 'Erro ao processar pontuação.');
            return;
        }

        tocarBuzzer(250, tipo === 'ippon' ? 880 : 520);

        // Atualiza Placar na Tela
        const s = data.sumula;
        document.getElementById('pontosAka').innerText = parseFloat(s.pontos_aka).toFixed(1);
        document.getElementById('qtdWazaariAka').innerText = s.wazaari_aka;
        document.getElementById('qtdIpponAka').innerText = s.ippon_aka;
        document.getElementById('qtdShidoAka').innerText = s.shido_aka;
        document.getElementById('qtdChuiAka').innerText = s.chui_aka;
        document.getElementById('qtdSaidasAka').innerText = s.saidas_aka;

        document.getElementById('pontosShiro').innerText = parseFloat(s.pontos_shiro).toFixed(1);
        document.getElementById('qtdWazaariShiro').innerText = s.wazaari_shiro;
        document.getElementById('qtdIpponShiro').innerText = s.ippon_shiro;
        document.getElementById('qtdShidoShiro').innerText = s.shido_shiro;
        document.getElementById('qtdChuiShiro').innerText = s.chui_shiro;
        document.getElementById('qtdSaidasShiro').innerText = s.saidas_shiro;

        // Se o motor indicou vitória imediata
        if (data.avaliacao && data.avaliacao.finalizada) {
            clearInterval(intervaloCronometro);
            tocarBuzzer(1500, 440);
            const boxVitoria = document.getElementById('alertaVitoria');
            document.getElementById('textoVitoria').innerText = data.avaliacao.motivo;
            boxVitoria.classList.remove('d-none');
            alertify.success('COMBATE FINALIZADO: ' + data.avaliacao.motivo);
        }

        // Atualiza Lista de Eventos
        if (data.eventos && data.eventos.length > 0) {
            renderizarEventos(data.eventos);
        }
    })
    .catch(err => {
        alertify.error('Erro de conexão ao enviar evento.');
    });
}

function renderizarEventos(eventos) {
    const tbody = document.getElementById('listaEventos');
    const reversos = [...eventos].reverse();
    let html = '';

    reversos.forEach(ev => {
        const min = Math.floor(ev.segundo_luta / 60);
        const seg = ev.segundo_luta % 60;
        const tempo = (min < 10 ? '0' : '') + min + ':' + (seg < 10 ? '0' : '') + seg;
        const badgeLado = ev.lado === 'aka' ? '<span class="badge bg-danger">AKA</span>' : (ev.lado === 'shiro' ? '<span class="badge bg-secondary">SHIRO</span>' : '<span class="badge bg-dark">MESA</span>');
        const pts = parseFloat(ev.pontos_atribuidos) > 0 ? '+' + parseFloat(ev.pontos_atribuidos).toFixed(1) : '-';

        html += `<tr>
            <td>${tempo}</td>
            <td>${badgeLado}</td>
            <td><span class="text-uppercase">${ev.tipo_evento}</span></td>
            <td class="font-sans-serif">${ev.descricao}</td>
            <td class="text-end fw-bold">${pts}</td>
        </tr>`;
    });

    tbody.innerHTML = html;
}

// Vincula cliques aos botões de ação
document.querySelectorAll('.btn-acao').forEach(btn => {
    btn.addEventListener('click', function() {
        const tipo = this.getAttribute('data-tipo');
        const lado = this.getAttribute('data-lado');
        enviarEvento(tipo, lado);
    });
});

// Atalhos de Teclado
window.addEventListener('keydown', function(e) {
    if (['input', 'textarea', 'select'].includes(document.activeElement.tagName.toLowerCase())) {
        return;
    }

    if (e.code === 'Space') {
        e.preventDefault();
        document.getElementById('btnPlayPause').click();
    } else if (e.key === 'n' || e.key === 'N') {
        document.getElementById('btnNeWazaToggle').click();
    } else if (e.key === 'a' || e.key === 'A') {
        enviarEvento('wazaari', 'aka');
    } else if (e.key === 's' || e.key === 'S') {
        enviarEvento('wazaari', 'shiro');
    }
});

atualizarDisplayTempo();
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
