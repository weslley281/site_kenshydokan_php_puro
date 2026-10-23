<?php
$pageTitle = 'Categorias | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = $cid;
$categorias = (new Categoria())->buscarPorCampeonato($idCampeonato);

$nomeModalidade = ['kumite' => 'Luta (Kumite)', 'kata' => 'Kata', 'tameshiwari' => 'Quebramento'];
$nomeSexo = ['M' => 'Masculino', 'F' => 'Feminino', 'misto' => 'Misto'];
?>

<div class="page-head">
    <div>
        <h1>2. Categorias</h1>
        <p>Crie as categorias em que os atletas vão competir. Ex.: <em>Kumite Adulto Masculino até 70 kg</em>.</p>
    </div>
    <a href="inscricoes.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary">Próxima etapa: Atletas <i class="fas fa-arrow-right"></i></a>
</div>

<div class="row g-4">
    <!-- Formulário -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header py-3"><i class="fas fa-plus text-danger me-2"></i>Nova categoria</div>
            <div class="card-body">
                <form method="POST" action="../controllers/categoriaController.php"><?= csrfCampo() ?>
                    <input type="hidden" name="tipo" value="criar_categoria">
                    <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">

                    <div class="mb-3">
                        <label class="form-label" for="nomeCat">Nome da categoria</label>
                        <input type="text" class="form-control" id="nomeCat" name="nome" required placeholder="Ex.: Adulto Masculino até 70 kg">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label" for="modalidadeCat">Tipo de disputa</label>
                            <select class="form-select" id="modalidadeCat" name="modalidade" required>
                                <option value="kumite">Luta (Kumite)</option>
                                <option value="kata">Kata</option>
                                <option value="tameshiwari">Quebramento</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="sexoCat">Sexo</label>
                            <select class="form-select" id="sexoCat" name="sexo" required>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="misto">Misto</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label" for="idadeMinCat">Idade de</label>
                            <input type="number" class="form-control" id="idadeMinCat" name="idade_min" value="18" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="idadeMaxCat">até (anos)</label>
                            <input type="number" class="form-control" id="idadeMaxCat" name="idade_max" value="35" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3" id="blocoPeso">
                        <div class="col-6">
                            <label class="form-label" for="pesoMinCat">Peso de (kg)</label>
                            <input type="number" step="0.1" class="form-control" id="pesoMinCat" name="peso_min" placeholder="Sem mínimo">
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="pesoMaxCat">até (kg)</label>
                            <input type="number" step="0.1" class="form-control" id="pesoMaxCat" name="peso_max" placeholder="Sem limite">
                        </div>
                    </div>

                    <details class="mb-3">
                        <summary class="text-muted">Opções avançadas</summary>
                        <div class="row g-3 mt-1">
                            <div class="col-6">
                                <label class="form-label" for="exigePesagemCat">Precisa pesar?</label>
                                <select class="form-select" id="exigePesagemCat" name="exige_pesagem">
                                    <option value="1">Sim</option>
                                    <option value="0">Não</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="toleranciaCat">Margem no peso (kg)</label>
                                <input type="number" step="0.1" class="form-control" id="toleranciaCat" name="tolerancia_peso_kg" value="0.5">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="duracaoCat">Tempo de luta (segundos)</label>
                                <input type="number" class="form-control" id="duracaoCat" name="duracao_luta_segundos" value="180">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="chaveamentoCat">Forma de disputa</label>
                                <select class="form-select" id="chaveamentoCat" name="tipo_chaveamento">
                                    <option value="eliminatoria_simples">Quem perde sai</option>
                                    <option value="todos_contra_todos">Todos contra todos</option>
                                    <option value="notas_ranking">Por notas</option>
                                </select>
                            </div>
                        </div>
                    </details>

                    <button type="submit" class="btn btn-danger w-100"><i class="fas fa-check"></i> Salvar categoria</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header py-3">Categorias criadas (<?= count($categorias) ?>)</div>
            <?php if (empty($categorias)): ?>
                <div class="empty"><i class="fas fa-layer-group"></i>Nenhuma categoria ainda. Use o formulário ao lado.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Categoria</th><th>Tipo</th><th>Idade</th><th>Peso</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($cat['nome']) ?></strong><br><small class="text-muted"><?= $nomeSexo[$cat['sexo']] ?? $cat['sexo'] ?></small></td>
                                <td><?= $nomeModalidade[$cat['modalidade']] ?? $cat['modalidade'] ?></td>
                                <td><?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?></td>
                                <td>
                                    <?php if ($cat['exige_pesagem'] == 1 && ($cat['peso_min'] || $cat['peso_max'])): ?>
                                        <?= $cat['peso_min'] ? number_format($cat['peso_min'], 1, ',', '') : '0' ?> a <?= $cat['peso_max'] ? number_format($cat['peso_max'], 1, ',', '') . ' kg' : 'livre' ?>
                                    <?php else: ?>
                                        <span class="text-muted">Livre</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="../controllers/categoriaController.php" onsubmit="return confirm('Apagar esta categoria?');"><?= csrfCampo() ?>
                                        <input type="hidden" name="tipo" value="excluir_categoria">
                                        <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                                        <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Apagar"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
