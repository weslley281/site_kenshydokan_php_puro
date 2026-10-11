<div class="container-fluid p-3 vh-100 d-flex flex-column text-white position-relative">
    <!-- BARRA SUPERIOR / CABEÇALHO DA SÚMULA -->
    <header class="d-flex justify-content-between align-items-center bg-dark bg-opacity-75 p-3 rounded-3 mb-2 border border-secondary shadow-sm">
        <div class="d-flex align-items-center">
            <a href="/tatames/<?= $luta['id_tatame'] ?? 1 ?>/painel" class="btn btn-outline-light btn-sm me-3" title="Voltar ao Painel da Mesa">
                <i class="fas fa-arrow-left"></i> Mesa
            </a>
            <div>
                <h4 class="mb-0 fw-bold text-white">
                    <i class="fas fa-stopwatch text-danger me-2"></i>Súmula Digital Oficial &bull; Luta #<?= $luta['numero_luta'] ?>
                </h4>
                <small class="text-secondary">
                    <?= htmlspecialchars($luta['categoria_nome'] ?? 'Kumite Oficial') ?> &bull; <?= $luta['rodada'] ?>ª Rodada
                </small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Modal de Ajuda / Atalhos -->
            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalAtalhos">
                <i class="fas fa-keyboard me-1"></i> Atalhos
            </button>
            <!-- Subsidiar Hantei / Tameshiwari -->
            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalHantei">
                <i class="fas fa-balance-scale me-1"></i> Decisão Hantei
            </button>
            <a href="/tatames/<?= $luta['id_tatame'] ?? 1 ?>/telao" target="_blank" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-tv me-1"></i> Telão do Tatame
            </a>
            <button class="btn btn-sm btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalFinalizarLuta">
                <i class="fas fa-flag-checkered me-1"></i> Concluir Luta
            </button>
        </div>
    </header>

    <!-- ÁREA PRINCIPAL: CANTO AKA | CRONÔMETROS | CANTO SHIRO -->
    <div class="row flex-grow-1 g-3">
        <!-- CANTO VERMELHO (AKA) -->
        <div class="col-md-5 d-flex flex-column">
            <div class="card h-100 bg-danger bg-opacity-90 text-white border-0 shadow-lg d-flex flex-column justify-content-between p-3 position-relative" style="border-radius: 14px;">
                <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-25 pb-2">
                    <span class="fs-5 fw-black text-uppercase">AKA (VERMELHO)</span>
                    <span class="badge bg-black bg-opacity-40 px-2 py-1"><?= htmlspecialchars($luta['dojo_aka'] ?? 'Dojo') ?></span>
                </div>

                <div class="text-center my-2">
                    <h2 class="fw-black mb-0 text-truncate text-uppercase" id="display_nome_aka" title="<?= htmlspecialchars($luta['nome_aka'] ?? 'AKA') ?>">
                        <?= htmlspecialchars($luta['nome_aka'] ?? 'AKA') ?>
                    </h2>
                    <small class="text-white-50"><?= htmlspecialchars($luta['graduacao_aka'] ?? '') ?></small>
                </div>

                <!-- Pontos e Waza-ari AKA -->
                <div class="bg-black bg-opacity-35 rounded-3 p-3 text-center my-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-6 border-end border-white border-opacity-25">
                            <span class="d-block small text-white-50 text-uppercase fw-bold">Pontos Totais</span>
                            <span id="score_pontos_aka" class="display-3 fw-black text-warning"><?= number_format($sumula['pontos_aka'], 1) ?></span>
                        </div>
                        <div class="col-6">
                            <span class="d-block small text-white-50 text-uppercase fw-bold">Waza-ari (0.5 pt)</span>
                            <span id="score_wazaari_aka" class="display-4 fw-bold"><?= (int)$sumula['wazaari_aka'] ?></span>
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">(6 = Ippon imediato)</small>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação AKA -->
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-warning w-100 py-2 fw-bold text-dark fs-6 shadow-sm" onclick="lancarEvento('wazaari', 'aka')">
                            <i class="fas fa-plus me-1"></i> WAZA-ARI (+0.5)
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-light w-100 py-2 fw-bold text-danger fs-6 shadow-sm" onclick="lancarEvento('ippon', 'aka')">
                            <i class="fas fa-star me-1"></i> IPPON (+1.0)
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark w-100 py-1 text-white border border-secondary small" onclick="lancarEvento('shido', 'aka')">
                            Shido (<span id="score_shido_aka"><?= (int)$sumula['shido_aka'] ?></span>)
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark w-100 py-1 text-warning border border-secondary small" onclick="lancarEvento('chui', 'aka')" title="2 Chui concedem 0.5 ao oponente">
                            Chui (<span id="score_chui_aka"><?= (int)$sumula['chui_aka'] ?></span>)
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark w-100 py-1 text-info border border-secondary small" onclick="lancarEvento('saida', 'aka')" title="2 Saídas concedem 0.5 ao oponente">
                            Jogai (<span id="score_saidas_aka"><?= (int)$sumula['saidas_aka'] ?></span>)
                        </button>
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <button class="btn btn-outline-light btn-sm py-0 w-100 opacity-75" onclick="lancarEvento('hansoku_make', 'aka')">
                        <i class="fas fa-ban me-1"></i> Hansoku-Make (Desclassificar AKA)
                    </button>
                </div>
            </div>
        </div>

        <!-- PAINEL CENTRAL DE CRONÔMETROS -->
        <div class="col-md-2 d-flex flex-column justify-content-between">
            <!-- Cronômetro de Combate Principal -->
            <div class="card bg-dark bg-opacity-90 border-secondary p-3 text-center shadow-lg rounded-3 mb-2">
                <span class="text-secondary small fw-bold text-uppercase">Tempo Regulamentar</span>
                <div id="relogio_luta" class="display-4 fw-mono fw-black text-warning my-2" style="letter-spacing: 2px;">
                    03:00
                </div>
                <div class="d-flex gap-2 justify-content-center mb-2">
                    <button id="btn_toggle_tempo" class="btn btn-success fw-bold px-3" onclick="toggleCronometro()">
                        <i class="fas fa-play me-1" id="icon_toggle_tempo"></i> Hajime
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="resetCronometro()" title="Reiniciar">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
                <span id="badge_fase" class="badge <?= $sumula['golden_score_ativo'] ? 'bg-warning text-dark' : 'bg-primary' ?> text-uppercase small">
                    <?= $sumula['golden_score_ativo'] ? 'Golden Score (1m)' : 'Fase Regular' ?>
                </span>
            </div>

            <!-- Cronômetro de Solo Ne Waza (60s) -->
            <div class="card bg-dark bg-opacity-90 border-secondary p-3 text-center shadow-lg rounded-3 mb-2">
                <span class="text-info small fw-bold text-uppercase">
                    <i class="fas fa-stopwatch me-1"></i> Ne Waza (Solo)
                </span>
                <div id="relogio_solo" class="fs-2 fw-mono fw-black text-info my-1">
                    00s
                </div>
                <div class="d-flex gap-1 justify-content-center">
                    <button id="btn_solo_toggle" class="btn btn-info btn-sm text-dark fw-bold px-2" onclick="toggleCronometroSolo()">
                        <i class="fas fa-play" id="icon_solo_toggle"></i> Solo
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="resetCronometroSolo()">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>
                <small class="text-muted mt-1" style="font-size: 0.65rem;">Max 60s &bull; Chui se passividade</small>
            </div>

            <!-- Golden Score / Prorrogação -->
            <div class="d-grid gap-2">
                <button class="btn btn-outline-warning btn-sm" onclick="ativarGoldenScore()">
                    <i class="fas fa-bolt me-1"></i> Ativar Golden Score
                </button>
                <button class="btn btn-outline-danger btn-sm" onclick="tocarGongo()">
                    <i class="fas fa-bell me-1"></i> Tocar Gongo / Sirene
                </button>
            </div>
        </div>

        <!-- CANTO BRANCO (SHIRO) -->
        <div class="col-md-5 d-flex flex-column">
            <div class="card h-100 bg-secondary bg-opacity-80 text-white border-0 shadow-lg d-flex flex-column justify-content-between p-3 position-relative" style="border-radius: 14px;">
                <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-25 pb-2">
                    <span class="fs-5 fw-black text-uppercase">SHIRO (BRANCO)</span>
                    <span class="badge bg-black bg-opacity-40 px-2 py-1"><?= htmlspecialchars($luta['dojo_shiro'] ?? 'Dojo') ?></span>
                </div>

                <div class="text-center my-2">
                    <h2 class="fw-black mb-0 text-truncate text-uppercase" id="display_nome_shiro" title="<?= htmlspecialchars($luta['nome_shiro'] ?? 'SHIRO') ?>">
                        <?= htmlspecialchars($luta['nome_shiro'] ?? 'SHIRO') ?>
                    </h2>
                    <small class="text-white-50"><?= htmlspecialchars($luta['graduacao_shiro'] ?? '') ?></small>
                </div>

                <!-- Pontos e Waza-ari SHIRO -->
                <div class="bg-black bg-opacity-35 rounded-3 p-3 text-center my-2">
                    <div class="row g-2 align-items-center">
                        <div class="col-6 border-end border-white border-opacity-25">
                            <span class="d-block small text-white-50 text-uppercase fw-bold">Pontos Totais</span>
                            <span id="score_pontos_shiro" class="display-3 fw-black text-warning"><?= number_format($sumula['pontos_shiro'], 1) ?></span>
                        </div>
                        <div class="col-6">
                            <span class="d-block small text-white-50 text-uppercase fw-bold">Waza-ari (0.5 pt)</span>
                            <span id="score_wazaari_shiro" class="display-4 fw-bold"><?= (int)$sumula['wazaari_shiro'] ?></span>
                            <small class="d-block text-white-50" style="font-size: 0.7rem;">(6 = Ippon imediato)</small>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação SHIRO -->
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-warning w-100 py-2 fw-bold text-dark fs-6 shadow-sm" onclick="lancarEvento('wazaari', 'shiro')">
                            <i class="fas fa-plus me-1"></i> WAZA-ARI (+0.5)
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-light w-100 py-2 fw-bold text-primary fs-6 shadow-sm" onclick="lancarEvento('ippon', 'shiro')">
                            <i class="fas fa-star me-1"></i> IPPON (+1.0)
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark w-100 py-1 text-white border border-secondary small" onclick="lancarEvento('shido', 'shiro')">
                            Shido (<span id="score_shido_shiro"><?= (int)$sumula['shido_shiro'] ?></span>)
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark w-100 py-1 text-warning border border-secondary small" onclick="lancarEvento('chui', 'shiro')" title="2 Chui concedem 0.5 ao oponente">
                            Chui (<span id="score_chui_shiro"><?= (int)$sumula['chui_shiro'] ?></span>)
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-dark w-100 py-1 text-info border border-secondary small" onclick="lancarEvento('saida', 'shiro')" title="2 Saídas concedem 0.5 ao oponente">
                            Jogai (<span id="score_saidas_shiro"><?= (int)$sumula['saidas_shiro'] ?></span>)
                        </button>
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <button class="btn btn-outline-light btn-sm py-0 w-100 opacity-75" onclick="lancarEvento('hansoku_make', 'shiro')">
                        <i class="fas fa-ban me-1"></i> Hansoku-Make (Desclassificar SHIRO)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- RODAPÉ: HISTÓRICO DE EVENTOS EM TEMPO REAL -->
    <div class="mt-2 bg-dark bg-opacity-75 p-2 rounded-3 border border-secondary" style="max-height: 120px; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="small fw-bold text-secondary text-uppercase">Trilha Cronológica do Combate:</span>
            <span class="small text-muted" id="contador_eventos"><?= count($eventos) ?> eventos gravados</span>
        </div>
        <div id="lista_eventos" class="small text-white-50">
            <?php if (empty($eventos)): ?>
                <em>Nenhum lance registrado até o momento.</em>
            <?php else: ?>
                <?php foreach (array_reverse($eventos) as $ev): ?>
                    <div>
                        <strong class="text-warning">[<?= sprintf('%02d:%02d', floor($ev['segundo_luta'] / 60), $ev['segundo_luta'] % 60) ?>]</strong>
                        <span class="text-white"><?= htmlspecialchars($ev['descricao']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Finalizar Luta Manualmente -->
<div class="modal fade" id="modalFinalizarLuta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow bg-dark text-white">
            <form method="POST" action="/lutas/<?= $luta['id_luta'] ?>/sumula/finalizar">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">

                <div class="modal-header border-secondary">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-flag-checkered text-danger me-2"></i>Concluir Combate Oficial
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Canto Vencedor: <span class="text-danger">*</span></label>
                        <select name="vencedor_lado" class="form-select bg-dark text-white border-secondary" required>
                            <option value="aka">AKA (Vermelho) - <?= htmlspecialchars($luta['nome_aka'] ?? 'AKA') ?></option>
                            <option value="shiro">SHIRO (Branco) - <?= htmlspecialchars($luta['nome_shiro'] ?? 'SHIRO') ?></option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Forma da Vitória: <span class="text-danger">*</span></label>
                        <select name="forma_vitoria" class="form-select bg-dark text-white border-secondary" required>
                            <option value="pontos">Pontos / Superioridade Regulamentar</option>
                            <option value="ippon">Ippon / Waza-ari Awasete Ippon</option>
                            <option value="golden_score">Golden Score (Ponto de Ouro)</option>
                            <option value="hantei">Decisão dos Árbitros (Hantei)</option>
                            <option value="desclassificacao">Hansoku-make (Desclassificação)</option>
                            <option value="desistencia">Desistência / Kiken</option>
                            <option value="wo">W.O.</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Observações Oficiais da Mesa:</label>
                        <textarea name="motivo" class="form-control bg-dark text-white border-secondary" rows="2" placeholder="Decisão unânime, advertências, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Confirmar Encerramento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Subsídio Hantei (Tameshiwari) -->
<div class="modal fade" id="modalHantei" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fas fa-balance-scale text-warning me-2"></i>Critérios de Desempate (Hantei)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-white-50">
                    Regra Oficial: Em caso de empate rigoroso após o Golden Score, a decisão (Hantei) dos árbitros avalia combatividade, domínio do centro e, subsidiariamente, o total de tábuas quebradas no Tameshiwari:
                </p>
                <div class="row g-3 text-center my-2">
                    <div class="col-6">
                        <div class="p-3 bg-danger bg-opacity-25 rounded border border-danger">
                            <span class="d-block small text-uppercase">AKA (Vermelho)</span>
                            <span class="fs-4 fw-bold"><?= htmlspecialchars($luta['nome_aka'] ?? 'AKA') ?></span>
                            <div class="mt-2 fs-5 fw-bold text-warning"><?= $tameshiwariAka ?> tábua(s) quebrada(s)</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-secondary bg-opacity-25 rounded border border-secondary">
                            <span class="d-block small text-uppercase">SHIRO (Branco)</span>
                            <span class="fs-4 fw-bold"><?= htmlspecialchars($luta['nome_shiro'] ?? 'SHIRO') ?></span>
                            <div class="mt-2 fs-5 fw-bold text-warning"><?= $tameshiwariShiro ?> tábua(s) quebrada(s)</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Atalhos de Teclado -->
<div class="modal fade" id="modalAtalhos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fas fa-keyboard text-info me-2"></i>Atalhos Rápidos de Teclado
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <table class="table table-dark table-sm mb-0">
                    <tbody>
                        <tr><td><kbd>ESPAÇO</kbd></td><td>Iniciar / Pausar Cronômetro de Combate (Hajime / Yame)</td></tr>
                        <tr><td><kbd>S</kbd></td><td>Iniciar / Pausar Cronômetro de Solo Ne Waza (60s)</td></tr>
                        <tr><td><kbd>A</kbd></td><td>+ Waza-ari para AKA (Vermelho)</td></tr>
                        <tr><td><kbd>B</kbd></td><td>+ Waza-ari para SHIRO (Branco)</td></tr>
                        <tr><td><kbd>G</kbd></td><td>Tocar Gongo Oficial</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
var idLuta = <?= (int)$luta['id_luta'] ?>;
var tempoTotal = 180; // 3 minutos regulamentar
var tempoDecorrido = <?= (int)($sumula['tempo_decorrido_segundos'] ?? 0) ?>;
var timerAtivo = false;
var timerInterval = null;

var tempoSolo = 0;
var timerSoloAtivo = false;
var timerSoloInterval = null;

var audioCtx = null;

function tocarGongo() {
    try {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        var osc = audioCtx.createOscillator();
        var gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(440, audioCtx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(110, audioCtx.currentTime + 1.2);
        gain.gain.setValueAtTime(0.8, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 1.2);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + 1.2);
    } catch (e) {}
}

function formatarTempo(segundos) {
    var m = Math.floor(segundos / 60);
    var s = segundos % 60;
    return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
}

function atualizarDisplayTempo() {
    var restante = Math.max(0, tempoTotal - tempoDecorrido);
    document.getElementById('relogio_luta').textContent = formatarTempo(restante);
    if (restante <= 0 && timerAtivo) {
        toggleCronometro();
        tocarGongo();
        alertify.warning('Tempo regulamentar esgotado!');
    }
}

function toggleCronometro() {
    timerAtivo = !timerAtivo;
    var btn = document.getElementById('btn_toggle_tempo');
    var icon = document.getElementById('icon_toggle_tempo');

    if (timerAtivo) {
        btn.className = 'btn btn-danger fw-bold px-3';
        btn.innerHTML = '<i class="fas fa-pause me-1"></i> Yame';
        timerInterval = setInterval(function() {
            tempoDecorrido++;
            atualizarDisplayTempo();
            if (tempoDecorrido % 5 === 0) {
                sincronizarTempoServidor();
            }
        }, 1000);
    } else {
        btn.className = 'btn btn-success fw-bold px-3';
        btn.innerHTML = '<i class="fas fa-play me-1"></i> Hajime';
        clearInterval(timerInterval);
        sincronizarTempoServidor();
    }
}

function resetCronometro() {
    if (confirm('Deseja reiniciar o cronômetro desta luta?')) {
        tempoDecorrido = 0;
        atualizarDisplayTempo();
        sincronizarTempoServidor();
    }
}

function toggleCronometroSolo() {
    timerSoloAtivo = !timerSoloAtivo;
    var btn = document.getElementById('btn_solo_toggle');
    if (timerSoloAtivo) {
        btn.className = 'btn btn-danger btn-sm fw-bold px-2';
        btn.innerHTML = '<i class="fas fa-pause"></i> Parar';
        timerSoloInterval = setInterval(function() {
            tempoSolo++;
            document.getElementById('relogio_solo').textContent = String(tempoSolo).padStart(2, '0') + 's';
            if (tempoSolo >= 60) {
                toggleCronometroSolo();
                tocarGongo();
                alertify.error('Tempo máximo de Ne Waza (60s) atingido! Levante os atletas (Matte).');
            }
        }, 1000);
    } else {
        btn.className = 'btn btn-info btn-sm text-dark fw-bold px-2';
        btn.innerHTML = '<i class="fas fa-play"></i> Solo';
        clearInterval(timerSoloInterval);
    }
}

function resetCronometroSolo() {
    tempoSolo = 0;
    document.getElementById('relogio_solo').textContent = '00s';
    if (timerSoloAtivo) toggleCronometroSolo();
}

function ativarGoldenScore() {
    if (confirm('Ativar prorrogação de Golden Score (Ponto de Ouro de 1 minuto)?')) {
        tempoTotal = 60;
        tempoDecorrido = 0;
        document.getElementById('badge_fase').className = 'badge bg-warning text-dark text-uppercase small';
        document.getElementById('badge_fase').textContent = 'Golden Score (1m)';
        atualizarDisplayTempo();
        lancarEvento('golden_score', 'geral', 'Início da prorrogação em Golden Score.');
    }
}

function sincronizarTempoServidor() {
    $.post('/lutas/' + idLuta + '/sumula/evento', {
        tipo: 'tempo',
        lado: 'geral',
        segundo_luta: tempoDecorrido
    });
}

function lancarEvento(tipo, lado, descricao = '') {
    $.post('/lutas/' + idLuta + '/sumula/evento', {
        tipo: tipo,
        lado: lado,
        segundo_luta: tempoDecorrido,
        descricao: descricao
    }, function(res) {
        if (res.success) {
            var sumula = res.sumula;
            // Atualiza placares visuais
            document.getElementById('score_pontos_aka').textContent = parseFloat(sumula.pontos_aka).toFixed(1);
            document.getElementById('score_wazaari_aka').textContent = sumula.wazaari_aka;
            document.getElementById('score_shido_aka').textContent = sumula.shido_aka;
            document.getElementById('score_chui_aka').textContent = sumula.chui_aka;
            document.getElementById('score_saidas_aka').textContent = sumula.saidas_aka;

            document.getElementById('score_pontos_shiro').textContent = parseFloat(sumula.pontos_shiro).toFixed(1);
            document.getElementById('score_wazaari_shiro').textContent = sumula.wazaari_shiro;
            document.getElementById('score_shido_shiro').textContent = sumula.shido_shiro;
            document.getElementById('score_chui_shiro').textContent = sumula.chui_shiro;
            document.getElementById('score_saidas_shiro').textContent = sumula.saidas_shiro;

            // Atualiza histórico de eventos
            var lista = document.getElementById('lista_eventos');
            var html = '';
            res.eventos.slice(-8).reverse().forEach(function(ev) {
                var m = Math.floor(ev.segundo_luta / 60);
                var s = ev.segundo_luta % 60;
                var timeStr = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                html += '<div><strong class="text-warning">[' + timeStr + ']</strong> <span class="text-white">' + ev.descricao + '</span></div>';
            });
            lista.innerHTML = html;
            document.getElementById('contador_eventos').textContent = res.eventos.length + ' eventos gravados';

            // Checa avaliação automática (6 Waza-ari, Golden Score, etc)
            if (res.avaliacao && res.avaliacao.finalizada) {
                tocarGongo();
                if (timerAtivo) toggleCronometro();
                alertify.alert('Combate Encerrado!', 'Vitória do canto ' + res.avaliacao.vencedor_lado.toUpperCase() + ' por ' + res.avaliacao.motivo, function() {
                    window.location.reload();
                });
            }
        }
    }).fail(function() {
        alertify.error('Erro de conexão ao registrar lance.');
    });
}

// Atalhos de teclado
document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') return;
    if (e.code === 'Space') {
        e.preventDefault();
        toggleCronometro();
    } else if (e.code === 'KeyS') {
        e.preventDefault();
        toggleCronometroSolo();
    } else if (e.code === 'KeyA') {
        lancarEvento('wazaari', 'aka');
    } else if (e.code === 'KeyB') {
        lancarEvento('wazaari', 'shiro');
    } else if (e.code === 'KeyG') {
        tocarGongo();
    }
});

atualizarDisplayTempo();
</script>
