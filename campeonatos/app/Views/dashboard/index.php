<?php
use App\Core\Router;
$base = Router::getBasePath();
?>
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-white mb-1">Painel Geral de Controle</h2>
            <p class="text-muted mb-0">Gestão integrada de competições esportivas Kenshydokan Jutsu</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= $base ?>/campeonatos/novo" class="btn btn-kenshy">
                <i class="fa-solid fa-plus me-1"></i> Novo Campeonato
            </a>
            <a href="<?= $base ?>/validar-certificado" class="btn btn-outline-secondary">
                <i class="fa-solid fa-stamp me-1"></i> Validar Certificado
            </a>
        </div>
    </div>
</div>

<!-- CARDS ESTATÍSTICOS -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-kenshy p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Campeonatos</span>
                    <h3 class="fw-bold text-white mt-2 mb-0"><?= $totalCampeonatos ?></h3>
                </div>
                <div class="p-3 bg-danger bg-opacity-10 rounded-3 text-danger">
                    <i class="fa-solid fa-trophy fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-kenshy p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Atletas Inscritos</span>
                    <h3 class="fw-bold text-white mt-2 mb-0"><?= $totalAtletas ?></h3>
                </div>
                <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info">
                    <i class="fa-solid fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-kenshy p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Total de Lutas</span>
                    <h3 class="fw-bold text-white mt-2 mb-0"><?= $totalLutas ?></h3>
                </div>
                <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning">
                    <i class="fa-solid fa-hand-fist fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-kenshy p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Áreas de Tatame</span>
                    <h3 class="fw-bold text-white mt-2 mb-0"><?= $totalTatames ?></h3>
                </div>
                <div class="p-3 bg-success bg-opacity-10 rounded-3 text-success">
                    <i class="fa-solid fa-square-full fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- LUTAS AO VIVO OU EM ANDAMENTO -->
    <div class="col-lg-8">
        <div class="card-kenshy h-100">
            <div class="card-header-kenshy">
                <span><i class="fa-solid fa-bolt text-danger me-2"></i>Lutas ao Vivo nos Tatames</span>
                <span class="badge bg-danger">Tempo Real</span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($lutasAndamento)): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tatame</th>
                                    <th>Luta</th>
                                    <th>Categoria</th>
                                    <th>Lado Vermelho (AKA)</th>
                                    <th>Lado Branco (SHIRO)</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lutasAndamento as $luta): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($luta['tatame_nome'] ?? 'Geral') ?></span></td>
                                        <td><strong>#<?= $luta['numero_luta'] ?></strong></td>
                                        <td><?= htmlspecialchars($luta['categoria_nome']) ?></td>
                                        <td class="text-danger fw-bold"><?= htmlspecialchars($luta['aka_nome'] ?? '-') ?></td>
                                        <td class="text-light fw-bold"><?= htmlspecialchars($luta['shiro_nome'] ?? '-') ?></td>
                                        <td>
                                            <a href="<?= $base ?>/sumula/kumite/<?= $luta['id_luta'] ?>" class="btn btn-sm btn-kenshy">
                                                <i class="fa-solid fa-play me-1"></i> Súmula
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-hourglass-empty fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0">Nenhuma luta em andamento no momento.</p>
                        <small>Inicie um tatame para exibir os combates ao vivo.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- GRÁFICO DE MODALIDADES -->
    <div class="col-lg-4">
        <div class="card-kenshy h-100">
            <div class="card-header-kenshy">
                <span><i class="fa-solid fa-chart-pie text-warning me-2"></i>Distribuição por Modalidade</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-4">
                <canvas id="chartModalidades" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- LISTA DE CAMPEONATOS -->
<div class="row g-4">
    <div class="col-12">
        <div class="card-kenshy">
            <div class="card-header-kenshy">
                <span><i class="fa-solid fa-medal text-danger me-2"></i>Campeonatos Cadastrados</span>
                <a href="<?= $base ?>/campeonatos" class="btn btn-sm btn-outline-secondary">Ver Todos</a>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Título do Evento</th>
                                <th>Data de Realização</th>
                                <th>Local</th>
                                <th>Categorias</th>
                                <th>Inscritos</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($campeonatos as $c): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $base ?>/campeonatos/<?= $c['id_campeonato'] ?>" class="text-white fw-bold text-decoration-none">
                                            <?= htmlspecialchars($c['titulo']) ?>
                                        </a>
                                        <div class="text-muted small"><?= htmlspecialchars($c['edicao'] ?? '') ?></div>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($c['data_inicio'])) ?></td>
                                    <td><?= htmlspecialchars($c['cidade'] . '/' . $c['estado']) ?></td>
                                    <td><span class="badge bg-secondary"><?= $c['total_categorias'] ?> categorias</span></td>
                                    <td><span class="badge bg-info text-dark"><?= $c['total_inscritos'] ?> atletas</span></td>
                                    <td>
                                        <span class="badge-status badge-status-<?= $c['status'] ?>">
                                            <?= str_replace('_', ' ', $c['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= $base ?>/campeonatos/<?= $c['id_campeonato'] ?>" class="btn btn-sm btn-outline-light me-1" title="Ver Painel">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= $base ?>/campeonatos/<?= $c['id_campeonato'] ?>/editar" class="btn btn-sm btn-outline-secondary" title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartModalidades').getContext('2d');
        const labels = <?= json_encode(array_map(fn($m) => strtoupper($m['modalidade']), $distModalidades)) ?>;
        const data = <?= json_encode(array_map(fn($m) => (int)$m['total'], $distModalidades)) ?>;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels.length ? labels : ['KUMITE', 'KATA', 'TAMESHIWARI'],
                datasets: [{
                    data: data.length ? data : [1, 1, 1],
                    backgroundColor: ['#ef4444', '#f59e0b', '#0ea5e9'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94a3b8', font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>
