<?php
$pageTitle = 'Súmula Oficial de Kata | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/sumulaKataModel.php";

$idCategoria = (int)($_GET['id'] ?? ($_GET['id_categoria'] ?? 0));
$categoriaModel = new Categoria();
$categoria = $categoriaModel->buscarPorId($idCategoria);

if (!$categoria) {
    echo "<div class='alert alert-danger'>Categoria não encontrada.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$idCampeonato = (int)$categoria['id_campeonato'];
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

$inscricaoModel = new Inscricao();
$inscricoes = $inscricaoModel->buscarPorCategoria($idCategoria);

$kataModel = new SumulaKata();
$ranking = $kataModel->obterRanking($idCategoria, 1);
$rankingDesempate = $kataModel->obterRanking($idCategoria, 2);
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo'] ?? '') ?></a></li>
                <li class="breadcrumb-item"><a href="categorias.php?id=<?= $idCampeonato ?>" class="text-decoration-none">Categorias</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($categoria['nome']) ?></li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-medal text-primary me-2"></i>Súmula Oficial de Kata: <?= htmlspecialchars($categoria['nome']) ?>
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo'] ?? '') ?> &bull; Sistema de Avaliação por Notas (3 a 5 Árbitros)</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLancarNotas">
            <i class="fas fa-plus me-1"></i> Lançar Notas do Atleta
        </button>
        <button class="btn btn-warning text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalDesempate">
            <i class="fas fa-balance-scale me-1"></i> Rodada Desempate
        </button>
        <a href="categorias.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<!-- Resumo da Categoria e Regras de Avaliação -->
<div class="card border-0 shadow-sm mb-4 border-start border-4 border-primary">
    <div class="card-body p-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-info-circle text-primary me-1"></i> Critérios Oficiais de Julgamento de Kata (Kenshydokan Jutsu)</h6>
                <p class="text-muted small mb-0">
                    Notas de 0.0 a 10.0 atribuídas por árbitros oficiais. Avaliação baseada em: <strong>Postura marcial (Shisei), Precisão técnica, Ritmo e Kime, Equilíbrio e Zanshin</strong>. A classificação final é dada pela média aritmética. Em caso de empate rigoroso, realiza-se nova rodada de desempate com <em>obrigatoriedade de apresentação de um novo Kata</em>.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-primary fs-7">Atletas Aptos: <?= count($inscricoes) ?></span>
                <span class="badge bg-secondary fs-7">Apresentações: <?= count($ranking) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Classificação e Ranking Geral de Kata -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-trophy text-warning me-2"></i>Classificação Oficial de Kata (Rodada Principal)
        </h5>
        <span class="badge bg-dark">Média Aritmética</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 80px;">Posição</th>
                        <th>Atleta</th>
                        <th>Dojo</th>
                        <th>Kata Apresentado</th>
                        <th class="text-center">Qtd. Árbitros</th>
                        <th class="text-end pe-4">Média Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ranking)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Nenhuma nota de Kata lançada até o momento nesta categoria.
                                <br>
                                <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalLancarNotas">
                                    Lançar Primeira Apresentação
                                </button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ranking as $idx => $r): ?>
                            <?php 
                                $colocacao = $idx + 1;
                                $classeBadge = $colocacao === 1 ? 'bg-warning text-dark' : ($colocacao === 2 ? 'bg-secondary' : ($colocacao === 3 ? 'bg-danger' : 'bg-light text-dark border'));
                            ?>
                            <tr>
                                <td class="ps-3">
                                    <span class="badge <?= $classeBadge ?> fs-6 rounded-pill px-3">
                                        <?= $colocacao ?>º
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6"><?= htmlspecialchars($r['nome_atleta_snapshot']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($r['dojo_snapshot']) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1">
                                        <?= htmlspecialchars($r['kata_nome'] ?: 'Kata Livre') ?>
                                    </span>
                                </td>
                                <td class="text-center font-monospace">
                                    <?= $r['total_avaliacoes'] ?> árbitros
                                </td>
                                <td class="text-end pe-4">
                                    <span class="display-6 fs-4 fw-bold text-primary font-monospace">
                                        <?= number_format((float)$r['media_nota'], 2) ?>
                                    </span>
                                    <small class="text-muted d-block font-sans-serif" style="font-size: 0.75rem;">Soma: <?= number_format((float)$r['soma_notas'], 1) ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($rankingDesempate)): ?>
