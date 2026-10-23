<?php
require_once __DIR__ . "/../controllers/authHelper.php";
exigirPerfil([], 'inicio.php'); // somente administrador
$pageTitle = 'Novo Campeonato | Kenshydokan';
include_once __DIR__ . "/menu.php";
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-plus-circle text-danger me-2"></i>Cadastrar Novo Campeonato
        </h2>
        <p class="text-muted small mb-0">Preencha os dados oficiais do evento Kenshydokan Jutsu</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="campeonatos.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
        <span class="p-2 bg-danger bg-opacity-10 text-danger rounded-3">
            <i class="fas fa-file-alt"></i>
        </span>
        <div>
            <h5 class="card-title mb-0 fs-6 fw-bold text-dark">Formulário de Cadastro do Campeonato</h5>
            <small class="text-muted">Preencha os dados e datas regulamentares do evento</small>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="../controllers/campeonatoController.php"><?= csrfCampo() ?>
            <input type="hidden" name="tipo" value="criar_campeonato">

            <div class="row g-3">
                <div class="col-md-8">
                    <label for="titulo" class="form-label fw-bold">Título Oficial do Evento: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required placeholder="Ex: Campeonato Brasileiro Kenshydokan Jutsu">
                </div>
                <div class="col-md-4">
                    <label for="edicao" class="form-label fw-bold">Edição:</label>
                    <input type="text" class="form-control" id="edicao" name="edicao" placeholder="Ex: 5ª Edição">
                </div>

                <div class="col-12">
                    <label for="subtitulo" class="form-label fw-bold">Subtítulo / Tema:</label>
                    <input type="text" class="form-control" id="subtitulo" name="subtitulo" placeholder="Ex: Copa Samurai de Karatê e Jutsu">
                </div>

                <div class="col-md-3">
                    <label for="ano" class="form-label fw-bold">Ano: <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="ano" name="ano" value="<?= date('Y') ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="data_inicio" class="form-label fw-bold">Data de Início: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label for="data_fim" class="form-label fw-bold">Data de Término: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" required value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label for="data_pesagem" class="form-label fw-bold">Data da Pesagem Oficial:</label>
                    <input type="date" class="form-control" id="data_pesagem" name="data_pesagem" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="col-md-4">
                    <label for="data_referencia_idade" class="form-label fw-bold">Data Ref. Idade: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data_referencia_idade" name="data_referencia_idade" required value="<?= date('Y-m-d') ?>">
                    <small class="text-muted">Data para cálculo exato da idade dos atletas.</small>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label fw-bold">Status Inicial:</label>
                    <select class="form-select" id="status" name="status">
                        <option value="rascunho">Rascunho</option>
                        <option value="inscricoes_abertas">Inscrições Abertas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="limite_atletas" class="form-label fw-bold">Limite Máx. de Atletas:</label>
                    <input type="number" class="form-control" id="limite_atletas" name="limite_atletas" placeholder="Opcional">
                </div>

                <div class="col-md-6">
                    <label for="local_nome" class="form-label fw-bold">Nome do Ginásio / Local: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="local_nome" name="local_nome" required placeholder="Ex: Ginásio Municipal Poliesportivo">
                </div>
                <div class="col-md-4">
                    <label for="cidade" class="form-label fw-bold">Cidade: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cidade" name="cidade" required placeholder="Ex: São Paulo">
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label fw-bold">UF: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase" id="estado" name="estado" maxlength="2" required value="SP">
                </div>

                <div class="col-12">
                    <label for="local_endereco" class="form-label fw-bold">Endereço Completo:</label>
                    <input type="text" class="form-control" id="local_endereco" name="local_endereco" placeholder="Rua, número, bairro">
                </div>

                <div class="col-12">
                    <label for="descricao" class="form-label fw-bold">Informações e Regulamento:</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Detalhes gerais, premiação, cronograma..."></textarea>
                </div>

                <div class="col-12 text-end mt-4 pt-3 border-top">
                    <a href="campeonatos.php" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="fas fa-check me-1"></i> Salvar Campeonato
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
