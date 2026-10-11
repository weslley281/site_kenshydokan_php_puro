<?php
use App\Core\Router;
use App\Core\Csrf;

$base = Router::getBasePath();
$idCamp = $campeonato['id_campeonato'];
?>
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card-kenshy p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge-status badge-status-<?= $campeonato['status'] ?>">
                            <?= str_replace('_', ' ', $campeonato['status']) ?>
                        </span>
                        <span class="text-muted small">&bull; Edição: <?= htmlspecialchars($campeonato['edicao'] ?: 'Oficial') ?> (<?= $campeonato['ano'] ?>)</span>
                    </div>
                    <h2 class="fw-bold text-white mb-1"><?= htmlspecialchars($campeonato['titulo']) ?></h2>
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-location-dot me-1 text-danger"></i> <?= htmlspecialchars($campeonato['local']) ?> &bull; <?= htmlspecialchars($campeonato['cidade'] . ' - ' . $campeonato['estado']) ?> |
                        <i class="fa-solid fa-calendar me-1 text-warning"></i> <?= date('d/m/Y', strtotime($campeonato['data_inicio'])) ?> a <?= date('d/m/Y', strtotime($campeonato['data_fim'])) ?>
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <!-- Botão de Mudança de Status -->
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalStatus">
                        <i class="fa-solid fa-arrows-rotate me-1"></i> Alterar Fase
                    </button>
                    <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/editar" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-pen me-1"></i> Editar
                    </a>
                    <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/auditoria" class="btn btn-outline-info">
                        <i class="fa-solid fa-shield-halved me-1"></i> Auditoria
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NAVEGAÇÃO RÁPIDA DOS MÓDULOS -->
<div class="row g-4 mb-4">
    <div class="col-md-4 col-xl-2">
        <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/categorias" class="card-kenshy p-3 text-center text-decoration-none d-block">
            <i class="fa-solid fa-layer-group fa-2x text-warning mb-2"></i>
            <h6 class="text-white mb-1">Categorias</h6>
            <span class="badge bg-secondary"><?= count($categorias) ?> ativas</span>
        </a>
    </div>

    <div class="col-md-4 col-xl-2">
        <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/inscricoes" class="card-kenshy p-3 text-center text-decoration-none d-block">
            <i class="fa-solid fa-users fa-2x text-info mb-2"></i>
            <h6 class="text-white mb-1">Inscrições</h6>
            <span class="badge bg-secondary"><?= count($inscricoes) ?> atletas</span>
        </a>
    </div>

    <div class="col-md-4 col-xl-2">
        <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/pesagem" class="card-kenshy p-3 text-center text-decoration-none d-block">
            <i class="fa-solid fa-weight-scale fa-2x text-danger mb-2"></i>
            <h6 class="text-white mb-1">Pesagem</h6>
            <span class="badge bg-secondary">Balança Oficial</span>
        </a>
    </div>

    <div class="col-md-4 col-xl-2">
        <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/chaves" class="card-kenshy p-3 text-center text-decoration-none d-block">
            <i class="fa-solid fa-sitemap fa-2x text-primary mb-2"></i>
            <h6 class="text-white mb-1">Chaveamento</h6>
            <span class="badge bg-secondary">Sorteio & Byes</span>
        </a>
    </div>

    <div class="col-md-4 col-xl-2">
        <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/tatames" class="card-kenshy p-3 text-center text-decoration-none d-block">
            <i class="fa-solid fa-square-full fa-2x text-success mb-2"></i>
            <h6 class="text-white mb-1">Tatames</h6>
            <span class="badge bg-secondary"><?= count($tatames) ?> áreas</span>
        </a>
    </div>

    <div class="col-md-4 col-xl-2">
        <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/resultados" class="card-kenshy p-3 text-center text-decoration-none d-block">
            <i class="fa-solid fa-award fa-2x text-gold mb-2" style="color: #f59e0b;"></i>
            <h6 class="text-white mb-1">Resultados</h6>
            <span class="badge bg-secondary">Homologação</span>
        </a>
    </div>
</div>