<!-- Tabela de Desempate (Se Houver) -->
<div class="card border-0 shadow-sm mb-4 border-top border-4 border-warning">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-balance-scale text-warning me-2"></i>Rodada de Desempate Oficial (Novo Kata)
        </h5>
        <span class="badge bg-warning text-dark">Critério Final</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3">Posição</th>
                        <th>Atleta</th>
                        <th>Dojo</th>
                        <th>Novo Kata de Desempate</th>
                        <th class="text-center">Árbitros</th>
                        <th class="text-end pe-4">Média Desempate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rankingDesempate as $idx => $rd): ?>
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-dark fs-6 rounded-pill px-3"><?= $idx + 1 ?>º</span>
                            </td>
                            <td><strong class="text-dark"><?= htmlspecialchars($rd['nome_atleta_snapshot']) ?></strong></td>
                            <td><span class="badge bg-light text-secondary border"><?= htmlspecialchars($rd['dojo_snapshot']) ?></span></td>
                            <td><span class="badge bg-warning text-dark border px-2 py-1"><?= htmlspecialchars($rd['kata_nome']) ?></span></td>
                            <td class="text-center font-monospace"><?= $rd['total_avaliacoes'] ?></td>
                            <td class="text-end pe-4 fw-bold text-primary fs-5 font-monospace"><?= number_format((float)$rd['media_nota'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODAL: LANÇAR NOTAS REGULARES (RODADA 1) -->
<div class="modal fade" id="modalLancarNotas" tabindex="-1" aria-labelledby="modalLancarNotasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="../controllers/sumulaKataController.php" method="POST">
                <input type="hidden" name="tipo" value="lancar_notas">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold" id="modalLancarNotasLabel">
                        <i class="fas fa-clipboard-check text-primary me-2"></i>Lançamento de Notas de Kata
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-muted">Atleta Competidor</label>
                            <select name="id_inscricao" class="form-select select2-modal" required>
                                <option value="">-- Selecione o atleta participante --</option>
                                <?php foreach ($inscricoes as $ins): ?>
                                    <option value="<?= $ins['id_inscricao'] ?>">
                                        <?= htmlspecialchars($ins['nome_atleta_snapshot']) ?> (<?= htmlspecialchars($ins['dojo_snapshot']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Kata Executado</label>
                            <input type="text" name="kata_nome" class="form-control" list="listaKatas" placeholder="Ex: Bassai Dai, Heian..." required>
                            <datalist id="listaKatas">
                                <option value="Taikyoku Sono Ichi">
                                <option value="Taikyoku Sono Ni">
                                <option value="Taikyoku Sono San">
                                <option value="Pinan Sono Ichi">
                                <option value="Pinan Sono Ni">
                                <option value="Pinan Sono San">
                                <option value="Pinan Sono Yon">
                                <option value="Pinan Sono Go">
                                <option value="Bassai Dai">
                                <option value="Kanku Dai">
                                <option value="Empi">
                                <option value="Seienchin">
                                <option value="Garyu">
                                <option value="Koryu Kenshydokan">
                            </datalist>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark mt-4 mb-2 small text-uppercase">
                        <i class="fas fa-users text-primary me-1"></i> Notas dos Árbitros (Escala 0.0 a 10.0)
                    </h6>
                    <p class="text-muted small">Informe a pontuação dada por 3 a 5 árbitros da banca examinadora:</p>

                    <div class="row g-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card p-3 bg-light border">
                                    <label class="form-label fw-bold small text-dark mb-1">Árbitro <?= $i ?> <?= $i <= 3 ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Opcional)</span>' ?></label>
                                    <input type="text" name="arbitros[<?= $i ?>]" class="form-control form-control-sm mb-2" value="Árbitro <?= $i ?>" placeholder="Nome do árbitro">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-star text-warning"></i></span>
                                        <input type="number" step="0.1" min="0" max="10" name="notas[<?= $i ?>]" class="form-control fw-bold text-center" placeholder="0.0" <?= $i <= 3 ? 'required' : '' ?>>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Gravar Notas e Calcular Média</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: RODADA DE DESEMPATE (RODADA 2) -->
<div class="modal fade" id="modalDesempate" tabindex="-1" aria-labelledby="modalDesempateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="../controllers/sumulaKataController.php" method="POST">
                <input type="hidden" name="tipo" value="desempate">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                <input type="hidden" name="id_categoria" value="<?= $idCategoria ?>">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fs-6 fw-bold" id="modalDesempateLabel">
                        <i class="fas fa-balance-scale me-2"></i>Rodada Oficial de Desempate (Novo Kata)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning small border-warning mb-3">
                        <strong>Regra Oficial Kenshydokan:</strong> Em caso de empate de notas na fase preliminar, os atletas empatados devem apresentar obrigatoriamente um <u>KATA DIFERENTE</u> do executado na primeira rodada.
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-muted">Atleta Empatado</label>
                            <select name="id_inscricao" class="form-select select2-modal" required>
                                <option value="">-- Selecione o atleta empatado --</option>
                                <?php foreach ($ranking as $rk): ?>
                                    <option value="<?= $rk['id_inscricao'] ?>">
                                        <?= htmlspecialchars($rk['nome_atleta_snapshot']) ?> (<?= htmlspecialchars($rk['dojo_snapshot']) ?>) - Média: <?= number_format((float)$rk['media_nota'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Novo Kata de Desempate</label>
                            <input type="text" name="kata_nome" class="form-control" list="listaKatas" placeholder="Novo Kata diferente..." required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark mt-4 mb-2 small text-uppercase">Notas dos Árbitros no Desempate</h6>
                    <div class="row g-3">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card p-3 bg-light border">
                                    <label class="form-label fw-bold small text-dark mb-1">Árbitro <?= $i ?></label>
                                    <input type="text" name="arbitros[<?= $i ?>]" class="form-control form-control-sm mb-2" value="Árbitro <?= $i ?>">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-star text-warning"></i></span>
                                        <input type="number" step="0.1" min="0" max="10" name="notas[<?= $i ?>]" class="form-control fw-bold text-center" placeholder="0.0" <?= $i <= 3 ? 'required' : '' ?>>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Registrar Desempate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
