<?php
$pageTitle = 'Editar Campeonato | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";

$id = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($id);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-edit text-danger me-2"></i>Editar Campeonato
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?></p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="detalhes_campeonato.php?id=<?= $id ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="../controllers/campeonatoController.php">
            <input type="hidden" name="tipo" value="editar_campeonato">
            <input type="hidden" name="id_campeonato" value="<?= $id ?>">

            <div class="row g-3">
                <div class="col-md-8">
                    <label for="titulo" class="form-label fw-bold">Título Oficial do Evento: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($campeonato['titulo']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="edicao" class="form-label fw-bold">Edição:</label>
                    <input type="text" class="form-control" id="edicao" name="edicao" value="<?= htmlspecialchars($campeonato['edicao'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label for="subtitulo" class="form-label fw-bold">Subtítulo / Tema:</label>
                    <input type="text" class="form-control" id="subtitulo" name="subtitulo" value="<?= htmlspecialchars($campeonato['subtitulo'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="ano" class="form-label fw-bold">Ano: <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="ano" name="ano" value="<?= $campeonato['ano'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="data_inicio" class="form-label fw-bold">Data de Início: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?= $campeonato['data_inicio'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="data_fim" class="form-label fw-bold">Data de Término: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= $campeonato['data_fim'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="data_pesagem" class="form-label fw-bold">Data da Pesagem Oficial:</label>
                    <input type="date" class="form-control" id="data_pesagem" name="data_pesagem" value="<?= $campeonato['data_pesagem'] ?>">
                </div>

                <div class="col-md-4">
                    <label for="data_referencia_idade" class="form-label fw-bold">Data Ref. Idade: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_referencia_idade" name="data_referencia_idade" value="<?= $campeonato['data_referencia_idade'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label fw-bold">Status Atual:</label>
                    <select class="form-select" id="status" name="status">
                        <option value="rascunho" <?= ($campeonato['status'] === 'rascunho') ? 'selected' : '' ?>>Rascunho</option>
                        <option value="inscricoes_abertas" <?= ($campeonato['status'] === 'inscricoes_abertas') ? 'selected' : '' ?>>Inscrições Abertas</option>
                        <option value="inscricoes_encerradas" <?= ($campeonato['status'] === 'inscricoes_encerradas') ? 'selected' : '' ?>>Inscrições Encerradas</option>
                        <option value="pesagem" <?= ($campeonato['status'] === 'pesagem') ? 'selected' : '' ?>>Pesagem Oficial</option>
                        <option value="chaveamento" <?= ($campeonato['status'] === 'chaveamento') ? 'selected' : '' ?>>Chaveamento / Sorteio</option>
                        <option value="em_andamento" <?= ($campeonato['status'] === 'em_andamento') ? 'selected' : '' ?>>Em Andamento (Combates)</option>
                        <option value="finalizado" <?= ($campeonato['status'] === 'finalizado') ? 'selected' : '' ?>>Finalizado</option>
                        <option value="cancelado" <?= ($campeonato['status'] === 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="limite_atletas" class="form-label fw-bold">Limite Máx. de Atletas:</label>
                    <input type="number" class="form-control" id="limite_atletas" name="limite_atletas" value="<?= $campeonato['limite_atletas'] ?>">
                </div>

                <div class="col-md-6">
                    <label for="local_nome" class="form-label fw-bold">Nome do Ginásio / Local: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="local_nome" name="local_nome" value="<?= htmlspecialchars($campeonato['local_nome']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="cidade" class="form-label fw-bold">Cidade: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($campeonato['cidade']) ?>" required>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label fw-bold">UF: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase" id="estado" name="estado" maxlength="2" value="<?= htmlspecialchars($campeonato['estado']) ?>" required>
                </div>

                <div class="col-12">
                    <label for="local_endereco" class="form-label fw-bold">Endereço Completo:</label>
                    <input type="text" class="form-control" id="local_endereco" name="local_endereco" value="<?= htmlspecialchars($campeonato['local_endereco']) ?>">
                </div>

                <div class="col-12">
                    <label for="descricao" class="form-label fw-bold">Informações e Regulamento:</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= htmlspecialchars($campeonato['descricao'] ?? '') ?></textarea>
                </div>

                <div class="col-12 text-end mt-4 pt-3 border-top">
                    <a href="detalhes_campeonato.php?id=<?= $id ?>" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Atualizar Dados
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
