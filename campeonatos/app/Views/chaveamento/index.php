<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Chaveamento</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-sitemap text-danger me-2"></i>Gestão de Chaves e Sorteios
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Sorteio com separação por Dojo e potências de 2 (Byes automáticos)</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/tatames" class="btn btn-outline-danger">
            <i class="fas fa-th-large me-1"></i> Ir para Painel de Tatames
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-layer-group text-danger me-2"></i>Categorias do Campeonato
        </h5>
        <span class="badge bg-light text-secondary border">Total: <?= count($categorias) ?> categoria(s)</span>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 50px;">#</th>
                        <th>Categoria</th>
                        <th>Modalidade</th>
                        <th>Faixa Etária / Sexo</th>
                        <th>Inscritos / Aptos</th>
                        <th>Situação da Chave</th>
                        <th class="text-end pe-3" style="width: 250px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Nenhuma categoria cadastrada para este campeonato.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categorias as $idx => $cat): ?>
                            <?php
                            $chave = \App\Models\ChaveModel::byCategoria((int)$cat['id_categoria']);
                            $inscricoes = \App\Models\InscricaoModel::byCampeonato((int)$campeonato['id_campeonato'], (int)$cat['id_categoria']);
                            $totalIns = count($inscricoes);
                            $aptos = count(array_filter($inscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada'));
                            ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($cat['nome']) ?></div>
                                    <small class="text-muted">Regulamento Oficial Kenshydokan</small>
                                </td>
                                <td>
                                    <?php if ($cat['modalidade'] === 'kumite'): ?>
                                        <span class="badge bg-danger"><i class="fas fa-fist-raised me-1"></i>KUMITE</span>
                                    <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                        <span class="badge bg-primary"><i class="fas fa-user-ninja me-1"></i>KATA</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-hammer me-1"></i>TAMESHIWARI</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="small text-muted">
                                        <?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?> anos &bull;
                                        <span class="fw-semibold text-dark">
                                            <?= $cat['sexo'] === 'M' ? 'Masc.' : ($cat['sexo'] === 'F' ? 'Fem.' : 'Misto') ?>
                                        </span>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= $totalIns ?> inscritos (<strong><?= $aptos ?> aptos</strong>)
                                    </span>
                                </td>
                                <td>
                                    <?php if ($cat['modalidade'] === 'kumite'): ?>
                                        <?php if ($chave): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Chave Gerada (<?= $chave['total_atletas'] ?> atletas)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-clock me-1"></i>Pendente de Sorteio
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                        <span class="badge bg-info text-dark">
                                            <i class="fas fa-clipboard-list me-1"></i>Súmula de Notas (3 a 5 árbitros)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-cubes me-1"></i>Súmula de Quebramento (4 técnicas)
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <?php if ($cat['modalidade'] === 'kumite'): ?>
                                        <?php if ($chave): ?>
                                            <a href="/chaves/<?= $cat['id_categoria'] ?>/visualizar" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i> Ver Chave
                                            </a>
                                            <a href="/chaves/<?= $cat['id_categoria'] ?>/pdf" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-warning" title="Regerar Sorteio" onclick="confirmarSorteio(<?= $cat['id_categoria'] ?>, '<?= addslashes($cat['nome']) ?>', <?= $aptos ?>)">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarSorteio(<?= $cat['id_categoria'] ?>, '<?= addslashes($cat['nome']) ?>', <?= $aptos ?>)" <?= $aptos < 2 ? 'disabled title="Mínimo de 2 atletas aptos"' : '' ?>>
                                                <i class="fas fa-random me-1"></i> Realizar Sorteio
                                            </button>
                                        <?php endif; ?>
                                    <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                        <a href="/sumula/kata/<?= $cat['id_categoria'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-clipboard-list me-1"></i> Abrir Súmula Kata
                                        </a>
                                    <?php else: ?>
                                        <a href="/sumula/tameshiwari/<?= $cat['id_categoria'] ?>" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-hammer me-1"></i> Abrir Tameshiwari
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Confirmação de Sorteio -->
<div class="modal fade" id="modalSorteio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="formGerarSorteio" action="">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-random text-danger me-2"></i>Sorteio Oficial de Chave Eliminatória
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Categoria: <strong id="sorteioNomeCategoria" class="text-danger"></strong><br>
                        Atletas Aptos: <strong id="sorteioTotalAptos" class="text-success"></strong>
                    </p>
                    <div class="alert alert-info border-0 shadow-sm small">
                        <i class="fas fa-info-circle me-1"></i>
                        O algoritmo Kenshydokan distribuirá os atletas em potências de 2 (2, 4, 8, 16, 32, 64) aplicando:
                        <ul class="mb-0 mt-1 ps-3">
                            <li>Isolamento de mesmo Dojo nas primeiras rodadas;</li>
                            <li>Atribuição automática de Byes (isenções);</li>
                            <li>Geração automática de combates encadeados.</li>
                        </ul>
                    </div>
                    <p class="small text-danger fw-bold mb-0">
                        Atenção: Se a chave já existir, o sorteio anterior será reiniciado!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="fas fa-dice me-1"></i> Executar Sorteio Agora
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarSorteio(idCategoria, nomeCat, aptos) {
    if (aptos < 2) {
        alertify.alert('Atenção', 'São necessários no mínimo 2 atletas aprovados na pesagem para realizar o sorteio.');
        return;
    }
    document.getElementById('formGerarSorteio').action = '/chaves/' + idCategoria + '/gerar';
    document.getElementById('sorteioNomeCategoria').textContent = nomeCat;
    document.getElementById('sorteioTotalAptos').textContent = aptos + ' atletas';
    
    var modal = new bootstrap.Modal(document.getElementById('modalSorteio'));
    modal.show();
}
</script>
