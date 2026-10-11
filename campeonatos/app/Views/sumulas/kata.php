<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/chaves" class="text-decoration-none">Categorias</a></li>
                <li class="breadcrumb-item active" aria-current="page">Súmula de Kata</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-user-ninja text-primary me-2"></i>Súmula Oficial de Kata: <?= htmlspecialchars($categoria['nome']) ?>
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Sistema de Avaliação por 3 a 5 Árbitros (Média Aritmética)</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLancarNota">
            <i class="fas fa-plus me-1"></i> Lançar Notas de Atleta
        </button>
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/resultados" class="btn btn-outline-success">
            <i class="fas fa-medal me-1"></i> Ir para Pódios
        </a>
    </div>
</div>

<?php if ($precisaDesempate): ?>
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4">
        <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
        <div class="flex-grow-1">
            <strong>Empate Detectado na Zona de Pódio!</strong>
            <p class="mb-0 small">
                O regulamento oficial da Kenshydokan Jutsu exige uma <strong>nova rodada de desempate com apresentação de um novo Kata diferente</strong> para desempatar as colocações de medalhas.
            </p>
        </div>
        <button class="btn btn-sm btn-dark ms-3" data-bs-toggle="modal" data-bs-target="#modalDesempate">
            <i class="fas fa-dice me-1"></i> Registrar Desempate
        </button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Ranking / Classificação Atual -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                    <i class="fas fa-trophy text-warning me-2"></i>Classificação Geral da Categoria
                </h5>
                <span class="badge bg-light text-secondary border">Total Avaliados: <?= count($ranking) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-secondary">
                            <tr>
                                <th class="ps-3" style="width: 60px;">Pos.</th>
                                <th>Atleta</th>
                                <th>Dojo</th>
                                <th>Kata Executado</th>
                                <th class="text-center">Notas dos Árbitros</th>
                                <th class="text-end pe-3">Média Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ranking)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard fa-3x text-secondary opacity-50 mb-3"></i>
                                        <p class="mb-0">Nenhuma nota lançada ainda para esta categoria de Kata.</p>
                                        <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalLancarNota">
                                            Lançar Primeira Apresentação
                                        </button>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ranking as $idx => $r): ?>
                                    <?php
                                    $posicao = $idx + 1;
                                    $badgePos = 'bg-secondary';
                                    if ($posicao === 1) $badgePos = 'bg-warning text-dark';
                                    elseif ($posicao === 2) $badgePos = 'bg-light text-dark border';
                                    elseif ($posicao === 3) $badgePos = 'bg-danger text-white';
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
                                        <td>
                                            <span class="fw-semibold text-primary"><?= htmlspecialchars($r['kata_nome'] ?? 'Kata Oficial') ?></span>
                                            <?php if (!empty($r['is_desempate'])): ?>
                                                <span class="badge bg-info text-dark ms-1" style="font-size: 0.65rem;">Desempate</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="small text-muted">
                                                <?= htmlspecialchars($r['notas_detalhes'] ?? '-') ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="display-6 fw-bold text-dark fs-5"><?= number_format($r['media_final'], 2, ',', '.') ?></span>
                                            <span class="small text-muted">pts</span>
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

    <!-- Lista de Atletas Inscritos e Status de Lançamento -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                    <i class="fas fa-users text-primary me-2"></i>Atletas Aptos na Categoria
                </h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($inscricoes as $ins): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <strong class="text-dark d-block"><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></strong>
                                <small class="text-muted"><?= htmlspecialchars($ins['dojo_snapshot']) ?> &bull; <?= htmlspecialchars($ins['graduacao_nome_snapshot']) ?></small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" onclick="selecionarAtletaParaLancar(<?= $ins['id_inscricao'] ?>, '<?= addslashes($ins['nome_atleta_snapshot']) ?>')">
                                <i class="fas fa-edit me-1"></i> Notas
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lançar Notas da Apresentação Regular -->
<div class="modal fade" id="modalLancarNota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="/sumula/kata">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">
                <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-edit text-danger me-2"></i>Lançamento de Notas de Kata (Média Aritmética)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="selectAtletaNota" class="form-label fw-bold">Atleta Competidor: <span class="text-danger">*</span></label>
                            <select id="selectAtletaNota" name="id_inscricao" class="form-select" required>
                                <option value="">-- Selecione o Atleta --</option>
                                <?php foreach ($inscricoes as $ins): ?>
                                    <option value="<?= $ins['id_inscricao'] ?>">
                                        <?= htmlspecialchars($ins['nome_atleta_snapshot']) ?> (<?= htmlspecialchars($ins['dojo_snapshot']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label for="kata_nome" class="form-label fw-bold">Kata Executado: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kata_nome" name="kata_nome" required placeholder="Ex: Bassai Dai, Kanku Dai, etc.">
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="fw-bold border-bottom pb-2 text-secondary">
                                <i class="fas fa-gavel text-primary me-2"></i>Notas Individuais dos Árbitros (Escala: 0.0 a 10.0)
                            </h6>
                            <div class="row g-2">
                                <?php for ($arb = 1; $arb <= 5; $arb++): ?>
                                    <div class="col-md">
                                        <div class="p-2 bg-light rounded border text-center">
                                            <label class="form-label small fw-bold mb-1">Árbitro <?= $arb ?></label>
                                            <input type="hidden" name="arbitros[<?= $arb ?>]" value="Árbitro <?= $arb ?>">
                                            <input type="number" step="0.1" min="0" max="10" class="form-control text-center fw-bold fs-5 input-nota-kata" name="notas[<?= $arb ?>]" <?= $arb <= 3 ? 'required' : '' ?> placeholder="0.0" oninput="calcularMediaAoVivo()">
                                            <small class="text-muted" style="font-size: 0.65rem;"><?= $arb <= 3 ? 'Obrigatório' : 'Opcional' ?></small>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="col-12 text-center p-3 bg-light rounded border mt-3">
                            <span class="small text-muted text-uppercase fw-bold d-block">Média Aritmética Calculada:</span>
                            <span id="preview_media_kata" class="display-5 fw-bold text-primary">0.00</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Gravar Notas e Média</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Desempate de Kata -->
<div class="modal fade" id="modalDesempate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="/sumula/kata/desempate">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">
                <input type="hidden" name="id_categoria" value="<?= $categoria['id_categoria'] ?>">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-dice text-dark me-2"></i>Rodada de Desempate de Kata (Novo Kata Obrigatório)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info border-0 shadow-sm small">
                        Informe o atleta empatado e as novas notas recebidas na execução do <strong>novo Kata</strong>.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Atleta Empatado:</label>
                            <select name="id_inscricao" class="form-select" required>
                                <option value="">-- Selecione o Atleta --</option>
                                <?php foreach ($inscricoes as $ins): ?>
                                    <option value="<?= $ins['id_inscricao'] ?>">
                                        <?= htmlspecialchars($ins['nome_atleta_snapshot']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Novo Kata Diferente:</label>
                            <input type="text" class="form-control" name="kata_nome" required placeholder="Novo Kata executado...">
                        </div>
                        <div class="col-12">
                            <div class="row g-2">
                                <?php for ($arb = 1; $arb <= 5; $arb++): ?>
                                    <div class="col-md">
                                        <div class="p-2 bg-light rounded border text-center">
                                            <label class="form-label small fw-bold mb-1">Árbitro <?= $arb ?></label>
                                            <input type="hidden" name="arbitros[<?= $arb ?>]" value="Árbitro <?= $arb ?>">
                                            <input type="number" step="0.1" min="0" max="10" class="form-control text-center fw-bold fs-5" name="notas[<?= $arb ?>]" <?= $arb <= 3 ? 'required' : '' ?> placeholder="0.0">
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Registrar Desempate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selecionarAtletaParaLancar(idInscricao, nome) {
    document.getElementById('selectAtletaNota').value = idInscricao;
    var modal = new bootstrap.Modal(document.getElementById('modalLancarNota'));
    modal.show();
}

function calcularMediaAoVivo() {
    var inputs = document.querySelectorAll('.input-nota-kata');
    var soma = 0;
    var count = 0;
    inputs.forEach(function(inp) {
        var v = parseFloat(inp.value);
        if (!isNaN(v) && v >= 0 && v <= 10) {
            soma += v;
            count++;
        }
    });

    if (count > 0) {
        var media = soma / count;
        document.getElementById('preview_media_kata').textContent = media.toFixed(2);
    } else {
        document.getElementById('preview_media_kata').textContent = '0.00';
    }
}
</script>
