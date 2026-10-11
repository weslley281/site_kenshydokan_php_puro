<?php
use App\Core\Router;
use App\Core\Csrf;

$base = Router::getBasePath();
$idCamp = $campeonato['id_campeonato'];
?>
<div class="row g-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="text-muted small mb-1">
                <a href="<?= $base ?>/campeonatos/<?= $idCamp ?>" class="text-decoration-none text-muted">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar ao Painel do Campeonato
                </a>
            </div>
            <h2 class="fw-bold text-white mb-1">Categorias: <?= htmlspecialchars($campeonato['titulo']) ?></h2>
            <p class="text-muted mb-0">Configuração de divisões por modalidade, idade, peso e sexo</p>
        </div>
        <div class="d-flex gap-2">
            <form action="<?= $base ?>/campeonatos/<?= $idCamp ?>/categorias/modelo-padrao" method="POST" class="d-inline">
                <?= Csrf::field() ?>
                <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Deseja carregar as categorias do modelo padrão oficial?')">
                    <i class="fa-solid fa-file-import me-1"></i> Carregar Modelo Padrão
                </button>
            </form>
            <button class="btn btn-kenshy" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
                <i class="fa-solid fa-plus me-1"></i> Nova Categoria
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <div class="card-kenshy">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Modalidade</th>
                                <th>Nome da Categoria</th>
                                <th>Sexo</th>
                                <th>Faixa Etária</th>
                                <th>Limites de Peso</th>
                                <th>Nível / Graduação</th>
                                <th>Balança</th>
                                <th>Inscritos</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias as $cat): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-<?= $cat['modalidade'] === 'kumite' ? 'danger' : ($cat['modalidade'] === 'kata' ? 'warning text-dark' : 'info text-dark') ?>">
                                            <?= strtoupper($cat['modalidade']) ?>
                                        </span>
                                    </td>
                                    <td><strong><?= htmlspecialchars($cat['nome']) ?></strong></td>
                                    <td><?= $cat['sexo'] === 'M' ? 'Masculino' : ($cat['sexo'] === 'F' ? 'Feminino' : 'Misto') ?></td>
                                    <td><?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?> anos</td>
                                    <td>
                                        <?php if ($cat['peso_min'] || $cat['peso_max']): ?>
                                            <?= $cat['peso_min'] ? $cat['peso_min'] . 'kg' : '0' ?> a <?= $cat['peso_max'] ? $cat['peso_max'] . 'kg' : 'Sem limite' ?>
                                        <?php else: ?>
                                            <span class="text-muted">Livre / Absoluto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($cat['nivel_competitivo']) ?></td>
                                    <td>
                                        <?= $cat['exige_pesagem'] ? '<span class="badge bg-danger">Obrigatória</span>' : '<span class="badge bg-secondary">Isento</span>' ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?= $cat['total_inscritos'] ?> inscritos</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= $base ?>/chaves/<?= $cat['id_categoria'] ?>/visualizar" class="btn btn-sm btn-outline-light me-1" title="Ver Chave">
                                            <i class="fa-solid fa-sitemap"></i>
                                        </a>
                                        <form action="<?= $base ?>/categorias/<?= $cat['id_categoria'] ?>/deletar" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente remover esta categoria?')">
                                            <?= Csrf::field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NOVA CATEGORIA -->
<div class="modal fade" id="modalNovaCategoria" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content card-kenshy text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fa-solid fa-plus text-danger me-2"></i>Nova Categoria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= $base ?>/categorias/salvar" method="POST">
                <?= Csrf::field() ?>
                <input type="hidden" name="id_campeonato" value="<?= $idCamp ?>">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Modalidade *</label>
                            <select name="modalidade" class="form-select" required>
                                <option value="kumite">Kumite (Combate)</option>
                                <option value="kata">Kata (Formas)</option>
                                <option value="tameshiwari">Tameshiwari (Quebramento)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Sexo *</label>
                            <select name="sexo" class="form-select" required>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="misto">Misto / Aberto</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted small fw-semibold">Nome da Categoria *</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Kumite Adulto Masculino Médio (-80kg)" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-semibold">Idade Mínima</label>
                            <input type="number" name="idade_min" class="form-control" value="18" min="4" max="99">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-semibold">Idade Máxima</label>
                            <input type="number" name="idade_max" class="form-control" value="35" min="4" max="99">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-semibold">Peso Mínimo (kg)</label>
                            <input type="number" step="0.01" name="peso_min" class="form-control" placeholder="Ex: 70.01">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-semibold">Peso Máximo (kg)</label>
                            <input type="number" step="0.01" name="peso_max" class="form-control" placeholder="Ex: 80.00">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Nível Competitivo</label>
                            <input type="text" name="nivel_competitivo" class="form-control" value="Geral" placeholder="Ex: Iniciante, Geral, Master">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Exige Pesagem Oficial?</label>
                            <select name="exige_pesagem" class="form-select">
                                <option value="1">Sim (Obrigatória na balança)</option>
                                <option value="0">Não (Isento de pesagem)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-kenshy">Salvar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>
