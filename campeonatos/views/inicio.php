<?php
$pageTitle = 'Painel Geral | Campeonatos Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";

$campeonatoModel = new Campeonato();
$campeonatos = $campeonatoModel->buscarTodos();

$totalEventos = count($campeonatos);
$eventosAtivos = count(array_filter($campeonatos, fn($c) => in_array($c['status'], ['inscricoes_abertas', 'pesagem', 'chaveamento', 'em_andamento'])));
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-torii-gate text-danger me-2"></i>Gestão Oficial de Campeonatos
        </h2>
        <p class="text-muted small mb-0">Sistema Oficial da Federação Kenshydokan Jutsu &bull; Regras Oficiais</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="criar_campeonato.php" class="btn btn-danger">
            <i class="fas fa-plus-circle me-1"></i> Criar Novo Campeonato
        </a>
    </div>
</div>

<!-- Cards de Métricas -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded">
                        <i class="fas fa-trophy fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Total de Eventos</h6>
                        <span class="fs-4 fw-bold text-dark"><?= $totalEventos ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success p-3 rounded">
                        <i class="fas fa-fist-raised fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Eventos em Andamento / Abertos</h6>
                        <span class="fs-4 fw-bold text-success"><?= $eventosAtivos ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm border-start border-danger border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger bg-opacity-10 text-danger p-3 rounded">
                        <i class="fas fa-shield-alt fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Padrão Esportivo</h6>
                        <span class="fs-4 fw-bold text-danger">Kenshydokan Puro</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Campeonatos Recentes -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-calendar-alt text-danger me-2"></i>Eventos e Campeonatos Cadastrados
        </h5>
        <a href="campeonatos.php" class="btn btn-sm btn-outline-secondary">Ver Todos</a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 50px;">#</th>
                        <th>Campeonato</th>
                        <th>Data do Evento</th>
                        <th>Cidade / Local</th>
                        <th>Status</th>
                        <th class="text-end pe-3" style="width: 220px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($campeonatos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-trophy fa-3x text-secondary opacity-50 mb-3"></i>
                                <p class="mb-0">Nenhum campeonato cadastrado ainda.</p>
                                <a href="criar_campeonato.php" class="btn btn-sm btn-danger mt-2">Cadastrar Primeiro Evento</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($campeonatos as $idx => $camp): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <a href="detalhes_campeonato.php?id=<?= $camp['id_campeonato'] ?>" class="fw-bold text-dark text-decoration-none">
                                        <?= htmlspecialchars($camp['titulo']) ?>
                                    </a>
                                    <small class="text-muted d-block"><?= htmlspecialchars($camp['edicao'] ?? 'Edição Oficial') ?> &bull; <?= $camp['ano'] ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-calendar me-1 text-danger"></i>
                                        <?= date('d/m/Y', strtotime($camp['data_inicio'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold"><?= htmlspecialchars($camp['cidade']) ?>-<?= htmlspecialchars($camp['estado']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($camp['local_nome']) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $st = $camp['status'];
                                    $bg = 'bg-secondary';
                                    if ($st === 'inscricoes_abertas') $bg = 'bg-success';
                                    elseif ($st === 'em_andamento') $bg = 'bg-danger';
                                    elseif ($st === 'pesagem') $bg = 'bg-warning text-dark';
                                    elseif ($st === 'chaveamento') $bg = 'bg-info text-dark';
                                    elseif ($st === 'finalizado') $bg = 'bg-dark';
                                    ?>
                                    <span class="badge <?= $bg ?> text-uppercase"><?= str_replace('_', ' ', $st) ?></span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="detalhes_campeonato.php?id=<?= $camp['id_campeonato'] ?>" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-cogs me-1"></i> Gerenciar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
