<?php
include_once __DIR__ . "/../models/tatameModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/lutaModel.php";

$idTatame = (int)($_GET['id'] ?? 0);
$tatameModel = new Tatame();
$tatame = $tatameModel->buscarPorId($idTatame);

if (!$tatame) {
    die("Tatame não encontrado.");
}

$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId((int)$tatame['id_campeonato']);

$lutaModel = new Luta();
$lutaAtual = !empty($tatame['luta_em_andamento_id']) ? $lutaModel->buscarPorId((int)$tatame['luta_em_andamento_id']) : null;
$proximasLutas = $lutaModel->buscarPorTatame($idTatame, 'chamada');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Telão <?= htmlspecialchars($tatame['nome']) ?> | Kenshydokan</title>

    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/custom.css">

    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            overflow: hidden;
            height: 100vh;
        }
        .fw-black {
            font-weight: 900;
        }
        .fw-mono {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .card-aka {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
            border-radius: 20px;
        }
        .card-shiro {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="container-fluid p-3 h-100 d-flex flex-column justify-content-between">
    <!-- Topo -->
    <header class="d-flex justify-content-between align-items-center border-bottom border-dark pb-2">
        <div class="d-flex align-items-center">
            <span class="badge bg-danger fs-5 me-3 px-3 py-2 fw-bold text-uppercase"><?= htmlspecialchars($tatame['nome']) ?></span>
            <div>
                <h4 class="mb-0 fw-bold text-white"><?= htmlspecialchars($campeonato['titulo']) ?></h4>
                <small class="text-secondary">Circuito Oficial Kenshydokan Jutsu &bull; Placar Oficial</small>
            </div>
        </div>
        <div class="text-end">
            <div id="relogioLocal" class="fs-4 fw-mono text-warning fw-bold">--:--:--</div>
            <button class="btn btn-sm btn-outline-secondary py-0 text-white" onclick="toggleFullScreen()">
                <i class="fas fa-expand me-1"></i> Tela Cheia
            </button>
        </div>
    </header>

    <?php if ($lutaAtual): ?>
        <div class="text-center my-1">
            <span class="badge bg-dark border border-secondary fs-6 px-4 py-1 text-uppercase">
                Luta #<?= $lutaAtual['numero_luta'] ?> &bull; <?= htmlspecialchars($lutaAtual['categoria_nome'] ?? '') ?> &bull; <?= $lutaAtual['rodada'] ?>ª Rodada
            </span>
        </div>

        <!-- Placar Gigante -->
        <div class="row flex-grow-1 g-3 align-items-stretch my-1">
            <!-- CANTO VERMELHO (AKA) -->
            <div class="col-md-5 d-flex flex-column">
                <div class="card h-100 card-aka text-white border-0 shadow-lg d-flex flex-column justify-content-between p-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-25 pb-2">
                        <span class="fs-4 fw-black text-uppercase">AKA (VERMELHO)</span>
                        <span class="badge bg-black bg-opacity-40 fs-6 px-3 py-1"><?= htmlspecialchars($lutaAtual['dojo_aka'] ?? 'Dojo') ?></span>
                    </div>

                    <div class="my-auto text-center py-2">
                        <h1 class="display-4 fw-black mb-1 text-truncate text-uppercase">
                            <?= htmlspecialchars($lutaAtual['nome_aka'] ?? 'AKA') ?>
                        </h1>
                        <div class="fs-5 text-white-50"><?= htmlspecialchars($lutaAtual['graduacao_aka'] ?? '') ?></div>
                    </div>

                    <div class="bg-black bg-opacity-30 rounded-3 p-3 text-center">
                        <span class="d-block small text-white-50 text-uppercase fw-bold">Pontuação Oficial</span>
                        <span class="display-3 fw-black text-warning">PONTOS</span>
                    </div>
                </div>
            </div>

            <!-- CENTRO: CRONÔMETRO -->
            <div class="col-md-2 d-flex flex-column justify-content-center align-items-center text-center">
                <div class="p-3 w-100 bg-dark bg-opacity-75 rounded-4 border border-secondary shadow d-flex flex-column justify-content-center align-items-center h-100">
                    <span class="text-secondary small fw-bold text-uppercase mb-2">Tempo de Luta</span>
                    <div class="display-4 fw-mono fw-black text-warning my-2">03:00</div>
                    <span class="badge bg-secondary fs-7 text-uppercase mb-3 px-3 py-1">Regulamentar</span>

                    <hr class="w-100 border-secondary my-2">

                    <span class="text-secondary small fw-bold text-uppercase">Ne Waza (Solo)</span>
                    <div class="fs-2 fw-mono fw-bold text-info my-1">00s</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Limite Máx: 60s</small>
                </div>
            </div>

            <!-- CANTO BRANCO (SHIRO) -->
            <div class="col-md-5 d-flex flex-column">
                <div class="card h-100 card-shiro text-white border-0 shadow-lg d-flex flex-column justify-content-between p-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-white border-opacity-25 pb-2">
                        <span class="fs-4 fw-black text-uppercase">SHIRO (BRANCO)</span>
                        <span class="badge bg-black bg-opacity-40 fs-6 px-3 py-1"><?= htmlspecialchars($lutaAtual['dojo_shiro'] ?? 'Dojo') ?></span>
                    </div>

                    <div class="my-auto text-center py-2">
                        <h1 class="display-4 fw-black mb-1 text-truncate text-uppercase">
                            <?= htmlspecialchars($lutaAtual['nome_shiro'] ?? 'SHIRO') ?>
                        </h1>
                        <div class="fs-5 text-white-50"><?= htmlspecialchars($lutaAtual['graduacao_shiro'] ?? '') ?></div>
                    </div>

                    <div class="bg-black bg-opacity-30 rounded-3 p-3 text-center">
                        <span class="d-block small text-white-50 text-uppercase fw-bold">Pontuação Oficial</span>
                        <span class="display-3 fw-black text-warning">PONTOS</span>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center">
            <i class="fas fa-torii-gate fa-5x text-danger mb-4 opacity-75"></i>
            <h1 class="display-3 fw-bold text-white mb-2"><?= htmlspecialchars($tatame['nome']) ?></h1>
            <h3 class="text-secondary fw-normal mb-4">Área aguardando próximo combate oficial</h3>
            <span class="badge bg-success fs-5 px-4 py-2">TATAME DISPONÍVEL</span>
        </div>
    <?php endif; ?>

    <!-- Rodapé -->
    <footer class="border-top border-dark pt-2">
        <div class="d-flex align-items-center">
            <span class="badge bg-danger me-2 text-uppercase fw-bold">Próximos:</span>
            <div class="text-truncate text-secondary small">
                <?php if (empty($proximasLutas)): ?>
                    <span>Nenhum combate na fila de chamada imediata.</span>
                <?php else: ?>
                    <?php foreach ($proximasLutas as $pl): ?>
                        <span class="me-3">
                            <strong>#<?= $pl['numero_luta'] ?>:</strong> 
                            <span class="text-danger"><?= htmlspecialchars($pl['nome_aka'] ?? 'AKA') ?></span> vs 
                            <span class="text-white"><?= htmlspecialchars($pl['nome_shiro'] ?? 'SHIRO') ?></span>
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
        if (document.exitFullscreen) document.exitFullscreen();
    }
}

// Auto-sync a cada 4 segundos
setInterval(function() {
    window.location.reload();
}, 4000);
</script>
</body>
</html>
