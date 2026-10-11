<?php
$pageTitle = 'Categorias | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$categoriaModel = new Categoria();
$categorias = $categoriaModel->buscarPorCampeonato($idCampeonato);
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Categorias</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-layer-group text-danger me-2"></i>Categorias de Disputa
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
            <i class="fas fa-plus me-1"></i> Nova Categoria
        </button>
        <a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
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
                        <th>Categoria</th>
                        <th>Modalidade</th>
                        <th>Faixa Etária</th>
                        <th>Sexo</th>
                        <th>Limites de Peso</th>
                        <th>Duração Luta</th>
                        <th class="text-end pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                Nenhuma categoria cadastrada ainda neste evento.
                                <br>
                                <button class="btn btn-sm btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
                                    Adicionar Primeira Categoria
                                </button>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categorias as $idx => $cat): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-muted"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($cat['nome']) ?></strong>
                                </td>
                                <td>
                                    <?php if ($cat['modalidade'] === 'kumite'): ?>
                                        <span class="badge bg-danger">KUMITE</span>
                                    <?php elseif ($cat['modalidade'] === 'kata'): ?>
                                        <span class="badge bg-primary">KATA</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">TAMESHIWARI</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small">
                                    <?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?> anos
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= $cat['sexo'] === 'M' ? 'Masculino' : ($cat['sexo'] === 'F' ? 'Feminino' : 'Misto') ?>
                                    </span>
                                </td>
                                <td class="small">
                                    <?php if ($cat['exige_pesagem'] == 1): ?>
                                        <?= $cat['peso_min'] ? number_format($cat['peso_min'], 1) . 'kg' : 'Sem mín.' ?> 
                                        até 
                                        <strong><?= $cat['peso_max'] ? number_format($cat['peso_max'], 1) . 'kg' : 'Livre' ?></strong>
                                        <?php if ($cat['tolerancia_peso_kg'] > 0): ?>
                                            <span class="text-info">(+<?= number_format($cat['tolerancia_peso_kg'], 1) ?>kg tol.)</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Sem pesagem</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small">
                                    <?= floor($cat['duracao_luta_segundos'] / 60) ?> min
                                </td>
                                <td class="text-end pe-3">
                                    <form method="POST" action="../controllers/categoriaController.php" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir esta categoria?');">
                                        <input type="hidden" name="tipo" value="excluir_categoria">
                                        <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                                        <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nova Categoria -->
<div class="modal fade" id="modalNovaCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="../controllers/categoriaController.php">
                <input type="hidden" name="tipo" value="criar_categoria">
                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fas fa-plus-circle text-danger me-2"></i>Cadastrar Nova Categoria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="nomeCat" class="form-label fw-bold">Nome da Categoria: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nomeCat" name="nome" required placeholder="Ex: Sênior Masculino até 75kg">
                        </div>
                        <div class="col-md-4">
                            <label for="modalidadeCat" class="form-label fw-bold">Modalidade: <span class="text-danger">*</span></label>
                            <select class="form-select" id="modalidadeCat" name="modalidade" required>
                                <option value="kumite">Kumite (Combate)</option>
                                <option value="kata">Kata (Formas)</option>
                                <option value="tameshiwari">Tameshiwari (Quebramento)</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="sexoCat" class="form-label fw-bold">Sexo / Gênero: <span class="text-danger">*</span></label>
                            <select class="form-select" id="sexoCat" name="sexo" required>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="misto">Misto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="idadeMinCat" class="form-label fw-bold">Idade Mínima: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="idadeMinCat" name="idade_min" value="18" required>
                        </div>
                        <div class="col-md-4">
                            <label for="idadeMaxCat" class="form-label fw-bold">Idade Máxima: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="idadeMaxCat" name="idade_max" value="35" required>
                        </div>

                        <div class="col-md-3">
                            <label for="pesoMinCat" class="form-label fw-bold">Peso Mínimo (kg):</label>
                            <input type="number" step="0.1" class="form-control" id="pesoMinCat" name="peso_min" placeholder="Ex: 65.0">
                        </div>
                        <div class="col-md-3">
                            <label for="pesoMaxCat" class="form-label fw-bold">Peso Máximo (kg):</label>
                            <input type="number" step="0.1" class="form-control" id="pesoMaxCat" name="peso_max" placeholder="Ex: 75.0">
                        </div>
                        <div class="col-md-3">
                            <label for="toleranciaCat" class="form-label fw-bold">Tolerância (kg):</label>
                            <input type="number" step="0.1" class="form-control" id="toleranciaCat" name="tolerancia_peso_kg" value="0.5">
                        </div>
                        <div class="col-md-3">
                            <label for="exigePesagemCat" class="form-label fw-bold">Exige Balança?</label>
                            <select class="form-select" id="exigePesagemCat" name="exige_pesagem">
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="duracaoCat" class="form-label fw-bold">Duração Regulamentar (segundos):</label>
                            <input type="number" class="form-control" id="duracaoCat" name="duracao_luta_segundos" value="180">
                            <small class="text-muted">Padrão oficial Kenshydokan: 180s (3 minutos).</small>
                        </div>
                        <div class="col-md-6">
                            <label for="chaveamentoCat" class="form-label fw-bold">Tipo de Chave:</label>
                            <select class="form-select" id="chaveamentoCat" name="tipo_chaveamento">
                                <option value="eliminatoria_simples">Eliminatória Simples</option>
                                <option value="todos_contra_todos">Todos contra Todos (Round Robin)</option>
                                <option value="notas_ranking">Ranking por Notas (Kata/Tameshiwari)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Salvar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
