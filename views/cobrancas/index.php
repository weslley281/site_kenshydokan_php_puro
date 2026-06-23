<?php
include_once __DIR__ . "/../admin/menu.php";

// Verificação de autenticação
if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
    echo "<script>alert('Acesso negado.'); window.location.href = '../login.php';</script>";
    exit();
}

// Carregar filiados e dojos para os seletores
require_once __DIR__ . '/../../db/conexao.php';
$db = new Database();
$conn = $db->getConnection();

$filiados = [];
$res_filiados = $conn->query("SELECT id_filiado, nome, email FROM filiados ORDER BY nome ASC");
if ($res_filiados) {
    while ($row = $res_filiados->fetch_assoc()) {
        $filiados[] = $row;
    }
}

$dojos = [];
$res_dojos = $conn->query("SELECT id, nome_fantasia, email FROM dojos ORDER BY nome_fantasia ASC");
if ($res_dojos) {
    while ($row = $res_dojos->fetch_assoc()) {
        $dojos[] = $row;
    }
}
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">Gerenciar Cobranças via Stripe</h2>
            <p class="text-muted mb-0"><i class="fa-solid fa-credit-card mr-2"></i>Geração de links de pagamento e envio direto por e-mail</p>
        </div>
        <a href="../perfil/perfil.php" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-2"></i> Voltar ao Painel
        </a>
    </div>

    <!-- Mensagens de Feedback -->
    <?php if (isset($_SESSION["msg_sucesso"])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-lg mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check mr-3 text-success" style="font-size: 1.8rem;"></i>
                <div>
                    <div><?php echo $_SESSION["msg_sucesso"]; unset($_SESSION["msg_sucesso"]); ?></div>
                    
                    <?php if (isset($_SESSION["stripe_link"])): ?>
                        <div class="mt-3 p-3 bg-white rounded border border-success shadow-sm">
                            <h6 class="font-weight-bold text-dark mb-2"><i class="fa-solid fa-link text-success mr-2"></i>Link de Pagamento Gerado:</h6>
                            <div class="input-group">
                                <input type="text" id="stripePaymentLink" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($_SESSION["stripe_link"]); ?>" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-success font-weight-bold" type="button" onclick="copiarLinkStripe()">
                                        <i class="fa-solid fa-copy mr-1"></i> Copiar
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted mt-1">Você pode copiar este link e enviá-lo diretamente via WhatsApp ou Redes Sociais.</small>
                        </div>
                        <?php unset($_SESSION["stripe_link"]); ?>
                    <?php endif; ?>
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

    <div class="row">
        <!-- Form de Cobrança -->
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4">
                    <h5 class="text-danger font-weight-bold mb-4 border-bottom pb-2">
                        <i class="fa-solid fa-file-invoice-dollar mr-2"></i>Emitir Nova Fatura
                    </h5>
                    
                    <form action="../../controllers/cobrancaController.php" method="post">
                        <!-- Tipo de Destinatário -->
                        <div class="form-group mb-4">
                            <label class="text-secondary small font-weight-bold text-uppercase d-block mb-2">Tipo de Destinatário *</label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-danger font-weight-bold active w-50 py-2">
                                    <input type="radio" name="tipo_destinatario" id="dest_filiado" value="filiado" checked onchange="alternarDestinatario('filiado')"> 
                                    <i class="fa-solid fa-user mr-1"></i> Filiado / Aluno
                                </label>
                                <label class="btn btn-outline-danger font-weight-bold w-50 py-2">
                                    <input type="radio" name="tipo_destinatario" id="dest_dojo" value="dojo" onchange="alternarDestinatario('dojo')"> 
                                    <i class="fa-solid fa-store mr-1"></i> Dojô / Academia
                                </label>
                            </div>
                        </div>

                        <!-- Seleção do Filiado -->
                        <div class="form-group mb-3" id="grupo-filiados">
                            <label for="id_filiado" class="text-secondary small font-weight-bold text-uppercase">Selecionar Filiado *</label>
                            <select class="form-control form-control-lg bg-light border-0 shadow-sm js-select2" id="id_filiado" name="id_filiado" required>
                                <option value="">Procure pelo nome do filiado...</option>
                                <?php foreach ($filiados as $f): ?>
                                    <option value="<?php echo $f['id_filiado']; ?>" data-email="<?php echo htmlspecialchars($f['email']); ?>">
                                        <?php echo htmlspecialchars($f['nome']) . ' (' . htmlspecialchars($f['email']) . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Seleção do Dojô -->
                        <div class="form-group mb-3" id="grupo-dojos" style="display: none;">
                            <label for="id_dojo" class="text-secondary small font-weight-bold text-uppercase">Selecionar Dojô *</label>
                            <select class="form-control form-control-lg bg-light border-0 shadow-sm js-select2" id="id_dojo" name="id_dojo">
                                <option value="">Procure pelo nome do dojô...</option>
                                <?php foreach ($dojos as $d): ?>
                                    <option value="<?php echo $d['id']; ?>" data-email="<?php echo htmlspecialchars($d['email']); ?>">
                                        <?php echo htmlspecialchars($d['nome_fantasia']) . ' (' . htmlspecialchars($d['email']) . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- E-mail de Destino (Automático) -->
                        <div class="form-group mb-3">
                            <label for="email_destino" class="text-secondary small font-weight-bold text-uppercase">E-mail do Destinatário *</label>
                            <input type="email" class="form-control form-control-lg bg-white border border-danger shadow-sm font-weight-bold text-dark" id="email_destino" name="email_destino" placeholder="O e-mail será preenchido automaticamente ao selecionar" readonly required>
                            <small class="form-text text-muted">A cobrança e o link do Stripe serão enviados diretamente para este endereço.</small>
                        </div>

                        <hr class="my-4">

                        <h5 class="text-danger font-weight-bold mb-3"><i class="fa-solid fa-receipt mr-2"></i>Detalhes da Fatura</h5>

                        <!-- Frequência / Tipo de Plano -->
                        <div class="form-row">
                            <div class="form-group col-md-6 mb-3">
                                <label for="frequencia" class="text-secondary small font-weight-bold text-uppercase">Frequência da Cobrança *</label>
                                <select class="form-control form-control-lg bg-light border-0 shadow-sm font-weight-bold" id="frequencia" name="frequencia" required onchange="atualizarValoresPadrao(this.value)">
                                    <option value="mensal">Mensal (R$ 100,00)</option>
                                    <option value="semestral">Semestral (R$ 600,00)</option>
                                    <option value="anual">Anual (R$ 1.000,00)</option>
                                    <option value="personalizado" selected>Personalizado (Livre)</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label for="valor" class="text-secondary small font-weight-bold text-uppercase">Valor da Cobrança (R$) *</label>
                                <input type="number" step="0.01" min="1" class="form-control form-control-lg bg-light border-0 shadow-sm font-weight-bold text-danger" id="valor" name="valor" placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- Descrição da Fatura -->
                        <div class="form-group mb-4">
                            <label for="descricao" class="text-secondary small font-weight-bold text-uppercase">Descrição da Cobrança *</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" id="descricao" name="descricao" placeholder="Ex: Mensalidade Julho/2026 - Dojô Central" required>
                            <small class="form-text text-muted">Esta descrição aparecerá na tela de pagamento segura do Stripe Checkout para o destinatário.</small>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg btn-block font-weight-bold rounded-pill shadow px-5 py-3">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Gerar Link e Enviar Cobrança por E-mail
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function alternarDestinatario(tipo) {
    var grpFiliados = document.getElementById('grupo-filiados');
    var grpDojos = document.getElementById('grupo-dojos');
    
    var selFiliado = document.getElementById('id_filiado');
    var selDojo = document.getElementById('id_dojo');
    
    var emailInput = document.getElementById('email_destino');
    emailInput.value = ''; // Limpar email

    if (tipo === 'filiado') {
        grpFiliados.style.display = 'block';
        grpDojos.style.display = 'none';
        
        selFiliado.setAttribute('required', 'true');
        selDojo.removeAttribute('required');
        
        // Disparar preenchimento com valor atual do select se houver
        atualizarEmailDeSelect(selFiliado);
    } else {
        grpFiliados.style.display = 'none';
        grpDojos.style.display = 'block';
        
        selFiliado.removeAttribute('required');
        selDojo.setAttribute('required', 'true');
        
        // Disparar preenchimento com valor atual do select se houver
        atualizarEmailDeSelect(selDojo);
    }
}

function atualizarEmailDeSelect(selectElement) {
    var emailInput = document.getElementById('email_destino');
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    if (selectedOption && selectedOption.value !== "") {
        var email = selectedOption.getAttribute('data-email');
        emailInput.value = email;
    } else {
        emailInput.value = '';
    }
}

function atualizarValoresPadrao(frequencia) {
    var valInput = document.getElementById('valor');
    var descInput = document.getElementById('descricao');
    
    var tipoDest = document.querySelector('input[name="tipo_destinatario"]:checked').value;
    var nomeDest = "";
    
    if (tipoDest === 'filiado') {
        var sel = document.getElementById('id_filiado');
        nomeDest = sel.selectedIndex > 0 ? sel.options[sel.selectedIndex].text.split(' (')[0] : "Filiado";
    } else {
        var sel = document.getElementById('id_dojo');
        nomeDest = sel.selectedIndex > 0 ? sel.options[sel.selectedIndex].text.split(' (')[0] : "Dojo";
    }

    if (frequencia === 'mensal') {
        valInput.value = '100.00';
        descInput.value = 'Mensalidade Oficial - ' + nomeDest;
    } else if (frequencia === 'semestral') {
        valInput.value = '600.00';
        descInput.value = 'Taxa Semestral de Treinos - ' + nomeDest;
    } else if (frequencia === 'anual') {
        valInput.value = '1000.00';
        descInput.value = 'Anuidade Oficial de Filiação - ' + nomeDest;
    } else {
        valInput.value = '';
        descInput.value = '';
    }
}

function copiarLinkStripe() {
    var copyText = document.getElementById("stripePaymentLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // Para mobile
    navigator.clipboard.writeText(copyText.value).then(function() {
        alert("Link de pagamento copiado com sucesso!");
    }, function(err) {
        alert("Erro ao copiar o link. Por favor, copie manualmente.");
    });
}

document.addEventListener("DOMContentLoaded", function() {
    // Inicializar Select2 nos seletores
    if ($.fn.select2) {
        $('.js-select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            language: 'pt-BR'
        });
        
        // Ouvir alterações via Select2
        $('#id_filiado').on('select2:select', function (e) {
            atualizarEmailDeSelect(this);
            atualizarValoresPadrao(document.getElementById('frequencia').value);
        });
        
        $('#id_dojo').on('select2:select', function (e) {
            atualizarEmailDeSelect(this);
            atualizarValoresPadrao(document.getElementById('frequencia').value);
        });
    } else {
        // Fallback se select2 não estiver disponível
        document.getElementById('id_filiado').addEventListener('change', function() {
            atualizarEmailDeSelect(this);
            atualizarValoresPadrao(document.getElementById('frequencia').value);
        });
        document.getElementById('id_dojo').addEventListener('change', function() {
            atualizarEmailDeSelect(this);
            atualizarValoresPadrao(document.getElementById('frequencia').value);
        });
    }
});
</script>

<?php
include_once __DIR__ . "/../admin/rodape.php";
?>
