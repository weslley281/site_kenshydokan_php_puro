<?php
include_once __DIR__ . "/../menu.php";
include_once __DIR__ . "/../../models/exameGraduacaoModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$exameRepositorio = new ExameGraduacaoModel();
$graduacaoRepositorio = new Graduacao();

$exame = $exameRepositorio->buscarPorId($_GET['id']);
$graduacoes = $graduacaoRepositorio->listarGraduacoes();

?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Editar Exame de Graduação</h5>
                    <form class="form-signin" action="../../controllers/admin/exameController.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $exame['id']; ?>">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $exame['nome']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="documento">Documento:</label>
                            <input type="text" class="form-control" id="documento" name="documento" value="<?php echo $exame['documento']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="graduacao_atual">Graduação Atual:</label>
                            <select class="form-control" id="graduacao_atual" name="id_graduacao_atual">
                                <?php foreach ($graduacoes as $graduacao) : ?>
                                    <option value="<?php echo $graduacao['id_graduacao']; ?>" <?php echo ($graduacao['id_graduacao'] == $exame['id_graduacao_atual']) ? 'selected' : ''; ?>><?php echo $graduacao['graduacao']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="graduacao_pretendida">Graduação Pretendida:</label>
                            <select class="form-control" id="graduacao_pretendida" name="id_graduacao_pretendida">
                                <?php foreach ($graduacoes as $graduacao) : ?>
                                    <option value="<?php echo $graduacao['id_graduacao']; ?>" <?php echo ($graduacao['id_graduacao'] == $exame['id_graduacao_pretendida']) ? 'selected' : ''; ?>><?php echo $graduacao['graduacao']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="situacao">Situação:</label>
                            <select class="form-control" id="situacao" name="situacao">
                                <option value="aprovado" <?php echo ($exame['situacao'] == 'aprovado') ? 'selected' : ''; ?>>Aprovado</option>
                                <option value="aguardando" <?php echo ($exame['situacao'] == 'aguardando') ? 'selected' : ''; ?>>Aguardando</option>
                                <option value="reprovado" <?php echo ($exame['situacao'] == 'reprovado') ? 'selected' : ''; ?>>Reprovado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary btn-block text-uppercase">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/../rodape.php"; ?>