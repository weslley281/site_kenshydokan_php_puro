<?php
// views/admin/dojos.php
require_once __DIR__ . '/../../models/dojoModel.php';
require_once __DIR__ . '/../../models/filiadoModel.php';

$dojoRepo = new DojoModel();
$filiadoRepo = new FiliadoModel();
$dojos = $dojoRepo->listarDojos();

// Se não houver nenhum dojô cadastrado, cria o padrão automaticamente
if (empty($dojos)) {
    $defaultDojo = new DojoModel(
        1,
        'Associação de Karatê Kenshydokan',
        'Kenshydokan Dojô',
        '00.000.000/0001-00',
        null,
        '(00) 0000-0000',
        '(00) 99999-9999',
        'contato@kenshydokan.com.br',
        '78000-000',
        'Rua Principal, 123',
        'Cuiabá',
        'Mato Grosso',
        date('Y-m-d'),
        'ativo',
        null
    );
    $dojoRepo->criarDojo($defaultDojo);
    $dojos = $dojoRepo->listarDojos();
}

$dojo = $dojos[0];
$id_dojo = $dojo->getIdDojo();
$razao_social = $dojo->getRazaoSocial();
$nome_fantasia = $dojo->getNomeFantasia();
$cnpj = $dojo->getCnpj();
$id_responsavel = $dojo->getIdFiliadoResponsavel();
$telefone = $dojo->getTelefone();
$celular = $dojo->getCelular();
$email = $dojo->getEmail();
$cep = $dojo->getCep();
$endereco = $dojo->getEndereco();
$cidade = $dojo->getCidade();
$estado = $dojo->getEstado();
$data_filiacao = $dojo->getDataFiliacao();
$status = $dojo->getStatus();
$imagem = $dojo->getImagem();

// Busca o nome do filiado responsável
$nome_responsavel = 'Não definido';
if ($id_responsavel) {
    $responsavel = $filiadoRepo->buscarFiliadoPorId($id_responsavel);
    if ($responsavel) {
        $nome_responsavel = $responsavel->getNome();
    }
}

$caminho_logo = !empty($imagem) ? '../../img/' . $imagem : '../../arquivos/sem_imagem.png';
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="font-weight-bold text-dark mb-0">Informações do Dojô</h2>
        <a href="editar_dojo.php?id=<?php echo $id_dojo; ?>" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-4">
            <i class="fas fa-edit mr-2"></i> Editar Informações
        </a>
    </div>

    <div class="row">
        <!-- Coluna da Logo e Status -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg text-center p-4 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <img src="<?php echo $caminho_logo; ?>" alt="Logo <?php echo htmlspecialchars($nome_fantasia); ?>" class="rounded-circle border border-danger shadow-sm mb-4" style="width: 150px; height: 150px; object-fit: cover; border-width: 3px !important;">
                    <h3 class="font-weight-bold text-dark mb-1"><?php echo htmlspecialchars($nome_fantasia); ?></h3>
                    <p class="text-muted small mb-3"><?php echo htmlspecialchars($razao_social); ?></p>
                    
                    <span class="badge badge-<?php echo $status == 'ativo' ? 'success' : 'danger'; ?> text-uppercase px-3 py-2 rounded-pill font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                        Status: <?php echo $status == 'ativo' ? 'Ativo' : 'Inativo'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Coluna com Detalhes do Dojô -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body p-4">
                    <h5 class="text-danger font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fa-solid fa-circle-info mr-2"></i>Dados Cadastrais
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Razão Social</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars($razao_social); ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">CNPJ</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars(!empty($cnpj) ? $cnpj : 'Não informado'); ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Sensei / Responsável</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars($nome_responsavel); ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Data de Filiação</span>
                            <span class="text-dark font-weight-bold">
                                <?php echo !empty($data_filiacao) ? date("d/m/Y", strtotime($data_filiacao)) : 'Não informada'; ?>
                            </span>
                        </div>
                    </div>

                    <h5 class="text-danger font-weight-bold mb-4 border-bottom pb-2 mt-4">
                        <i class="fa-solid fa-address-book mr-2"></i>Contato e Endereço
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">E-mail</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars(!empty($email) ? $email : 'Não informado'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Contatos Telefônicos</span>
                            <span class="text-dark font-weight-bold">
                                <?php 
                                $telefones = array_filter([$celular, $telefone]);
                                echo htmlspecialchars(!empty($telefones) ? implode(" / ", $telefones) : 'Não informado'); 
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Endereço</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars(!empty($endereco) ? $endereco : 'Não informado'); ?></span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">CEP</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars(!empty($cep) ? $cep : 'Não informado'); ?></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Cidade</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars(!empty($cidade) ? $cidade : 'Não informada'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-secondary small font-weight-bold text-uppercase d-block">Estado</span>
                            <span class="text-dark font-weight-bold"><?php echo htmlspecialchars(!empty($estado) ? $estado : 'Não informado'); ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>