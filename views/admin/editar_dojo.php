<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/dojoModel.php';

// Verificação de autenticação
if (!isset($_SESSION['id_usuario']) || $_SESSION['nivel'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    header("Location: index.php?pagina=dojos");
    exit();
}

$id_dojo = $_GET['id'];
$dojoRepositorio = new DojoModel();
$dojo = DojoModel::getDojoById($id_dojo);

// Se o dojô não for encontrado, redireciona
if (!$dojo) {
    header("Location: index.php?pagina=dojos");
    exit();
}

$imagem = $dojo->getImagem();
$caminho_logo = !empty($imagem) ? '../../img/' . $imagem : '../../arquivos/sem_imagem.png';

include_once __DIR__ . '/menu.php';
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-weight-bold text-dark mb-0">Editar Dojô</h2>
        <a href="index.php?pagina=dojos" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-2"></i> Voltar para Dojôs
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body p-4">
            <form action="../../controllers/dojoController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="editar">
                <input type="hidden" name="id_dojo" value="<?php echo $dojo->getIdDojo(); ?>">
                <input type="hidden" name="imagem_antiga" value="<?php echo htmlspecialchars($dojo->getImagem()); ?>">

                <!-- Preview e Upload da Logo -->
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <img id="imagePreview" src="<?php echo $caminho_logo; ?>" alt="Prévia do Logo" class="rounded-circle border border-danger shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border-width: 2px !important;">
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="text-secondary small font-weight-bold text-uppercase d-block">Alterar Logo do Dojô</label>
                        <div class="custom-file mb-2">
                            <input type="file" class="custom-file-input" id="imagem" name="imagem" accept="image/*">
                            <label class="custom-file-label text-truncate shadow-sm" for="imagem" data-browse="Escolher">Selecionar nova logo...</label>
                        </div>
                        <small class="form-text text-muted">Deixe em branco para manter a logo atual. Formatos recomendados: JPG, PNG.</small>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Seção 1: Identificação -->
                <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-id-card mr-2"></i>Identificação</h5>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="razao_social" class="text-secondary small font-weight-bold text-uppercase">Razão Social</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="razao_social" name="razao_social" value="<?php echo htmlspecialchars($dojo->getRazaoSocial()); ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nome_fantasia" class="text-secondary small font-weight-bold text-uppercase">Nome Fantasia</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="nome_fantasia" name="nome_fantasia" value="<?php echo htmlspecialchars($dojo->getNomeFantasia()); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cnpj" class="text-secondary small font-weight-bold text-uppercase">CNPJ</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="cnpj" name="cnpj" value="<?php echo htmlspecialchars($dojo->getCnpj()); ?>" placeholder="00.000.000/0000-00">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_filiado_responsavel" class="text-secondary small font-weight-bold text-uppercase">Responsável</label>
                        <select class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" id="id_filiado_responsavel" name="id_filiado_responsavel" required>
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

                <hr class="my-4">

                <!-- Seção 2: Contato e Status -->
                <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-address-book mr-2"></i>Contato e Filiação</h5>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email" class="text-secondary small font-weight-bold text-uppercase">Email</label>
                        <input type="email" class="form-control form-control-lg bg-light border-0 shadow-sm" id="email" name="email" value="<?php echo htmlspecialchars($dojo->getEmail()); ?>" placeholder="contato@dojo.com">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="telefone" name="telefone" value="<?php echo htmlspecialchars($dojo->getTelefone()); ?>" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" placeholder="(00) 0000-0000">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="celular" class="text-secondary small font-weight-bold text-uppercase">Celular</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="celular" name="celular" value="<?php echo htmlspecialchars($dojo->getCelular()); ?>" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" placeholder="(00) 00000-0000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="data_filiacao" class="text-secondary small font-weight-bold text-uppercase">Data de Filiação</label>
                        <input type="date" class="form-control form-control-lg bg-light border-0 shadow-sm" id="data_filiacao" name="data_filiacao" value="<?php echo htmlspecialchars($dojo->getDataFiliacao()); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status" class="text-secondary small font-weight-bold text-uppercase">Status</label>
                        <select class="form-control form-control-lg bg-light border-0 shadow-sm" id="status" name="status">
                            <option value="ativo" <?php echo ($dojo->getStatus() == 'ativo') ? 'selected' : ''; ?>>Ativo</option>
                            <option value="inativo" <?php echo ($dojo->getStatus() == 'inativo') ? 'selected' : ''; ?>>Inativo</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Seção 3: Localização -->
                <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-map-location-dot mr-2"></i>Localização</h5>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="cep" class="text-secondary small font-weight-bold text-uppercase">CEP</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="cep" name="cep" value="<?php echo htmlspecialchars($dojo->getCep()); ?>" placeholder="00000-000">
                    </div>
                    <div class="form-group col-md-7">
                        <label for="endereco" class="text-secondary small font-weight-bold text-uppercase">Endereço</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="endereco" name="endereco" value="<?php echo htmlspecialchars($dojo->getEndereco()); ?>" placeholder="Rua, Número, Bairro">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="cidade" class="text-secondary small font-weight-bold text-uppercase">Cidade</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="cidade" name="cidade" value="<?php echo htmlspecialchars($dojo->getCidade()); ?>" placeholder="Cidade">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="estado" class="text-secondary small font-weight-bold text-uppercase">Estado</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="estado" name="estado" value="<?php echo htmlspecialchars($dojo->getEstado()); ?>" placeholder="Estado">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
                        <i class="fas fa-save mr-2"></i> Salvar Alterações
                    </button>
                    <a href="index.php?pagina=dojos" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Atualiza o texto do input file ao selecionar um arquivo
        var fileInput = document.getElementById('imagem');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'Selecionar nova logo...';
                var nextLabel = e.target.nextElementSibling;
                if (nextLabel) {
                    nextLabel.innerHTML = fileName;
                }
            });
        }
    });
</script>

<?php include_once __DIR__ . '/../rodape.php'; ?>
