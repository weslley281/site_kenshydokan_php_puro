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

<!-- Hero Banner Marcial Oficial -->
<div class="hero-kenshy">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-danger bg-opacity-25 border border-danger border-opacity-50 text-white small mb-3">
                <i class="fas fa-shield-alt text-danger"></i>
                <span class="fw-bold tracking-wider text-uppercase" style="font-size: 0.72rem;">Federação Oficial Kenshydokan Jutsu</span>
            </div>
            <h1 class="display-6 fw-bold mb-2 text-white">Sistema de Competições e Súmula Digital</h1>
            <p class="text-white-50 mb-4 fs-6" style="max-width: 650px;">
                Gestão integral de campeonatos marciais: inscrições com snapshot imutável, pesagem oficial com balança digital, chaves eliminatórias com potências de 2 e súmula eletrônica ao vivo.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="criar_campeonato.php" class="btn btn-danger btn-lg shadow">
                    <i class="fas fa-plus-circle me-1"></i> Criar Novo Campeonato
                </a>
                <a href="campeonatos.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-list me-1"></i> Listar Todos os Eventos
                </a>
            </div>
        </div>
        <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0 d-none d-lg-block">
            <div class="p-4 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 d-inline-block text-start backdrop-blur" style="backdrop-filter: blur(8px);">
                <div class="small text-uppercase tracking-wider text-white-50 fw-bold mb-1">Status Operacional</div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success p-2 rounded-circle" style="width: 10px; height: 10px;"></span>
                    <span class="fw-bold text-white fs-6">SISTEMA ATIVO</span>
                </div>
                <div class="small text-white-50">Subdomínio: <code>campeonatos.kenshydokan.org.br</code></div>
                <div class="small text-white-50 mt-1">Regras: Livro Oficial Kenshydokan Jutsu</div>
            </div>
        </div>
    </div>
</div>

<!-- Cards de Métricas Principais (KPIs) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="kpi-card kpi-blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Campeonatos Cadastrados</div>
                    <div class="kpi-value text-primary"><?= $totalEventos ?></div>
                    <div class="small text-muted mt-1">Eventos esportivos registrados</div>
                </div>
                <div class="kpi-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="kpi-card kpi-green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Eventos em Andamento / Abertos</div>
                    <div class="kpi-value text-success"><?= $eventosAtivos ?></div>
                    <div class="small text-muted mt-1">Inscrições ou combates ativos</div>
                </div>
                <div class="kpi-icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-fist-raised"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="kpi-label">Regulamento Esportivo</div>
                    <div class="kpi-value text-danger fs-3">Kenshydokan Puro</div>
                    <div class="small text-muted mt-1">Kumite, Kata e Tameshiwari</div>
                </div>
                <div class="kpi-icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-torii-gate"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Campeonatos Recentes -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <span class="p-2 bg-danger bg-opacity-10 text-danger rounded-3">
                <i class="fas fa-calendar-alt"></i>
            </span>
            <div>
                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">Campeonatos Oficiais Cadastrados</h5>
                <small class="text-muted">Acesse o painel individual de cada evento para gerenciar categorias e súmulas</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="campeonatos.php" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-th-list me-1"></i> Ver Todos
            </a>
            <a href="criar_campeonato.php" class="btn btn-sm btn-danger">
                <i class="fas fa-plus me-1"></i> Novo Evento
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;">#</th>
                        <th>Título do Campeonato</th>
                        <th>Data do Evento</th>
                        <th>Cidade / Local</th>
                        <th>Status do Evento</th>
                        <th class="text-end pe-4" style="width: 240px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($campeonatos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="mb-3 text-secondary opacity-50">
                                        <i class="fas fa-calendar-times fa-4x"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">Nenhum campeonato cadastrado ainda</h5>
                                    <p class="text-muted small mb-3">Clique no botão abaixo para criar a primeira edição oficial.</p>
                                    <a href="criar_campeonato.php" class="btn btn-danger btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i> Criar Primeiro Campeonato
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($campeonatos as $idx => $camp): ?>
                            <tr>
                                <td class="ps-4 text-muted fw-bold"><?= $idx + 1 ?></td>
                                <td>
                                    <a href="detalhes_campeonato.php?id=<?= $camp['id_campeonato'] ?>" class="text-decoration-none fw-bold text-dark fs-6 hover-red">
                                        <?= htmlspecialchars($camp['titulo']) ?>
                                    </a>
                                    <?php if (!empty($camp['subtitulo'])): ?>
                                        <div class="text-muted small"><?= htmlspecialchars($camp['subtitulo']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <i class="far fa-calendar-check text-danger me-1"></i>
                                        <?= date('d/m/Y', strtotime($camp['data_inicio'])) ?>
                                    </div>
                                    <?php if ($camp['data_inicio'] !== $camp['data_fim']): ?>
                                        <small class="text-muted">até <?= date('d/m/Y', strtotime($camp['data_fim'])) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        <?= htmlspecialchars($camp['cidade']) ?> - <?= htmlspecialchars($camp['estado']) ?>
                                    </div>
                                    <small class="text-muted"><?= htmlspecialchars($camp['local_evento'] ?? 'Local a definir') ?></small>
                                </td>
                                <td>
                                    <?php
                                    $st = $camp['status'];
                                    $badgeColor = 'bg-secondary';
                                    $badgeText = ucfirst(str_replace('_', ' ', $st));

                                    if ($st === 'inscricoes_abertas') {
                                        $badgeColor = 'bg-success';
                                        $badgeText = 'Inscrições Abertas';
                                    } elseif ($st === 'pesagem') {
                                        $badgeColor = 'bg-warning text-dark';
                                        $badgeText = 'Pesagem Oficial';
                                    } elseif ($st === 'chaveamento') {
                                        $badgeColor = 'bg-info text-dark';
                                        $badgeText = 'Chaveamento Pronto';
                                    } elseif ($st === 'em_andamento') {
                                        $badgeColor = 'bg-danger pulse-glow';
                                        $badgeText = 'AO VIVO / Em Andamento';
                                    } elseif ($st === 'concluido') {
                                        $badgeColor = 'bg-dark';
                                        $badgeText = 'Concluído';
                                    }
                                    ?>
                                    <span class="badge <?= $badgeColor ?>"><?= $badgeText ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="detalhes_campeonato.php?id=<?= $camp['id_campeonato'] ?>" class="btn btn-sm btn-danger" title="Abrir Central do Campeonato">
                                        <i class="fas fa-cog me-1"></i> Gerenciar
                                    </a>
                                    <a href="editar_campeonato.php?id=<?= $camp['id_campeonato'] ?>" class="btn btn-sm btn-secondary" title="Editar Informações">
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

<!-- Acessos Rápidos e Guias de Operação -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 fs-3">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Súmula Digital ao Vivo</h6>
                    <p class="text-muted small mb-0">Duplo cronômetro de luta e Ne Waza (solo máx 60s), buzzer sintético e regras esportivas automáticas.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-4 fs-3">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Chaveamento Eliminatório</h6>
                    <p class="text-muted small mb-0">Sorteio com potências de 2, Byes automáticos e separação de atletas do mesmo Dojo na Rodada 1.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 fs-3">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Autenticidade Criptográfica</h6>
                    <p class="text-muted small mb-0">Certificados digitais emitidos com código Hash verificável publicamente por qualquer instituição.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
