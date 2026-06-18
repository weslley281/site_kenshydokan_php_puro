<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/models/dojoModel.php';

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    header("Location: dojos.php");
    exit();
}

$id_dojo = $_GET['id'];
$dojoRepositorio = new DojoModel();
$dojo = $dojoRepositorio->buscarDojoPorId($id_dojo);

// Se o dojô não for encontrado, redireciona
if (!$dojo) {
    header("Location: dojos.php");
    exit();
}

include_once __DIR__ . '/../menu.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Editar Dojô</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalhes do Dojô</h6>
        </div>
        <div class="card-body">
            <form action="../../controllers/dojoController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="editar">
                <input type="hidden" name="id" value="<?php echo $dojo->getId(); ?>">
                <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($dojo->getImagem()); ?>">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="razao_social">Razão Social</label>
                        <input type="text" class="form-control" id="razao_social" name="razao_social" value="<?php echo htmlspecialchars($dojo->getRazaoSocial()); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nome_fantasia">Nome Fantasia</label>
                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" value="<?php echo htmlspecialchars($dojo->getNomeFantasia()); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($dojo->getCnpj()); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_filiado_responsavel">Responsável</label>
                        <select class="form-control" id="id_filiado_responsavel" name="id_filiado_responsavel" required>
                            <option value="">Selecione um responsável...</option>
                            <?php
                            require_once __DIR__ . '/../../models/filiadoModel.php';
                            $filiadoRepo = new FiliadoModel();
                            $filiados = $filiadoRepo->listarFiliados();
                            foreach ($filiados as $filiado) {
                                $selected = ($filiado['id_filiado'] == $dojo->getIdFiliadoResponsavel()) ? 'selected' : '';
                                echo '<option value="' . $filiado['id_filiado'] . '" ' . $selected . '>' . htmlspecialchars($filiado['nome']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($dojo->getEmail()); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($dojo->getTelefone()); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="celular">Celular</label>
                        <input type="text" class="form-control" id="celular" name="celular" value="<?php echo htmlspecialchars($dojo->getCelular()); ?>">
                    </div>
                </div>

                <hr>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" value="<?php echo htmlspecialchars($dojo->getCep()); ?>">
                    </div>
                    <div class="form-group col-md-7">
                        <label for="endereco">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($dojo->getEndereco()); ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo htmlspecialchars($dojo->getCidade()); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" id="estado" name="estado" value="<?php echo htmlspecialchars($dojo->getEstado()); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="data_filiacao">Data de Filiação</label>
                        <input type="date" class="form-control" id="data_filiacao" name="data_filiacao" value="<?php echo htmlspecialchars($dojo->getDataFiliacao()); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="ativo" <?php echo ($dojo->getStatus() == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                            <option value="inativo" <?php echo ($dojo->getStatus() == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <label>Logo Atual</label><br>
                    <img src="../../img/<?php echo htmlspecialchars($dojo->getImagem()); ?>" alt="Logo Atual" width="100"><br><br>
                    <label for="imagem">Alterar Logo do Dojô (opcional)</label>
                    <input type="file" class="form-control-file" id="imagem" name="imagem">
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="dojos.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../rodape.php'; ?>