<!-- AÇÕES E RELATÓRIOS DO EVENTO -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-kenshy h-100">
            <div class="card-header-kenshy">
                <span><i class="fa-solid fa-circle-info text-info me-2"></i>Visão Geral das Categorias e Chaves</span>
                <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/categorias" class="btn btn-sm btn-kenshy">Gerenciar</a>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($categorias)): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Modalidade</th>
                                    <th>Categoria</th>
                                    <th>Sexo</th>
                                    <th>Idade</th>
                                    <th>Inscritos</th>
                                    <th>Chave</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categorias as $cat): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary"><?= strtoupper($cat['modalidade']) ?></span></td>
                                        <td><strong><?= htmlspecialchars($cat['nome']) ?></strong></td>
                                        <td><?= $cat['sexo'] === 'M' ? 'Masc.' : ($cat['sexo'] === 'F' ? 'Fem.' : 'Misto') ?></td>
                                        <td><?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?> anos</td>
                                        <td><span class="badge bg-info text-dark"><?= $cat['total_inscritos'] ?></span></td>
                                        <td>
                                            <a href="<?= $base ?>/chaves/<?= $cat['id_categoria'] ?>/visualizar" class="btn btn-sm btn-outline-light">
                                                <i class="fa-solid fa-sitemap me-1"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <p class="mb-3">Nenhuma categoria configurada para este campeonato.</p>
                        <form action="<?= $base ?>/campeonatos/<?= $idCamp ?>/categorias/modelo-padrao" method="POST" class="d-inline">
                            <?= Csrf::field() ?>
                            <button type="submit" class="btn btn-kenshy">
                                <i class="fa-solid fa-file-import me-1"></i> Carregar Categorias do Modelo Oficial
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-kenshy h-100">
            <div class="card-header-kenshy">
                <span><i class="fa-solid fa-file-pdf text-danger me-2"></i>Documentos & Relatórios Oficiais</span>
            </div>
            <div class="card-body p-4 d-flex flex-column gap-2">
                <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/relatorios/inscritos" target="_blank" class="btn btn-outline-secondary text-start py-2">
                    <i class="fa-solid fa-users me-2 text-info"></i> Relação Geral de Inscritos (PDF)
                </a>
                <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/relatorios/pesagem" target="_blank" class="btn btn-outline-secondary text-start py-2">
                    <i class="fa-solid fa-weight-scale me-2 text-danger"></i> Relatório de Pesagem Oficial (PDF)
                </a>
                <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/relatorios/medalhas" target="_blank" class="btn btn-outline-secondary text-start py-2">
                    <i class="fa-solid fa-award me-2 text-warning"></i> Quadro de Medalhas por Dojo (PDF)
                </a>
                <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>/certificados" class="btn btn-outline-secondary text-start py-2">
                    <i class="fa-solid fa-certificate me-2 text-success"></i> Central de Certificados Oficiais
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DE ALTERAÇÃO DE STATUS -->
<div class="modal fade" id="modalStatus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content card-kenshy text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fa-solid fa-arrows-rotate text-warning me-2"></i>Avançar Ciclo de Vida</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= $base ?>/campeonatos/<?= $idCamp ?>/status" method="POST">
                <?= Csrf::field() ?>
                <div class="modal-body">
                    <label class="form-label text-muted small fw-semibold">Selecione o Novo Status:</label>
                    <select name="status" class="form-select mb-3">
                        <option value="planejamento" <?= $campeonato['status'] === 'planejamento' ? 'selected' : '' ?>>1. Planejamento (Ajuste de Regras)</option>
                        <option value="inscricoes_abertas" <?= $campeonato['status'] === 'inscricoes_abertas' ? 'selected' : '' ?>>2. Inscrições Abertas</option>
                        <option value="inscricoes_encerradas" <?= $campeonato['status'] === 'inscricoes_encerradas' ? 'selected' : '' ?>>3. Inscrições Encerradas</option>
                        <option value="conferencia_pesagem" <?= $campeonato['status'] === 'conferencia_pesagem' ? 'selected' : '' ?>>4. Conferência & Pesagem</option>
                        <option value="chaveamento" <?= $campeonato['status'] === 'chaveamento' ? 'selected' : '' ?>>5. Chaveamento & Sorteio</option>
                        <option value="em_andamento" <?= $campeonato['status'] === 'em_andamento' ? 'selected' : '' ?>>6. Em Andamento (Lutas ao Vivo)</option>
                        <option value="encerrado" <?= $campeonato['status'] === 'encerrado' ? 'selected' : '' ?>>7. Encerrado (Homologado)</option>
                        <option value="arquivado" <?= $campeonato['status'] === 'arquivado' ? 'selected' : '' ?>>8. Arquivado</option>
                    </select>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-kenshy">Confirmar Mudança</button>
                </div>
            </form>
        </div>
    </div>
</div>
