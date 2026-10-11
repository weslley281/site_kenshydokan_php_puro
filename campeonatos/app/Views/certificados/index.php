<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Certificados</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-certificate text-danger me-2"></i>Emissão e Gestão de Certificados Digitais
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Certificados oficiais autenticados por Hash Criptográfico</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="/validar-certificado" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-qrcode me-1"></i> Validador Público
        </a>
        <form method="POST" action="/campeonatos/<?= $campeonato['id_campeonato'] ?>/certificados/lote" onsubmit="return confirm('Gerar certificados de participação para todos os atletas aprovados neste campeonato?');">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-cogs me-1"></i> Emitir Lote Completo
            </button>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-primary border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary p-3 rounded">
                        <i class="fas fa-file-invoice fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Certificados Emitidos</h6>
                        <span class="fs-4 fw-bold text-dark"><?= count($certificados) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm border-start border-success border-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success p-3 rounded">
                        <i class="fas fa-shield-alt fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small text-uppercase fw-semibold">Autenticidade</h6>
                        <span class="fs-4 fw-bold text-success">100% Cripto</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Certificados Gerados -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-list text-danger me-2"></i>Certificados Oficiais Registrados
        </h5>
        <span class="badge bg-light text-secondary border">Total: <?= count($certificados) ?></span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 50px;">#</th>
                        <th>Titular do Certificado</th>
                        <th>Tipo</th>
                        <th>Código Hash de Autenticidade</th>
                        <th>Data de Emissão</th>
                        <th class="text-end pe-3" style="width: 140px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($certificados)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-certificate fa-3x text-secondary opacity-50 mb-3"></i>
                                <p class="mb-0">Nenhum certificado emitido ainda para este campeonato.</p>
                                <small>Utilize o botão "Emitir Lote Completo" ou selecione os atletas abaixo.</small>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($certificados as $idx => $cert): ?>
                            <tr>
                                <td class="ps-3 text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($cert['nome_titular']) ?></strong>
                                </td>
                                <td>
                                    <?php if ($cert['tipo_certificado'] === 'campeao'): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-trophy me-1"></i>Campeão</span>
                                    <?php elseif ($cert['tipo_certificado'] === 'arbitro'): ?>
                                        <span class="badge bg-info text-dark">Árbitro</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Participação</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code class="fw-bold fs-7 text-danger"><?= htmlspecialchars($cert['codigo_autenticidade']) ?></code>
                                </td>
                                <td class="small text-muted">
                                    <?= date('d/m/Y H:i', strtotime($cert['data_emissao'])) ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="/certificados/gerar/<?= $cert['id_inscricao'] ?>?tipo=<?= $cert['tipo_certificado'] ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-file-pdf me-1"></i> Ver PDF
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

<!-- Emissão Individual por Atleta -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-user-check text-primary me-2"></i>Emitir Certificado Individual por Atleta
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3">Atleta Inscrito</th>
                        <th>Dojo</th>
                        <th>Categoria</th>
                        <th class="text-end pe-3" style="width: 250px;">Ações de Emissão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $ins): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-dark"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></td>
                            <td><?= htmlspecialchars($ins['dojo_snapshot']) ?></td>
                            <td><?= htmlspecialchars($ins['categoria_nome']) ?></td>
                            <td class="text-end pe-3">
                                <a href="/certificados/gerar/<?= $ins['id_inscricao'] ?>?tipo=participacao" target="_blank" class="btn btn-xs btn-outline-secondary me-1">
                                    <i class="fas fa-file-alt me-1"></i> Participação
                                </a>
                                <a href="/certificados/gerar/<?= $ins['id_inscricao'] ?>?tipo=campeao" target="_blank" class="btn btn-xs btn-outline-warning text-dark">
                                    <i class="fas fa-trophy me-1"></i> Pódio
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
