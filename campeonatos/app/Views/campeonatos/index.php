<?php
use App\Core\Router;
$base = Router::getBasePath();
?>
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold text-white mb-1">Campeonatos de Artes Marciais</h2>
            <p class="text-muted mb-0">Gerencie todas as edições, inscrições e fases do evento</p>
        </div>
        <div>
            <a href="<?= $base ?>/campeonatos/novo" class="btn btn-kenshy">
                <i class="fa-solid fa-plus me-1"></i> Cadastrar Novo Campeonato
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card-kenshy">
            <div class="card-header-kenshy">
                <span><i class="fa-solid fa-trophy text-danger me-2"></i>Eventos Registrados</span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Título do Campeonato</th>
                                <th>Edição / Ano</th>
                                <th>Data Início</th>
                                <th>Cidade / Local</th>
                                <th>Categorias</th>
                                <th>Inscritos</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($campeonatos as $c): ?>
                                <tr>
                                    <td><strong><?= $c['id_campeonato'] ?></strong></td>
                                    <td>
                                        <a href="<?= $base ?>/campeonatos/<?= $c['id_campeonato'] ?>" class="text-white fw-bold text-decoration-none">
                                            <?= htmlspecialchars($c['titulo']) ?>
                                        </a>
                                        <div class="text-muted small"><?= htmlspecialchars($c['organizador']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($c['edicao'] ?: '-') ?> (<?= $c['ano'] ?>)</td>
                                    <td><?= date('d/m/Y', strtotime($c['data_inicio'])) ?></td>
                                    <td><?= htmlspecialchars($c['cidade'] . ' - ' . $c['estado']) ?></td>
                                    <td><span class="badge bg-secondary"><?= $c['total_categorias'] ?></span></td>
                                    <td><span class="badge bg-info text-dark"><?= $c['total_inscritos'] ?></span></td>
                                    <td>
                                        <span class="badge-status badge-status-<?= $c['status'] ?>">
                                            <?= str_replace('_', ' ', $c['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= $base ?>/campeonatos/<?= $c['id_campeonato'] ?>" class="btn btn-sm btn-kenshy me-1" title="Gerenciar Campeonato">
                                            <i class="fa-solid fa-gauge me-1"></i> Painel
                                        </a>
                                        <a href="<?= $base ?>/campeonatos/<?= $c['id_campeonato'] ?>/editar" class="btn btn-sm btn-outline-secondary" title="Editar Informações">
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
