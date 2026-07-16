<?php
// views/gerenciamento_dojo/ajuda.php
include_once __DIR__ . "/../admin/menu.php";

// Verificação de autenticação
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    echo "<script>alert('Acesso negado.'); window.location.href = '../login.php';</script>";
    exit();
}
?>

<div class="container py-4">
    <!-- Cabeçalho com botão de Voltar -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">Central de Ajuda & Manual do Usuário</h2>
            <p class="text-muted mb-0"><i class="fa-solid fa-circle-question mr-2"></i>Aprenda a operar o Gerenciador de Dojô Kenshydokan</p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary font-weight-bold rounded-pill px-4 py-2 shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Voltar ao Painel
        </a>
    </div>

    <!-- Introdução Geral -->
    <div class="card border-0 shadow-sm rounded-lg mb-4 bg-white">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2.5rem;">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                </div>
                <div class="col-md-10">
                    <h5 class="font-weight-bold text-danger">O que é este módulo?</h5>
                    <p class="text-muted mb-0 leading-relaxed">
                        Este módulo foi desenhado para facilitar a administração diária do dojo. Ele permite o controle de mensalidades de alunos, configurações individuais de cobrança e o fluxo de caixa geral (Livro Caixa) de forma centralizada e sem a necessidade de planilhas externas. Tudo está integrado com o banco de dados de filiados do site.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Abas de Ajuda para cada seção -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-lg bg-white p-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="gap: 8px;">
                    <a class="nav-link active rounded-pill font-weight-bold text-left px-3 py-2" id="v-pills-config-tab" data-toggle="pill" href="#v-pills-config" role="tab" aria-controls="v-pills-config" aria-selected="true">
                        <i class="fa-solid fa-user-gear mr-2"></i> 1. Configurar Alunos
                    </a>
                    <a class="nav-link rounded-pill font-weight-bold text-left px-3 py-2" id="v-pills-mensalidades-tab" data-toggle="pill" href="#v-pills-mensalidades" role="tab" aria-controls="v-pills-mensalidades" aria-selected="false">
                        <i class="fa-solid fa-sack-dollar mr-2"></i> 2. Mensalidades
                    </a>
                    <a class="nav-link rounded-pill font-weight-bold text-left px-3 py-2" id="v-pills-caixa-tab" data-toggle="pill" href="#v-pills-caixa" role="tab" aria-controls="v-pills-caixa" aria-selected="false">
                        <i class="fa-solid fa-cash-register mr-2"></i> 3. Livro Caixa
                    </a>
                    <a class="nav-link rounded-pill font-weight-bold text-left px-3 py-2" id="v-pills-dashboard-tab" data-toggle="pill" href="#v-pills-dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="false">
                        <i class="fa-solid fa-gauge mr-2"></i> 4. Painel Geral
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="tab-content bg-white p-4 rounded-lg shadow-sm border" id="v-pills-tabContent">
                
                <!-- Aba 1: Configurar Alunos -->
                <div class="tab-pane fade show active" id="v-pills-config" role="tabpanel" aria-labelledby="v-pills-config-tab">
                    <h5 class="font-weight-bold text-danger mb-3"><i class="fa-solid fa-user-gear mr-2"></i>Configuração de Alunos</h5>
                    
                    <p class="text-muted leading-relaxed">
                        A aba <strong>Config. Alunos</strong> serve para definir e personalizar os dados de cobrança de cada praticante do dojo.
                    </p>

                    <div class="alert alert-info border-0 shadow-sm rounded-lg mb-3">
                        <i class="fa-solid fa-circle-info mr-2"></i><strong>Importante:</strong> Os alunos exibidos nesta lista são puxados automaticamente da tabela de filiados do site. Apenas filiados com <strong>Confirmação = "Sim"</strong> aparecem aqui.
                    </div>

                    <h6 class="font-weight-bold text-dark mt-4 mb-2">Passo a Passo para Configurar:</h6>
                    <ol class="text-muted leading-relaxed pl-3">
                        <li class="mb-2">Acesse a aba <strong>Config. Alunos</strong> no painel de navegação.</li>
                        <li class="mb-2">Localize o aluno desejado na tabela (você pode usar a barra de pesquisa do DataTable para filtrar por nome, graduação ou e-mail).</li>
                        <li class="mb-2">Clique no botão azul de engrenagem (<i class="fa-solid fa-gear text-primary"></i>) na coluna "Ações" do respectivo aluno.</li>
                        <li class="mb-2">No modal que se abre, preencha:
                            <ul class="pl-3 mt-1">
                                <li><strong>Valor da Mensalidade (R$)</strong>: O valor mensal cobrado individualmente do aluno.</li>
                                <li><strong>Dia de Vencimento</strong>: O dia preferencial de cobrança (entre 1 e 28).</li>
                                <li><strong>Situação do Aluno</strong>: Escolha entre <em>Adimplente</em> (ativo e em dia), <em>Inadimplente</em> (com débito) ou <em>Pausado</em> (com treinos suspensos temporariamente).</li>
                            </ul>
                        </li>
                        <li class="mb-2">Clique em <strong>Salvar Config.</strong> para aplicar as alterações.</li>
                    </ol>
                </div>

                <!-- Aba 2: Mensalidades -->
                <div class="tab-pane fade" id="v-pills-mensalidades" role="tabpanel" aria-labelledby="v-pills-mensalidades-tab">
                    <h5 class="font-weight-bold text-danger mb-3"><i class="fa-solid fa-sack-dollar mr-2"></i>Controle de Mensalidades</h5>
                    
                    <p class="text-muted leading-relaxed">
                        Nesta seção você gera as mensalidades em lote para um mês específico e registra os recebimentos de maneira rápida.
                    </p>

                    <h6 class="font-weight-bold text-dark mt-4 mb-2"><i class="fa-solid fa-file-invoice-dollar text-danger mr-2"></i>Como Gerar Lançamentos Mensais?</h6>
                    <ul class="text-muted leading-relaxed pl-3 list-unstyled">
                        <li class="mb-3">
                            <span class="badge badge-danger text-uppercase px-2 py-1 mr-1">Passo 1</span> Selecione o mês/ano de referência desejado no campo <strong>"Período"</strong> localizado no canto superior direito do painel.
                        </li>
                        <li class="mb-3">
                            <span class="badge badge-danger text-uppercase px-2 py-1 mr-1">Passo 2</span> Vá até a aba <strong>Mensalidades</strong> e clique no botão vermelho <strong>"Gerar Mensalidades do Mês"</strong>.
                        </li>
                        <li class="mb-3">
                            <span class="badge badge-dark text-uppercase px-2 py-1 mr-1">Nota</span> O sistema irá gerar as cobranças no valor configurado de cada aluno ativo (Adimplente ou Inadimplente). Alunos pausados ou que já possuem faturas lançadas naquele período serão pulados automaticamente para evitar duplicidade.
                        </li>
                    </ul>

                    <hr class="my-4" style="opacity: 0.15;">

                    <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-cash-register text-success mr-2"></i>Como dar Baixa de Recebimento?</h6>
                    <ol class="text-muted leading-relaxed pl-3">
                        <li class="mb-2">Na tabela de mensalidades, localize a cobrança pendente (com badge amarelo <span class="badge badge-warning text-uppercase" style="font-size: 0.65rem;">Pendente</span> ou vermelho <span class="badge badge-danger text-uppercase" style="font-size: 0.65rem;">Atrasado</span>).</li>
                        <li class="mb-2">Clique no botão verde de caixa (<i class="fa-solid fa-cash-register text-success"></i>) na coluna "Ações".</li>
                        <li class="mb-2">No modal, confira a data do pagamento (por padrão vem preenchida com a data de hoje, mas pode ser alterada) e clique em <strong>Confirmar Recebimento</strong>.</li>
                        <li class="mb-2"><strong>O que acontece a seguir:</strong>
                            <ul class="pl-3 mt-1">
                                <li>A fatura muda para o status <span class="badge badge-success text-uppercase" style="font-size: 0.65rem;">Pago</span>.</li>
                                <li>É gerado um lançamento de <strong>Entrada</strong> automático no Livro Caixa geral com a descrição <em>"Mensalidade - [Nome do Aluno] (Ref: [Mês])"</em>.</li>
                                <li>O sistema atualiza a inadimplência e volta o aluno para o status de Adimplente caso ele não tenha outras pendências anteriores.</li>
                            </ul>
                        </li>
                    </ol>
                </div>

                <!-- Aba 3: Livro Caixa -->
                <div class="tab-pane fade" id="v-pills-caixa" role="tabpanel" aria-labelledby="v-pills-caixa-tab">
                    <h5 class="font-weight-bold text-danger mb-3"><i class="fa-solid fa-cash-register mr-2"></i>Livro Caixa & Fluxo Financeiro</h5>
                    
                    <p class="text-muted leading-relaxed">
                        O <strong>Livro Caixa</strong> gerencia a saúde financeira direta do dojo, listando todas as despesas operacionais e receitas extras.
                    </p>

                    <h6 class="font-weight-bold text-dark mt-4 mb-2"><i class="fa-solid fa-plus-circle text-danger mr-2"></i>Adicionar Lançamento Manual:</h6>
                    <ol class="text-muted leading-relaxed pl-3">
                        <li class="mb-2">Clique no botão <strong>"Nova Movimentação"</strong> no topo da aba Livro Caixa.</li>
                        <li class="mb-2">No formulário, informe a <strong>Descrição</strong> do lançamento (Ex: "Energia do Dojo", "Kimono tamanho M", etc.).</li>
                        <li class="mb-2">Defina o <strong>Tipo</strong> como <em>Receita (Entrada)</em> ou <em>Despesa (Saída)</em>.</li>
                        <li class="mb-2">Insira o <strong>Valor</strong> em Reais e a <strong>Data</strong> em que o fluxo financeiro ocorreu.</li>
                        <li class="mb-2">Selecione uma <strong>Categoria</strong> para classificar o lançamento.</li>
                        <li class="mb-2">Clique em <strong>Confirmar Lançamento</strong>.</li>
                    </ol>

                    <div class="alert alert-warning border-0 shadow-sm rounded-lg mt-3 small">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> <strong>Exclusão de Registros:</strong> Lançamentos avulsos ou de mensalidades podem ser excluídos clicando na lixeira vermelha (<i class="fa-solid fa-trash text-danger"></i>). Atenção: A exclusão de um registro de mensalidade no caixa geral remove o valor do saldo, mas não altera a baixa realizada na aba de Mensalidades.
                    </div>
                </div>

                <!-- Aba 4: Painel Geral -->
                <div class="tab-pane fade" id="v-pills-dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard-tab">
                    <h5 class="font-weight-bold text-danger mb-3"><i class="fa-solid fa-gauge mr-2"></i>Painel Geral (Dashboard)</h5>
                    
                    <p class="text-muted leading-relaxed">
                        A aba <strong>Painel Geral</strong> reúne todos os indicadores consolidados relativos ao <strong>mês de referência</strong> selecionado no filtro de período global.
                    </p>

                    <h6 class="font-weight-bold text-dark mt-4 mb-2">Compreendendo os Indicadores:</h6>
                    <ul class="text-muted leading-relaxed pl-3">
                        <li class="mb-2"><strong>Receitas (Entradas)</strong>: Soma de todas as receitas geradas no mês (incluindo baixas de mensalidades e entradas manuais).</li>
                        <li class="mb-2"><strong>Despesas (Saídas)</strong>: Soma de todos os gastos lançados no mês.</li>
                        <li class="mb-2"><strong>Saldo Líquido</strong>: O faturamento real do dojo no mês (Receitas menos Despesas). Fica azul em caso positivo e vermelho caso as despesas superem as entradas.</li>
                        <li class="mb-2"><strong>Alunos Adimplentes, Inadimplentes e Pausados</strong>: Métricas contínuas da base geral de alunos ativos no sistema para controle rápido de presença e suspensões.</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . "/../admin/rodape.php";
?>
