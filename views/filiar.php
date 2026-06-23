<?php 
include "menu.php";
?>

<style>
    .pricing-header {
        background: linear-gradient(135deg, #151719 0%, #3a080d 100%);
        color: white;
        padding: 50px 0;
        margin-bottom: 40px;
        border-bottom: 4px solid #dc3545;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .pricing .card {
        border: none;
        border-radius: 1rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
        position: relative;
    }
    .pricing .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.15);
    }
    .pricing .card-header-plan {
        padding: 25px 20px;
        text-align: center;
        background-color: #ffffff;
        border-bottom: 1px solid #f1f3f5;
    }
    .pricing .card.highlighted {
        border-top: 5px solid #dc3545;
    }
    .pricing .card.highlighted .card-header-plan {
        background-color: rgba(220, 53, 69, 0.02);
    }
    .pricing .badge-plan {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 30px;
        letter-spacing: 0.5px;
    }
    .pricing .card-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #212529;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .pricing .card-price {
        font-size: 2.5rem;
        font-weight: 900;
        color: #dc3545;
        margin: 15px 0;
    }
    .pricing .card-price .period {
        font-size: 0.9rem;
        color: #868e96;
        font-weight: 500;
    }
    .pricing .fa-ul {
        margin: 0;
        padding: 10px 25px 25px 25px;
        list-style: none;
    }
    .pricing .fa-ul li {
        position: relative;
        padding-left: 28px;
        margin-bottom: 12px;
        font-size: 0.9rem;
        color: #495057;
        text-align: left;
        line-height: 1.4;
    }
    .pricing .fa-ul li i {
        position: absolute;
        left: 0;
        top: 2px;
        font-size: 1.05rem;
    }
    .pricing .btn-filiar {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-radius: 50px;
        padding: 12px 30px;
        transition: all 0.2s ease;
    }
    .section-title {
        position: relative;
        font-weight: 800;
        color: #212529;
        margin-bottom: 35px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 1.75rem;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 50px;
        height: 4px;
        background-color: #dc3545;
        margin: 12px auto 0;
        border-radius: 2px;
    }
</style>

<div class="pricing-header text-center">
    <div class="container">
        <h1 class="display-4 font-weight-bold text-uppercase">Filiação Oficial WKKA</h1>
        <p class="lead text-white-50 max-width-600 mx-auto">Filie-se à Associação Mundial Kenshydokan e faça parte de uma das instituições mais sérias e tradicionais das artes marciais.</p>
    </div>
</div>

