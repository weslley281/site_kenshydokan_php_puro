<div class="container-fluid p-3 vh-100 d-flex flex-column justify-content-between text-white">
    <!-- Header do Telão -->
    <header class="d-flex justify-content-between align-items-center border-bottom border-dark pb-2 mb-2">
        <div class="d-flex align-items-center">
            <span class="badge bg-danger fs-5 me-3 px-3 py-2 fw-bold text-uppercase"><?= htmlspecialchars($tatame['nome']) ?></span>
            <div>
                <h4 class="mb-0 fw-bold text-white"><?= htmlspecialchars($campeonato['titulo']) ?></h4>
                <small class="text-secondary">Circuito Oficial Kenshydokan Jutsu &bull; Placar Oficial</small>
            </div>
        </div>
        <div class="text-end">
            <div id="relogioLocal" class="fs-4 fw-mono text-warning fw-bold">--:--:--</div>
            <button class="btn btn-sm btn-outline-secondary py-0" onclick="toggleFullScreen()" title="Alternar Tela Cheia">
                <i class="fas fa-expand me-1"></i> Tela Cheia
            </button>
        </div>
    </header>

    <?php if ($lutaAtual): ?>
        <!-- Informações do Confronto e Categoria -->
        <div class="text-center mb-2">
            <span class="badge bg-dark border border-secondary fs-6 px-4 py-1 text-uppercase">
                Luta #<?= $lutaAtual['numero_luta'] ?> &bull; <?= htmlspecialchars($lutaAtual['categoria_nome'] ?? 'Kumite Oficial') ?> &bull; <?= $lutaAtual['rodada'] ?>ª Rodada
            </span>
        </div>

        <!-- Placar Principal Gigante (AKA vs SHIRO) -->
        <div class="row flex-grow-1 g-3 align-items-stretch">
            <!-- CANTO VERMELHO (AKA) -->
            <div class="col-md-5 d-flex flex-column">
                <div class="card h-100 bg-danger text-white border-0 shadow-lg d-flex flex-column justify-content-between p-4 position-relative overflow-hidden" style="border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-25 pb-2">
                        <span class="fs-4 fw-black text-uppercase tracking-wider">AKA (VERMELHO)</span>
                        <span class="badge bg-black bg-opacity-40 fs-6 px-3 py-1"><?= htmlspecialchars($lutaAtual['dojo_aka'] ?? 'Dojo') ?></span>
                    </div>

                    <div class="my-auto text-center py-3">
                        <h1 class="display-4 fw-black mb-1 text-truncate text-uppercase" style="letter-spacing: 1px;" title="<?= htmlspecialchars($lutaAtual['nome_aka'] ?? 'AKA') ?>">
                            <?= htmlspecialchars($lutaAtual['nome_aka'] ?? 'AKA') ?>
                        </h1>
                        <div class="fs-5 text-white-50"><?= htmlspecialchars($lutaAtual['graduacao_aka'] ?? '') ?></div>
                    </div>

                    <div class="bg-black bg-opacity-30 rounded-3 p-3 text-center">
                        <div class="row g-2 align-items-center">
                            <div class="col-6 border-end border-white border-opacity-25">
                                <span class="d-block small text-white-50 text-uppercase fw-bold">Pontuação</span>
                                <span id="bigscore_pontos_aka" class="display-3 fw-black text-warning">0.0</span>
                            </div>
                            <div class="col-6">
                                <span class="d-block small text-white-50 text-uppercase fw-bold">Waza-ari</span>
                                <span id="bigscore_wazaari_aka" class="display-4 fw-bold">0</span>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top border-white border-opacity-25 d-flex justify-content-around small">
                            <span>Shido: <strong id="bigscore_shido_aka">0</strong></span>
                            <span>Chui: <strong id="bigscore_chui_aka">0</strong></span>
                            <span>Saídas: <strong id="bigscore_saidas_aka">0</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CENTRO: CRONÔMETRO CENTRAL GIGANTE -->
            <div class="col-md-2 d-flex flex-column justify-content-center align-items-center text-center">
                <div class="p-3 w-100 bg-dark bg-opacity-75 rounded-4 border border-secondary shadow d-flex flex-column justify-content-center align-items-center h-100">
                    <span class="text-secondary small fw-bold text-uppercase mb-2">Tempo de Luta</span>
                    <div id="bigscore_cronometro" class="display-3 fw-mono fw-black text-warning my-2" style="letter-spacing: 2px;">
                        03:00
                    </div>
                    <div id="bigscore_fase" class="badge bg-secondary fs-7 text-uppercase mb-3 px-3 py-1">
                        Regulamentar
                    </div>

                    <hr class="w-100 border-secondary my-2">

                    <span class="text-secondary small fw-bold text-uppercase">Tempo de Chão (Ne Waza)</span>
                    <div id="bigscore_timer_solo" class="fs-2 fw-mono fw-bold text-info my-1">
                        00s
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">Limite Máx: 60s</small>
                </div>
            </div>

            <!-- CANTO BRANCO (SHIRO) -->
            <div class="col-md-5 d-flex flex-column">
                <div class="card h-100 bg-secondary bg-opacity-75 text-white border-0 shadow-lg d-flex flex-column justify-content-between p-4 position-relative overflow-hidden" style="border-radius: 16px;">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-25 pb-2">
                        <span class="fs-4 fw-black text-uppercase tracking-wider">SHIRO (BRANCO)</span>
                        <span class="badge bg-black bg-opacity-40 fs-6 px-3 py-1"><?= htmlspecialchars($lutaAtual['dojo_shiro'] ?? 'Dojo') ?></span>
                    </div>

                    <div class="my-auto text-center py-3">
                        <h1 class="display-4 fw-black mb-1 text-truncate text-uppercase" style="letter-spacing: 1px;" title="<?= htmlspecialchars($lutaAtual['nome_shiro'] ?? 'SHIRO') ?>">
                            <?= htmlspecialchars($lutaAtual['nome_shiro'] ?? 'SHIRO') ?>
                        </h1>
                        <div class="fs-5 text-white-50"><?= htmlspecialchars($lutaAtual['graduacao_shiro'] ?? '') ?></div>
                    </div>

                    <div class="bg-black bg-opacity-30 rounded-3 p-3 text-center">
                        <div class="row g-2 align-items-center">
                            <div class="col-6 border-end border-white border-opacity-25">
                                <span class="d-block small text-white-50 text-uppercase fw-bold">Pontuação</span>
                                <span id="bigscore_pontos_shiro" class="display-3 fw-black text-warning">0.0</span>
                            </div>
                            <div class="col-6">
                                <span class="d-block small text-white-50 text-uppercase fw-bold">Waza-ari</span>
                                <span id="bigscore_wazaari_shiro" class="display-4 fw-bold">0</span>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top border-white border-opacity-25 d-flex justify-content-around small">
                            <span>Shido: <strong id="bigscore_shido_shiro">0</strong></span>
                            <span>Chui: <strong id="bigscore_chui_shiro">0</strong></span>
                            <span>Saídas: <strong id="bigscore_saidas_shiro">0</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fas fa-torii-gate fa-5x text-danger mb-4 opacity-75"></i>
            <h1 class="display-3 fw-bold text-white mb-2"><?= htmlspecialchars($tatame['nome']) ?></h1>
            <h3 class="text-secondary fw-normal mb-4">Área aguardando próximo combate oficial</h3>
            <span class="badge bg-success fs-5 px-4 py-2">TATAME LIVRE</span>
        </div>
    <?php endif; ?>

    <!-- Rodapé: Fila de Próximos Atletas Chamados -->
    <footer class="border-top border-dark pt-2 mt-2">
        <div class="d-flex align-items-center">
            <span class="badge bg-danger me-2 text-uppercase fw-bold">Próximos:</span>
            <div class="text-truncate text-secondary small">
                <?php if (empty($proximasLutas)): ?>
                    <span>Nenhum combate na fila de chamada imediata.</span>
                <?php else: ?>
                    <?php foreach ($proximasLutas as $idx => $pl): ?>
                        <span class="me-3">
                            <strong>#<?= $pl['numero_luta'] ?>:</strong> 
                            <span class="text-danger"><?= htmlspecialchars($pl['nome_aka'] ?? 'AKA') ?></span> vs 
                            <span class="text-white"><?= htmlspecialchars($pl['nome_shiro'] ?? 'SHIRO') ?></span>
                            (<?= htmlspecialchars($pl['categoria_nome'] ?? '') ?>)
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div>

<script>
function atualizarRelogio() {
    var now = new Date();
    var h = String(now.getHours()).padStart(2, '0');
    var m = String(now.getMinutes()).padStart(2, '0');
    var s = String(now.getSeconds()).padStart(2, '0');
    var el = document.getElementById('relogioLocal');
    if (el) el.textContent = h + ':' + m + ':' + s;
}
setInterval(atualizarRelogio, 1000);
atualizarRelogio();

function toggleFullScreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {});
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}

// Sincronização automática com a mesa via polling a cada 3 segundos
setInterval(function() {
    // Se não estiver em tela cheia, pode recarregar suavemente a página
    window.location.reload();
}, 4000);
</script>
