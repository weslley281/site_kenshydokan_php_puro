<?php
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../../models/exameGraduacaoModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$exameRepositorio = new ExameGraduacaoModel();
$graduacaoRepositorio = new Graduacao();

$exame = $exameRepositorio->buscarPorId($_GET['id']);
$graduacoes = $graduacaoRepositorio->listarGraduacoes();

$documento = $exame['documento'];
$link_doc = !empty($documento) ? '../../arquivos/' . $documento : '';
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-weight-bold text-dark mb-0">Editar Exame de Graduação</h2>
        <a href="index.php?pagina=exames" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para Exames
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 650px; margin: 0 auto;">
        <div class="card-body p-4">
            <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-graduation-cap mr-2"></i>Solicitação de Exame</h5>

            <form action="../../controllers/admin/exameController.php" method="post">
                <input type="hidden" name="id" value="<?php echo $exame['id']; ?>">

                <div class="form-group mb-3">
                    <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Candidato</label>
                    <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="nome" name="nome" value="<?php echo htmlspecialchars($exame['nome']); ?>" required>
                </div>

                <div class="form-row mb-3 align-items-end">
                    <div class="form-group col-md-9 mb-0">
                        <label for="documento" class="text-secondary small font-weight-bold text-uppercase">Nome do Arquivo (Documento)</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="documento" name="documento" value="<?php echo htmlspecialchars($documento); ?>">
                    </div>
                    <div class="form-group col-md-3 mb-0 text-right">
                        <?php if (!empty($documento)): ?>
                            <a href="<?php echo htmlspecialchars($link_doc); ?>" target="_blank" class="btn btn-danger btn-block font-weight-bold rounded-pill shadow-sm px-2 py-2" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-file-pdf mr-1"></i> Ver Doc
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="form-group col-md-6">
                        <label for="graduacao_atual" class="text-secondary small font-weight-bold text-uppercase">Graduação Atual</label>
                        <select class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" id="graduacao_atual" name="id_graduacao_atual">
                            <?php foreach ($graduacoes as $graduacao) : ?>
                                <option value="<?php echo $graduacao['id_graduacao']; ?>" <?php echo ($graduacao['id_graduacao'] == $exame['id_graduacao_atual']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($graduacao['graduacao']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="graduacao_pretendida" class="text-secondary small font-weight-bold text-uppercase">Graduação Pretendida</label>
                        <select class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" id="graduacao_pretendida" name="id_graduacao_pretendida">
                            <?php foreach ($graduacoes as $graduacao) : ?>
                                <option value="<?php echo $graduacao['id_graduacao']; ?>" <?php echo ($graduacao['id_graduacao'] == $exame['id_graduacao_pretendida']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($graduacao['graduacao']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="situacao" class="text-secondary small font-weight-bold text-uppercase">Situação</label>
                    <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="situacao" name="situacao">
                        <option value="aprovado" <?php echo ($exame['situacao'] == 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                        <option value="aguardando" <?php echo ($exame['situacao'] == 'aguardando') ? 'selected' : ''; ?>>Aguardando</option>
                        <option value="reprovado" <?php echo ($exame['situacao'] == 'reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                    <a href="index.php?pagina=exames" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>