<div class="container py-3">
    <!-- Mensagens de Feedback do Servidor -->
    <?php if (isset($_SESSION["msg_sucesso"])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check mr-3 text-success" style="font-size: 1.5rem;"></i>
                <div>
                    <?php echo $_SESSION["msg_sucesso"]; unset($_SESSION["msg_sucesso"]); ?>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION["msg_erro"])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-xmark mr-3 text-danger" style="font-size: 1.5rem;"></i>
                <div>
                    <?php echo $_SESSION["msg_erro"]; unset($_SESSION["msg_erro"]); ?>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <section class="pricing">
        <!-- SEÇÃO 1: FILIAÇÃO INDIVIDUAL -->
        <div class="text-center mb-5">
            <h2 class="section-title">Filiação Individual</h2>
            <div class="row justify-content-center">
                <!-- Atleta -->
                <div class="col-md-6 col-lg-5 mb-4">
                    <div class="card h-100">
                        <div class="card-header-plan">
                            <h5 class="card-title">Atleta</h5>
                            <h6 class="card-price">R$ 60<span class="period"> / anual</span></h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-0">
                            <ul class="fa-ul">
                                <li><i class="fa-solid fa-check text-success"></i>Registrar-se em qualquer dojô filiado à WKKA</li>
                                <li><i class="fa-solid fa-check text-success"></i>Inscrição preferencial em cursos, palestras e seminários</li>
                                <li><i class="fa-solid fa-check text-success"></i>Participação em exames oficiais de graduação</li>
                                <li><i class="fa-solid fa-check text-success"></i>Histórico marcial registrado no banco de dados geral</li>
                                <li><i class="fa-solid fa-check text-success"></i>Direito de inscrição em torneios e campeonatos da associação</li>
                                <li class="text-muted"><i class="fa-solid fa-xmark text-danger"></i>Ministrar aulas e certificar alunos</li>
                                <li class="text-muted"><i class="fa-solid fa-xmark text-danger"></i>Indicar/enviar alunos para exames e campeonatos</li>
                            </ul>
                            <div class="p-4 bg-light border-top">
                                <button type="button" class="btn btn-danger btn-block btn-filiar shadow-sm" data-toggle="modal" data-target="#filiarModal" data-plano="Atleta">
                                    Filiar-se como Atleta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professor -->
                <div class="col-md-6 col-lg-5 mb-4">
                    <div class="card h-100 highlighted">
                        <span class="badge-plan bg-danger text-white">Destaque</span>
                        <div class="card-header-plan">
                            <h5 class="card-title text-danger">Professor</h5>
                            <h6 class="card-price">R$ 120<span class="period"> / anual</span></h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-0">
                            <ul class="fa-ul">
                                <li><i class="fa-solid fa-check text-success"></i>Todos os benefícios e acessos de Atleta filiado</li>
                                <li><i class="fa-solid fa-check text-success"></i>Autorização oficial homologada para lecionar sob chancela WKKA</li>
                                <li><i class="fa-solid fa-check text-success"></i>Enviar e registrar alunos para campeonatos oficiais</li>
                                <li><i class="fa-solid fa-check text-success"></i>Indicar alunos para exames oficiais de graduação</li>
                                <li><i class="fa-solid fa-check text-success"></i>Acesso a seminários exclusivos de padronização técnica</li>
                                <li><i class="fa-solid fa-check text-success"></i>Inclusão no conselho técnico de professores</li>
                            </ul>
                            <div class="p-4 bg-light border-top">
                                <button type="button" class="btn btn-danger btn-block btn-filiar shadow-sm" data-toggle="modal" data-target="#filiarModal" data-plano="Professor">
                                    Filiar-se como Professor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEÇÃO 2: FILIAÇÃO DE ENTIDADES -->
        <div class="text-center mt-4 mb-5">
            <h2 class="section-title">Filiação de Dojôs e Entidades</h2>
            <div class="row justify-content-center">
                <!-- Dojo Particular -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 highlighted">
                        <span class="badge-plan bg-dark text-white">Recomendado</span>
                        <div class="card-header-plan">
                            <h5 class="card-title text-dark">Dojô Particular</h5>
                            <h6 class="card-price">R$ 300<span class="period"> / anual</span></h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-0">
                            <ul class="fa-ul">
                                <li><i class="fa-solid fa-check text-success"></i>Certificado oficial de Dojô Homologado WKKA</li>
                                <li><i class="fa-solid fa-check text-success"></i>Autorização para uso da marca e identidade visual</li>
                                <li><i class="fa-solid fa-check text-success"></i>Divulgação completa na página oficial de Dojôs do portal</li>
                                <li><i class="fa-solid fa-check text-success"></i>Gerenciamento de mensalidades e caixa integrado no sistema</li>
                                <li><i class="fa-solid fa-check text-success"></i>Faturamento em lote de alunos direto pelo dashboard</li>
                                <li><i class="fa-solid fa-check text-success"></i>Direito de sediar torneios regionais autorizados</li>
                            </ul>
                            <div class="p-4 bg-light border-top">
                                <button type="button" class="btn btn-dark btn-block btn-filiar shadow-sm" data-toggle="modal" data-target="#filiarModal" data-plano="Dojô Particular">
                                    Filiar Academia
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dojo de Projeto -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header-plan">
                            <h5 class="card-title">Dojô de Projeto</h5>
                            <h6 class="card-price">R$ 200<span class="period"> / anual</span></h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-0">
                            <ul class="fa-ul">
                                <li><i class="fa-solid fa-check text-success"></i>Condições subsidiadas para projetos sociais sem fins lucrativos</li>
                                <li><i class="fa-solid fa-check text-success"></i>Suporte pedagógico e técnico da diretoria WKKA</li>
                                <li><i class="fa-solid fa-check text-success"></i>Homologação oficial de exames de graduação comunitários</li>
                                <li><i class="fa-solid fa-check text-success"></i>Divulgação institucional no portal</li>
                                <li><i class="fa-solid fa-check text-success"></i>Envio de atletas bolsistas para campeonatos</li>
                            </ul>
                            <div class="p-4 bg-light border-top">
                                <button type="button" class="btn btn-danger btn-block btn-filiar shadow-sm" data-toggle="modal" data-target="#filiarModal" data-plano="Dojô de Projeto">
                                    Filiar Projeto Social
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instituições -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header-plan">
                            <h5 class="card-title">Instituições</h5>
                            <h6 class="card-price">R$ 500<span class="period"> / anual</span></h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-0">
                            <ul class="fa-ul">
                                <li><i class="fa-solid fa-check text-success"></i>Filiação para clubes, ligas, escolas e colégios parceiros</li>
                                <li><i class="fa-solid fa-check text-success"></i>Homologação de grades curriculares de artes marciais</li>
                                <li><i class="fa-solid fa-check text-success"></i>Assessoria técnica completa para eventos institucionais</li>
                                <li><i class="fa-solid fa-check text-success"></i>Isenção de taxas em seminários corporativos</li>
                                <li><i class="fa-solid fa-check text-success"></i>Representação consultiva nas assembleias da associação</li>
                            </ul>
                            <div class="p-4 bg-light border-top">
                                <button type="button" class="btn btn-danger btn-block btn-filiar shadow-sm" data-toggle="modal" data-target="#filiarModal" data-plano="Instituições">
                                    Filiar Instituição
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Filiação com Form Dinâmico -->
<div class="modal fade" id="filiarModal" role="dialog" aria-labelledby="filiarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header bg-dark text-white p-4">
                <h5 class="modal-title font-weight-bold text-uppercase" id="filiarModalLabel">
                    <i class="fa-solid fa-file-signature mr-2 text-danger"></i>Dados para Solicitação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true" class="text-white" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>
            
            <form action="../controllers/filiar_email.php" method="post">
                <input type="hidden" name="plano_selecionado" id="plano_selecionado" value="">
                
                <div class="modal-body p-4 bg-light">
                    <!-- SEÇÃO INDIVIDUAL: ATLETA E PROFESSOR -->
                    <div id="campos-filiados">
                        <h6 class="text-danger font-weight-bold text-uppercase mb-4 border-bottom pb-2">
                            <i class="fa-solid fa-user mr-2"></i>Dados Pessoais (Atleta/Professor)
                        </h6>
                        
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label class="text-secondary small font-weight-bold text-uppercase">Nome Completo *</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="nome" placeholder="Digite seu nome completo" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="text-secondary small font-weight-bold text-uppercase">Data de Nascimento *</label>
                                <input type="date" class="form-control form-control-lg bg-white border-0 shadow-sm" name="data_nascimento" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label class="text-secondary small font-weight-bold text-uppercase">E-mail *</label>
                                <input type="email" class="form-control form-control-lg bg-white border-0 shadow-sm" name="email" placeholder="nome@provedor.com" required>
                            </div>
                            <div class="form-group col-md-5">
                                <label class="text-secondary small font-weight-bold text-uppercase">Telefone / WhatsApp *</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm tel-mask" name="telefone" placeholder="(00) 00000-0000" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label class="text-secondary small font-weight-bold text-uppercase">Dojo de Origem</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="dojo" placeholder="Nome do Dojo/Academia onde treina">
                            </div>
                            <div class="form-group col-md-5">
                                <label class="text-secondary small font-weight-bold text-uppercase">Graduação Atual</label>
                                <select class="form-control form-control-lg bg-white border-0 shadow-sm" name="graduacao">
                                    <option value="Sem graduação">Sem graduação</option>
                                    <option value="faixa colorida 7° Kyu">Faixa Colorida - 7° Kyu</option>
                                    <option value="faixa colorida 6° Kyu">Faixa Colorida - 6° Kyu</option>
                                    <option value="faixa colorida 5° Kyu">Faixa Colorida - 5° Kyu</option>
                                    <option value="faixa colorida 4° Kyu">Faixa Colorida - 4° Kyu</option>
                                    <option value="faixa colorida 3° Kyu">Faixa Colorida - 3° Kyu</option>
                                    <option value="faixa colorida 2° Kyu">Faixa Colorida - 2° Kyu</option>
                                    <option value="faixa colorida 1° Kyu">Faixa Colorida - 1° Kyu</option>
                                    <option value="faixa preta 1° Dan">Faixa Preta - 1° Dan</option>
                                    <option value="faixa preta 2° Dan">Faixa Preta - 2° Dan</option>
                                    <option value="faixa preta 3° Dan">Faixa Preta - 3° Dan</option>
                                    <option value="faixa preta 4° Dan">Faixa Preta - 4° Dan</option>
                                    <option value="faixa preta 5° Dan">Faixa Preta - 5° Dan</option>
                                </select>
                            </div>
                        </div>

                        <h6 class="text-danger font-weight-bold text-uppercase mt-4 mb-3 border-bottom pb-2">
                            <i class="fa-solid fa-location-dot mr-2"></i>Endereço Residencial
                        </h6>

                        <div class="form-group">
                            <label class="text-secondary small font-weight-bold text-uppercase">Endereço Completo</label>
                            <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="endereco" placeholder="Rua, Número, Bairro, Apto">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label class="text-secondary small font-weight-bold text-uppercase">Cidade</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="cidade" placeholder="Ex: Várzea Grande">
                            </div>
                            <div class="form-group col-md-5">
                                <label class="text-secondary small font-weight-bold text-uppercase">Estado</label>
                                <select class="form-control form-control-lg bg-white border-0 shadow-sm" name="estado">
                                    <option value="">Selecione...</option>
                                    <option value="Acre">Acre</option>
                                    <option value="Alagoas">Alagoas</option>
                                    <option value="Amapá">Amapá</option>
                                    <option value="Amazonas">Amazonas</option>
                                    <option value="Bahia">Bahia</option>
                                    <option value="Ceará">Ceará</option>
                                    <option value="Distrito Federal">Distrito Federal</option>
                                    <option value="Espírito Santo">Espírito Santo</option>
                                    <option value="Goiás">Goiás</option>
                                    <option value="Maranhão">Maranhão</option>
                                    <option value="Mato Grosso" selected>Mato Grosso</option>
                                    <option value="Mato Grosso do Sul">Mato Grosso do Sul</option>
                                    <option value="Minas Gerais">Minas Gerais</option>
                                    <option value="Pará">Pará</option>
                                    <option value="Paraíba">Paraíba</option>
                                    <option value="Paraná">Paraná</option>
                                    <option value="Pernambuco">Pernambuco</option>
                                    <option value="Piauí">Piauí</option>
                                    <option value="Rio de Janeiro">Rio de Janeiro</option>
                                    <option value="Rio Grande do Norte">Rio Grande do Norte</option>
                                    <option value="Rio Grande do Sul">Rio Grande do Sul</option>
                                    <option value="Rondônia">Rondônia</option>
                                    <option value="Roraima">Roraima</option>
                                    <option value="Santa Catarina">Santa Catarina</option>
                                    <option value="São Paulo">São Paulo</option>
                                    <option value="Sergipe">Sergipe</option>
                                    <option value="Tocantins">Tocantins</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SEÇÃO ENTIDADES: DOJOS E INSTITUIÇÕES -->
                    <div id="campos-dojos" style="display:none;">
                        <h6 class="text-danger font-weight-bold text-uppercase mb-4 border-bottom pb-2">
                            <i class="fa-solid fa-building-columns mr-2"></i>Dados da Instituição / Dojô
                        </h6>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="text-secondary small font-weight-bold text-uppercase">Razão Social</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="razao_social" placeholder="Razão Social da empresa/associação">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-secondary small font-weight-bold text-uppercase">Nome Fantasia / Dojô *</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="nome_fantasia" placeholder="Nome Fantasia / Nome do Dojo" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="text-secondary small font-weight-bold text-uppercase">CNPJ (se houver)</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm cnpj-mask" name="cnpj" placeholder="00.000.000/0000-00">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="text-secondary small font-weight-bold text-uppercase">Responsável Técnico / Mestre *</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="responsavel" placeholder="Nome do Professor Responsável" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="text-secondary small font-weight-bold text-uppercase">E-mail de Contato *</label>
                                <input type="email" class="form-control form-control-lg bg-white border-0 shadow-sm" name="email" placeholder="contato@dojo.com" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="text-secondary small font-weight-bold text-uppercase">Telefone Fixo</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm tel-mask" name="telefone" placeholder="(00) 0000-0000">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="text-secondary small font-weight-bold text-uppercase">Celular / WhatsApp *</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm tel-mask" name="celular" placeholder="(00) 00000-0000" required>
                            </div>
                        </div>

                        <h6 class="text-danger font-weight-bold text-uppercase mt-4 mb-3 border-bottom pb-2">
                            <i class="fa-solid fa-map-location-dot mr-2"></i>Endereço da Sede
                        </h6>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="text-secondary small font-weight-bold text-uppercase">CEP</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm cep-mask" name="cep" placeholder="00000-000">
                            </div>
                            <div class="form-group col-md-9">
                                <label class="text-secondary small font-weight-bold text-uppercase">Endereço Completo</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="endereco" placeholder="Rua, Número, Bairro, Sala">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label class="text-secondary small font-weight-bold text-uppercase">Cidade</label>
                                <input type="text" class="form-control form-control-lg bg-white border-0 shadow-sm" name="cidade" placeholder="Ex: Várzea Grande">
                            </div>
                            <div class="form-group col-md-5">
                                <label class="text-secondary small font-weight-bold text-uppercase">Estado</label>
                                <select class="form-control form-control-lg bg-white border-0 shadow-sm" name="estado">
                                    <option value="">Selecione...</option>
                                    <option value="Acre">Acre</option>
                                    <option value="Alagoas">Alagoas</option>
                                    <option value="Amapá">Amapá</option>
                                    <option value="Amazonas">Amazonas</option>
                                    <option value="Bahia">Bahia</option>
                                    <option value="Ceará">Ceará</option>
                                    <option value="Distrito Federal">Distrito Federal</option>
                                    <option value="Espírito Santo">Espírito Santo</option>
                                    <option value="Goiás">Goiás</option>
                                    <option value="Maranhão">Maranhão</option>
                                    <option value="Mato Grosso" selected>Mato Grosso</option>
                                    <option value="Mato Grosso do Sul">Mato Grosso do Sul</option>
                                    <option value="Minas Gerais">Minas Gerais</option>
                                    <option value="Pará">Pará</option>
                                    <option value="Paraíba">Paraíba</option>
                                    <option value="Paraná">Paraná</option>
                                    <option value="Pernambuco">Pernambuco</option>
                                    <option value="Piauí">Piauí</option>
                                    <option value="Rio de Janeiro">Rio de Janeiro</option>
                                    <option value="Rio Grande do Norte">Rio Grande do Norte</option>
                                    <option value="Rio Grande do Sul">Rio Grande do Sul</option>
                                    <option value="Rondônia">Rondônia</option>
                                    <option value="Roraima">Roraima</option>
                                    <option value="Santa Catarina">Santa Catarina</option>
                                    <option value="São Paulo">São Paulo</option>
                                    <option value="Sergipe">Sergipe</option>
                                    <option value="Tocantins">Tocantins</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-white p-3 d-flex justify-content-between border-top">
                    <button type="button" class="btn btn-light font-weight-bold rounded-pill px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger font-weight-bold rounded-pill px-5 py-2 shadow-sm">
                        <i class="fa-solid fa-paper-plane mr-2"></i>Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Interceptar abertura do modal para mudar os campos dinamicamente
    $('#filiarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var plano = button.data('plano'); 
        var modal = $(this);
        
        modal.find('#plano_selecionado').val(plano);
        modal.find('.modal-title').html('<i class="fa-solid fa-file-signature mr-2 text-danger"></i>Filiação: ' + plano);
        
        toggleFormFields(plano);
    });

    function toggleFormFields(plano) {
        var filiadosSec = document.getElementById('campos-filiados');
        var dojosSec = document.getElementById('campos-dojos');
        
        var filiadosInputs = filiadosSec.querySelectorAll('input, select, textarea');
        var dojosInputs = dojosSec.querySelectorAll('input, select, textarea');
        
        if (plano === 'Atleta' || plano === 'Professor') {
            filiadosSec.style.display = 'block';
            dojosSec.style.display = 'none';
            
            // Habilitar campos do filiado (serão enviados e validados)
            filiadosInputs.forEach(function(el) {
                el.removeAttribute('disabled');
            });
            // Desabilitar campos do dojo (não serão enviados)
            dojosInputs.forEach(function(el) {
                el.setAttribute('disabled', 'true');
            });
        } else {
            filiadosSec.style.display = 'none';
            dojosSec.style.display = 'block';
            
            // Desabilitar campos do filiado
            filiadosInputs.forEach(function(el) {
                el.setAttribute('disabled', 'true');
            });
            // Habilitar campos do dojo
            dojosInputs.forEach(function(el) {
                el.removeAttribute('disabled');
            });
        }
    }

    // JS Máscara de Telefone simples
    function formatarTelefone(input) {
        var value = input.value.replace(/\D/g, "");
        if (value.length > 11) value = value.substring(0, 11);
        
        if (value.length > 10) {
            input.value = "(" + value.substring(0, 2) + ") " + value.substring(2, 7) + "-" + value.substring(7, 11);
        } else if (value.length > 5) {
            input.value = "(" + value.substring(0, 2) + ") " + value.substring(2, 6) + "-" + value.substring(6, 10);
        } else if (value.length > 2) {
            input.value = "(" + value.substring(0, 2) + ") " + value.substring(2);
        } else if (value.length > 0) {
            input.value = "(" + value;
        }
    }
    
    document.querySelectorAll(".tel-mask").forEach(function(el) {
        el.addEventListener("input", function() {
            formatarTelefone(this);
        });
    });

    // JS Máscara de CNPJ
    function formatarCNPJ(input) {
        var value = input.value.replace(/\D/g, "");
        if (value.length > 14) value = value.substring(0, 14);
        
        if (value.length > 12) {
            input.value = value.substring(0, 2) + "." + value.substring(2, 5) + "." + value.substring(5, 8) + "/" + value.substring(8, 12) + "-" + value.substring(12, 14);
        } else if (value.length > 8) {
            input.value = value.substring(0, 2) + "." + value.substring(2, 5) + "." + value.substring(5, 8) + "/" + value.substring(8);
        } else if (value.length > 5) {
            input.value = value.substring(0, 2) + "." + value.substring(2, 5) + "." + value.substring(5);
        } else if (value.length > 2) {
            input.value = value.substring(0, 2) + "." + value.substring(2);
        }
    }
    
    document.querySelectorAll(".cnpj-mask").forEach(function(el) {
        el.addEventListener("input", function() {
            formatarCNPJ(this);
        });
    });

    // JS Máscara de CEP
    function formatarCEP(input) {
        var value = input.value.replace(/\D/g, "");
        if (value.length > 8) value = value.substring(0, 8);
        
        if (value.length > 5) {
            input.value = value.substring(0, 5) + "-" + value.substring(5);
        }
    }
    
    document.querySelectorAll(".cep-mask").forEach(function(el) {
        el.addEventListener("input", function() {
            formatarCEP(this);
        });
    });
});
</script>

<?php 
include "rodape.php";
?>