<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/chaves" class="text-decoration-none">Categorias</a></li>
                <li class="breadcrumb-item active" aria-current="page">Súmula de Tameshiwari</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-hammer text-warning me-2"></i>Súmula de Tameshiwari: <?= htmlspecialchars($categoria['nome']) ?>
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; 4 Técnicas Oficiais (Seiken, Shuto, Hiji, Chusoku/Sokuto/Kakato) &bull; Máx. 60s por tentativa</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#modalLancarTameshiwari">
            <i class="fas fa-plus me-1"></i> Registrar Tentativa de Quebramento
        </button>
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/resultados" class="btn btn-outline-success">
            <i class="fas fa-medal me-1"></i> Pódios
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Ranking Geral de Tábuas Quebradas -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                    <i class="fas fa-cubes text-warning me-2"></i>Classificação Geral por Tábuas Quebradas
                </h5>
                <span class="badge bg-light text-secondary border">Total Atletas: <?= count($ranking) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3" style="width: 60px;">Pos.</th>
                                <th>Atleta Competidor</th>
                                <th>Dojo</th>
                                <th class="text-center">Tentativas</th>
                                <th class="text-center">Declaradas</th>
                                <th class="text-end pe-3">Total Quebradas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ranking)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-cubes fa-3x text-secondary opacity-50 mb-3"></i>
                                        <p class="mb-0">Nenhuma tentativa de Tameshiwari registrada ainda nesta categoria.</p>
                                        <button class="btn btn-sm btn-warning mt-2 text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalLancarTameshiwari">
                                            Registrar Primeira Tentativa
                                        </button>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ranking as $idx => $r): ?>
                                    <?php
                                    $posicao = $idx + 1;
                                    $badgePos = ($posicao === 1) ? 'bg-warning text-dark' : (($posicao === 2) ? 'bg-light text-dark border' : 'bg-danger text-white');
                                    ?>
                                    <tr class="<?= $posicao <= 3 ? 'table-warning bg-opacity-10' : '' ?>">
                                        <td class="ps-3">
                                            <span class="badge <?= $badgePos ?> rounded-circle p-2 fs-7 fw-bold" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <?= $posicao ?>º
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($r['nome_atleta']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($r['graduacao_nome']) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <?= htmlspecialchars($r['dojo']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?= $r['total_tentativas'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted small"><?= $r['total_declaradas'] ?> tábuas</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="display-6 fw-bold text-danger fs-5"><?= (int)$r['total_quebradas'] ?></span>
                                            <span class="small text-muted">tábuas</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Regras e Lista de Atletas -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="card-title mb-0 fs-6 fw-bold">
                    <i class="fas fa-book text-warning me-2"></i>Normas Oficiais de Tameshiwari
                </h6>
            </div>
            <div class="card-body p-3 small text-muted">
                <ul class="mb-0 ps-3">
                    <li class="mb-2"><strong>4 Técnicas Obrigatórias:</strong> Seiken (punho), Shuto (mão espalmada), Hiji (cotovelo), Chusoku/Sokuto/Kakato (chute).</li>
                    <li class="mb-2"><strong>Tempo Máximo:</strong> 60 segundos por tentativa a partir do Hajime.</li>
                    <li class="mb-2"><strong>Quebra Parcial = 0:</strong> Se qualquer tábua da pilha declarada não partir completamente, a tentativa inteira é zerada.</li>
                    <li><strong>Critério de Desempate:</strong> Maior número de tábuas na técnica de Seiken; persistindo empate, o atleta de menor peso corporal vence.</li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="card-title mb-0 fs-6 fw-bold text-dark">
                    <i class="fas fa-users text-primary me-2"></i>Atletas Aptos
                </h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($inscricoes as $ins): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <div>
                                <span class="fw-semibold text-dark small d-block"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars($ins['dojo_snapshot']) ?></small>
                            </div>
                            <button class="btn btn-xs btn-outline-warning text-dark py-1 px-2" onclick="selecionarAtletaTameshiwari(<?= $ins['id_inscricao'] ?>)">
                                <i class="fas fa-hammer me-1"></i> Lançar
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Tentativa de Quebramento -->
<div class="modal fade" id="modalLancarTameshiwari" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="/sumula/tameshiwari">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">
                <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-hammer text-warning me-2"></i>Registro de Tentativa de Tameshiwari
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="selectAtletaTameshiwari" class="form-label fw-bold">Atleta Competidor: <span class="text-danger">*</span></label>
                        <select id="selectAtletaTameshiwari" name="id_inscricao" class="form-select" required>
                            <option value="">-- Selecione o Atleta --</option>
                            <?php foreach ($inscricoes as $ins): ?>
                                <option value="<?= $ins['id_inscricao'] ?>">
                                    <?= htmlspecialchars($ins['nome_atleta_snapshot']) ?> (<?= htmlspecialchars($ins['dojo_snapshot']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Técnica Oficial: <span class="text-danger">*</span></label>
                            <select name="tecnica" class="form-select" required>
                                <option value="seiken">Seiken (Punho Cerrado)</option>
                                <option value="shuto">Shuto (Faca da Mão)</option>
                                <option value="hiji">Hiji (Cotovelo)</option>
                                <option value="chusoku">Chusoku / Sokuto / Kakato (Chute)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Material: <span class="text-danger">*</span></label>
                            <select name="material" class="form-select" required>
                                <option value="madeira">Madeira Padronizada (Pinho)</option>
                                <option value="bloco">Bloco / Telha</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Qtd. Declarada: <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="25" class="form-control" name="qtd_declarada" value="2" required id="qtd_declarada" oninput="sincronizarQuebradas()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Resultado Oficial: <span class="text-danger">*</span></label>
                            <select name="resultado" id="selectResultado" class="form-select" required onchange="ajustarResultadoQuebra()">
                                <option value="sucesso_total">Sucesso Total (Quebrou Todas)</option>
                                <option value="falha_ou_parcial">Falha / Quebra Parcial (Nota 0)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Qtd. Efetivamente Partida:</label>
                            <input type="number" min="0" max="25" class="form-control fw-bold fs-5 text-success" name="qtd_quebrada" value="2" id="qtd_quebrada" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tempo Gasto (segundos):</label>
                            <input type="number" min="1" max="60" class="form-control" name="tempo_segundos" value="15" placeholder="Máx 60s">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Árbitro Fiscal da Bancada:</label>
                        <input type="text" class="form-control" name="arbitro_responsavel" value="Árbitro Chefe de Tameshiwari">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Gravar Tentativa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selecionarAtletaTameshiwari(idInscricao) {
    document.getElementById('selectAtletaTameshiwari').value = idInscricao;
    var modal = new bootstrap.Modal(document.getElementById('modalLancarTameshiwari'));
    modal.show();
}

function sincronizarQuebradas() {
    var res = document.getElementById('selectResultado').value;
    if (res === 'sucesso_total') {
        document.getElementById('qtd_quebrada').value = document.getElementById('qtd_declarada').value;
    }
}

function ajustarResultadoQuebra() {
    var res = document.getElementById('selectResultado').value;
    if (res === 'falha_ou_parcial') {
        document.getElementById('qtd_quebrada').value = 0;
    } else {
        document.getElementById('qtd_quebrada').value = document.getElementById('qtd_declarada').value;
    }
}
</script>
