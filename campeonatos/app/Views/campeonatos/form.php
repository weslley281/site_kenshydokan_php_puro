<?php
use App\Core\Router;
use App\Core\Csrf;

$base = Router::getBasePath();
$isEdit = !empty($campeonato);
$action = $isEdit ? "{$base}/campeonatos/{$campeonato['id_campeonato']}/atualizar" : "{$base}/campeonatos/salvar";
$mods = $isEdit ? explode(',', $campeonato['modalidades_habilitadas']) : ['kumite', 'kata', 'tameshiwari'];
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-kenshy">
            <div class="card-header-kenshy">
                <span>
                    <i class="fa-solid fa-trophy text-danger me-2"></i>
                    <?= $isEdit ? 'Editar Campeonato: ' . htmlspecialchars($campeonato['titulo']) : 'Cadastrar Novo Campeonato' ?>
                </span>
                <a href="<?= $base ?>/campeonatos" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
            <div class="card-body p-4">
                <form action="<?= $action ?>" method="POST">
                    <?= Csrf::field() ?>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-muted small fw-semibold">Título Oficial do Evento *</label>
                            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($campeonato['titulo'] ?? '') ?>" placeholder="Ex: 1° Campeonato Estadual Kenshydokan Jutsu" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-semibold">Edição</label>
                            <input type="text" name="edicao" class="form-control" value="<?= htmlspecialchars($campeonato['edicao'] ?? '1ª Edição') ?>" placeholder="Ex: 1ª Edição">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Local / Ginásio *</label>
                            <input type="text" name="local" class="form-control" value="<?= htmlspecialchars($campeonato['local'] ?? '') ?>" placeholder="Ex: Ginásio Poliesportivo Fiotão" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-semibold">Cidade *</label>
                            <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($campeonato['cidade'] ?? '') ?>" placeholder="Ex: Várzea Grande" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label text-muted small fw-semibold">Estado (UF) *</label>
                            <input type="text" name="estado" class="form-control" value="<?= htmlspecialchars($campeonato['estado'] ?? 'MT') ?>" maxlength="2" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-semibold">Data de Início *</label>
                            <input type="date" name="data_inicio" class="form-control" value="<?= htmlspecialchars($campeonato['data_inicio'] ?? date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-semibold">Data de Término *</label>
                            <input type="date" name="data_fim" class="form-control" value="<?= htmlspecialchars($campeonato['data_fim'] ?? date('Y-m-d')) ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-semibold">Data de Referência (Idade) *</label>
                            <input type="date" name="data_referencia_idade" class="form-control" value="<?= htmlspecialchars($campeonato['data_referencia_idade'] ?? date('Y-m-d')) ?>" required>
                            <div class="text-muted small" style="font-size: 0.75rem;">Data base para cálculo da idade dos atletas</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Entidade / Organizador *</label>
                            <input type="text" name="organizador" class="form-control" value="<?= htmlspecialchars($campeonato['organizador'] ?? 'Instituto Kenshydokan de Artes Marciais') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Modalidades Habilitadas</label>
                            <div class="d-flex gap-3 pt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="modalidades[]" value="kumite" id="modKumite" <?= in_array('kumite', $mods) ? 'checked' : '' ?>>
                                    <label class="form-check-label text-white" for="modKumite">Kumite</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="modalidades[]" value="kata" id="modKata" <?= in_array('kata', $mods) ? 'checked' : '' ?>>
                                    <label class="form-check-label text-white" for="modKata">Kata</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="modalidades[]" value="tameshiwari" id="modTam" <?= in_array('tameshiwari', $mods) ? 'checked' : '' ?>>
                                    <label class="form-check-label text-white" for="modTam">Tameshiwari</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-kenshy px-4 py-2">
                                <i class="fa-solid fa-floppy-disk me-1"></i> <?= $isEdit ? 'Salvar Alterações' : 'Salvar Campeonato' ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
