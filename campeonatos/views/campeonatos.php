<?php
$pageTitle = 'Campeonatos | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";

$campeonatoModel = new Campeonato();
$campeonatos = $campeonatoModel->buscarTodos();
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-trophy text-danger me-2"></i>Campeonatos Oficiais
        </h2>
        <p class="text-muted small mb-0">Relação completa de eventos da Federação Kenshydokan Jutsu</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="criar_campeonato.php" class="btn btn-danger">
            <i class="fas fa-plus me-1"></i> Novo Campeonato
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Campeonato</th>
                        <th>Período</th>
                        <th>Data Ref. Idade</th>
                        <th>Local</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($campeonatos)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="fas fa-trophy fa-3x text-secondary opacity-50 mb-3"></i>
                                    <h5 class="fw-bold text-dark mb-1">Nenhum campeonato cadastrado ainda</h5>
                                    <p class="text-muted small mb-3">Cadastre a primeira edição de um evento esportivo oficial.</p>
                                    <a href="criar_campeonato.php" class="btn btn-danger btn-sm">
                                        <i class="fas fa-plus me-1"></i> Criar Primeiro Campeonato
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($campeonatos as $idx => $c): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <a href="detalhes_campeonato.php?id=<?= $c['id_campeonato'] ?>" class="fw-bold text-dark text-decoration-none">
                                        <?= htmlspecialchars($c['titulo']) ?>
                                    </a>
                                    <small class="text-muted d-block"><?= htmlspecialchars($c['edicao'] ?? '') ?> (<?= $c['ano'] ?>)</small>
                                </td>
                                <td class="small">
                                    <?= date('d/m/Y', strtotime($c['data_inicio'])) ?> a <?= date('d/m/Y', strtotime($c['data_fim'])) ?>
                                </td>
                                <td class="small">
                                    <?= date('d/m/Y', strtotime($c['data_referencia_idade'])) ?>
                                </td>
                                <td class="small">
                                    <strong><?= htmlspecialchars($c['cidade']) ?>/<?= htmlspecialchars($c['estado']) ?></strong><br>
                                    <span class="text-muted"><?= htmlspecialchars($c['local_nome'] ?? '') ?></span>
                                </td>
                                <td>
                                    <?php
                                    $st = $c['status'];
                                    $bg = 'bg-secondary';
                                    if ($st === 'inscricoes_abertas') $bg = 'bg-success';
                                    elseif ($st === 'em_andamento') $bg = 'bg-danger pulse-glow';
                                    elseif ($st === 'pesagem') $bg = 'bg-warning text-dark';
                                    elseif ($st === 'chaveamento') $bg = 'bg-info text-dark';
                                    elseif ($st === 'finalizado') $bg = 'bg-dark';
                                    ?>
                                    <span class="badge <?= $bg ?> text-uppercase"><?= str_replace('_', ' ', $st) ?></span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="detalhes_campeonato.php?id=<?= $c['id_campeonato'] ?>" class="btn btn-sm btn-danger">
                                        <i class="fas fa-cog me-1"></i> Gerenciar
                                    </a>
                                    <a href="editar_campeonato.php?id=<?= $c['id_campeonato'] ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
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
