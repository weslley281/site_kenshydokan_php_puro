<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/config.php';
// Adicione a verificação de autenticação se necessário
?>
<?php include_once __DIR__ . '/../menu.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Adicionar Novo Dojô</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detalhes do Dojô</h6>
        </div>
        <div class="card-body">
            <form action="../../controllers/dojoController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="inserir">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="razao_social">Razão Social</label>
                        <input type="text" class="form-control" id="razao_social" name="razao_social" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nome_fantasia">Nome Fantasia</label>
                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj">
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
                                echo '<option value="' . $filiado['id_filiado'] . '">' . htmlspecialchars($filiado['nome']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="celular">Celular</label>
                        <input type="text" class="form-control" id="celular" name="celular">
                    </div>
                </div>

                <hr>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep">
                    </div>
                    <div class="form-group col-md-7">
                        <label for="endereco">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="estado">Estado</label>
                        <input type="text" class="form-control" id="estado" name="estado">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="data_filiacao">Data de Filiação</label>
                        <input type="date" class="form-control" id="data_filiacao" name="data_filiacao" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <label for="imagem">Logo do Dojô</label>
                    <input type="file" class="form-control-file" id="imagem" name="imagem">
                </div>

                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="dojos.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../rodape.php'; ?>
