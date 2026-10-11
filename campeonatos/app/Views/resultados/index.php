<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Resultados Oficiais</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-medal text-warning me-2"></i>Resultados Oficiais e Quadro de Medalhas
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Homologação Final da Federação Kenshydokan</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/certificados" class="btn btn-outline-danger">
            <i class="fas fa-certificate me-1"></i> Emitir Certificados
        </a>
    </div>
</div>

<!-- Quadro Geral de Medalhas por Dojo -->
<div class="card border-0 shadow-sm mb-4 border-top border-4 border-warning">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-trophy text-warning me-2"></i>Quadro Geral de Medalhas por Dojo / Academia
        </h5>
        <span class="badge bg-dark">Classificação Oficial Geral</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 60px;">Pos.</th>
                        <th>Dojo / Academia</th>
                        <th class="text-center" style="width: 100px;">
                            <i class="fas fa-medal text-warning me-1"></i> Ouro
                        </th>
                        <th class="text-center" style="width: 100px;">
                            <i class="fas fa-medal text-secondary me-1"></i> Prata
                        </th>
                        <th class="text-center" style="width: 100px;">
                            <i class="fas fa-medal text-danger me-1"></i> Bronze
                        </th>
                        <th class="text-end pe-3" style="width: 120px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tabelaMedalhas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Nenhum pódio homologado ainda neste evento. Homologue as categorias abaixo para consolidar as medalhas.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tabelaMedalhas as $idx => $m): ?>
                            <tr>
                                <td class="ps-3 fw-bold fs-6">
                                    <?php if ($idx === 0): ?>
                                        <span class="badge bg-warning text-dark px-2 py-1">1º</span>
                                    <?php elseif ($idx === 1): ?>
                                        <span class="badge bg-light text-dark border px-2 py-1">2º</span>
                                    <?php elseif ($idx === 2): ?>
                                        <span class="badge bg-danger text-white px-2 py-1">3º</span>
                                    <?php else: ?>
                                        <span class="text-muted ms-2"><?= $idx + 1 ?>º</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($m['dojo']) ?></div>
                                </td>
                                <td class="text-center fw-bold text-dark fs-6"><?= $m['ouro'] ?></td>
                                <td class="text-center fw-bold text-dark fs-6"><?= $m['prata'] ?></td>
                                <td class="text-center fw-bold text-dark fs-6"><?= $m['bronze'] ?></td>
                                <td class="text-end pe-3">
                                    <span class="badge bg-dark fs-6 px-3"><?= $m['total_medalhas'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pódios por Categoria -->
<h4 class="fw-bold text-dark mb-3">
    <i class="fas fa-award text-danger me-2"></i>Pódios Homologados por Categoria
</h4>

<div class="row g-4">
    <?php foreach ($categorias as $cat): ?>
        <?php $podio = $podiosPorCategoria[$cat['id_categoria']] ?? []; ?>
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-dark text-white me-1" style="font-size: 0.65rem;">
                            <?= strtoupper($cat['modalidade']) ?>
                        </span>
                        <h6 class="card-title mb-0 fw-bold text-dark d-inline">
                            <?= htmlspecialchars($cat['nome']) ?>
                        </h6>
                    </div>
                </div>

                <div class="card-body p-3">
                    <?php if (empty($podio)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-hourglass-half fa-2x mb-2 opacity-50"></i>
                            <p class="small mb-3">Pódio ainda não homologado para esta categoria.</p>
                            <form method="POST" action="/campeonatos/<?= $campeonato['id_campeonato'] ?>/categorias/<?= $cat['id_categoria'] ?>/homologar">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-check-double me-1"></i> Homologar Pódio Agora
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($podio as $lugar): ?>
                                <?php
                                $badgeColor = ($lugar['colocacao'] == 1) ? 'bg-warning text-dark' : (($lugar['colocacao'] == 2) ? 'bg-secondary text-white' : 'bg-danger text-white');
                                $iconeMedalha = ($lugar['colocacao'] == 1) ? 'fa-medal text-warning' : (($lugar['colocacao'] == 2) ? 'fa-medal text-secondary' : 'fa-medal text-danger');
                                ?>
                                <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span class="badge <?= $badgeColor ?> me-2 fw-bold" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                            <?= $lugar['colocacao'] ?>º
                                        </span>
                                        <div>
                                            <strong class="text-dark small d-block"><?= htmlspecialchars($lugar['nome_atleta']) ?></strong>
                                            <small class="text-muted"><?= htmlspecialchars($lugar['dojo']) ?></small>
                                        </div>
                                    </div>
                                    <i class="fas <?= $iconeMedalha ?> fa-lg"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-white border-top p-2 text-center small">
                    <?php if (!empty($podio)): ?>
                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>Homologado</span>
                    <?php else: ?>
                        <span class="text-muted"><i class="fas fa-clock me-1"></i>Aguardando Conclusão</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
