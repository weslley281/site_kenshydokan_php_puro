<?php
// views/dashboard_aluno.php
session_start();

// Garante que apenas usuários logados com nível kohai ou sempai acessem (sensei é redirecionado ao painel admin)
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['nivel'] === 'sensei') {
    header("Location: admin/index.php");
    exit();
}

include_once "../db/conexao.php";
include_once "../models/filiadoModel.php";
include_once "../models/listaPresencaModel.php";
include_once "../models/exameGraduacaoModel.php";
include_once "../models/dojoMensalidadeModel.php";
include_once "../models/usuarioModel.php";
include_once "../models/imagemModel.php";
include_once "../models/dojoAlunoConfigModel.php";
include_once "../models/dojoRecorrenciaModel.php";

$id_usuario = $_SESSION['id_usuario'];
$id_filiado = $_SESSION['id_fil'];

$filiado = null;
$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

$caminho_foto = "../img/sem_foto.png";
if ($usuario && !empty($usuario["id_imagem"])) {
    $imagem = Imagem::procura_imagem($usuario["id_imagem"]);
    if ($imagem && !empty($imagem["caminho"])) {
        $caminho_foto = $imagem["caminho"];
    }
}
$frequencia = [];
$exames = [];
$mensalidades = [];

if ($id_filiado > 0) {
    $filiadoModel = new FiliadoModel();
    $filiado = $filiadoModel->buscarPorId($id_filiado);

    $presencaModel = new ListaPresencaModel();
    $frequencia = $presencaModel->buscarFrequenciaPorFiliado($id_filiado);

    $exameModel = new ExameGraduacaoModel();
    $exames = $exameModel->listarPorFiliado($id_filiado);

    $mensalidadeModel = new DojoMensalidadeModel();
    $mensalidades = $mensalidadeModel->buscarMensalidadesPorFiliado($id_filiado);
    
    $alunoConfigModel = new DojoAlunoConfigModel();
    $config_aluno = $alunoConfigModel->buscarPorFiliado($id_filiado);

    $recorrenciaModel = new DojoRecorrenciaModel();
    $recorrencia = $recorrenciaModel->buscarAtivaOuPendentePorFiliado($id_filiado);
} else {
    // Caso o usuário não esteja vinculado a nenhum filiado
    // Mostrar página de aviso amigável
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Painel do Aluno - Gerenciador de Dojô</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/e880bf5077.js" crossorigin="anonymous"></script>
    <!-- Google Font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            color: #333333;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #1f1f1f 0%, #111111 100%);
            border-bottom: 3px solid #dc3545;
        }
        .card-custom {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            background-color: #ffffff;
            margin-bottom: 24px;
        }
        .card-header-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 24px;
        }
        .badge-pill-custom {
            font-size: 0.8rem;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 50px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #dc3545;
            object-fit: cover;
        }
        .table th {
            border-top: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
        <div class="container">
            <span class="navbar-brand font-weight-bold"><i class="fa-solid fa-graduation-cap mr-2"></i> Painel do Aluno</span>
            <div class="navbar-nav ml-auto align-items-center">
                <span class="text-white-50 mr-3 small">Olá, <strong><?php echo htmlspecialchars($_SESSION['nome']); ?></strong> (<?php echo htmlspecialchars(ucfirst($_SESSION['nivel'])); ?>)</span>
                <a href="../controllers/sair.php" class="btn btn-outline-light btn-sm rounded-pill px-3 font-weight-bold"><i class="fa-solid fa-sign-out-alt mr-1"></i> Sair</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <?php if ($id_filiado <= 0 || !$filiado): ?>
            <div class="card card-custom p-5 text-center">
                <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-3"></i>
                <h4 class="font-weight-bold text-dark">Cadastro não vinculado</h4>
                <p class="text-muted mb-0">Esta conta de usuário ainda não está vinculada a nenhuma ficha de filiado/aluno. Entre em contato com seu Sensei para realizar a vinculação.</p>
            </div>
        <?php else: ?>
            <div class="row">
                 <!-- Coluna Esquerda: Perfil e Mensalidades -->
                <div class="col-lg-4">
                    <!-- Débito Automático Recorrente -->
                    <?php if (isset($recorrencia) && $recorrencia): 
                        $rec_status = $recorrencia['status'] ?? '';
                    ?>
                        <?php if ($rec_status === 'pendente'): ?>
                            <div class="card card-custom border border-warning shadow-sm mb-4">
                                <div class="card-header bg-warning text-white p-3">
                                    <h6 class="font-weight-bold mb-0"><i class="fa-solid fa-arrows-rotate mr-2"></i>Débito Automático Proposto</h6>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-secondary small mb-3">Seu Sensei propôs uma assinatura mensal recorrente no valor de <strong>R$ <?php echo number_format($recorrencia['valor'], 2, ',', '.'); ?></strong> com vencimento todo dia <strong><?php echo $config_aluno['dia_vencimento'] ?? 10; ?></strong>.</p>
                                    <button type="button" class="btn btn-sm btn-warning font-weight-bold rounded-pill w-100 shadow-sm text-dark" data-toggle="modal" data-target="#modalAceiteRecorrencia">
                                        <i class="fa-solid fa-file-signature mr-1"></i> Visualizar e Ativar
                                    </button>
                                </div>
                            </div>
                        <?php elseif ($rec_status === 'ativo'): ?>
                            <div class="card card-custom border border-success shadow-sm mb-4">
                                <div class="card-header bg-success text-white p-3">
                                    <h6 class="font-weight-bold mb-0"><i class="fa-solid fa-circle-check mr-2"></i>Débito Automático Ativo</h6>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-secondary small mb-2">Sua mensalidade de <strong>R$ <?php echo number_format($recorrencia['valor'], 2, ',', '.'); ?></strong> está configurada para débito recorrente automático no cartão de crédito via Stripe.</p>
                                    <p class="text-muted small mb-0" style="font-size: 0.75rem;"><i class="fa-solid fa-calendar-check mr-1 text-success"></i>Ativo desde: <?php echo date("d/m/Y H:i", strtotime($recorrencia['data_aceite'])); ?></p>
                                    <form action="../controllers/alunoController.php" method="post" onsubmit="return confirm('Deseja realmente cancelar seu débito automático recorrente? O cancelamento será agendado para o final do período vigente e você não perderá dias já pagos.');" class="mt-2">
                                        <input type="hidden" name="tipo" value="cancelar_recorrencia_stripe">
                                        <input type="hidden" name="subscription_id" value="<?php echo htmlspecialchars($recorrencia['stripe_subscription_id'] ?? ''); ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill w-100 mt-2">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Cancelar Débito
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php elseif ($rec_status === 'cancelando'): ?>
                            <div class="card card-custom border border-info shadow-sm mb-4">
                                <div class="card-header bg-info text-white p-3">
                                    <h6 class="font-weight-bold mb-0"><i class="fa-solid fa-hourglass-end mr-2"></i>Cancelamento Agendado</h6>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-secondary small mb-2">Seu débito automático de <strong>R$ <?php echo number_format($recorrencia['valor'], 2, ',', '.'); ?></strong> foi cancelado e não será renovado no próximo ciclo.</p>
                                    <p class="text-secondary small mb-0"><i class="fa-solid fa-circle-info mr-1 text-info"></i>Sua mensalidade continua ativa e válida até o final do período vigente atual.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Ficha de Cadastro -->
                    <div class="card card-custom">
                        <div class="card-body text-center p-4">
                            <img src="<?php echo htmlspecialchars($caminho_foto); ?>" alt="Avatar" class="profile-avatar mb-3 shadow-sm">
                            <h4 class="font-weight-bold text-dark mb-1 text-capitalize"><?php echo htmlspecialchars($filiado['nome']); ?></h4>
                            <span class="badge badge-pill-custom badge-danger text-uppercase mb-3"><?php echo htmlspecialchars($_SESSION['nivel']); ?></span>
                            
                            <hr class="my-3">
                            
                            <div class="text-left">
                                <p class="mb-2"><i class="fa-solid fa-barcode text-muted mr-2" style="width: 20px;"></i> <span class="text-secondary small font-weight-bold">Matrícula:</span> <strong class="text-dark">#<?php echo htmlspecialchars($filiado['codigo'] ?? $id_filiado); ?></strong></p>
                                <p class="mb-2"><i class="fa-solid fa-store text-muted mr-2" style="width: 20px;"></i> <span class="text-secondary small font-weight-bold">Dojô:</span> <span class="text-dark text-capitalize"><?php echo htmlspecialchars($filiado['dojo']); ?></span></p>
                                <p class="mb-2"><i class="fa-solid fa-phone text-muted mr-2" style="width: 20px;"></i> <span class="text-secondary small font-weight-bold">Telefone:</span> <span class="text-dark"><?php echo htmlspecialchars($filiado['telefone']); ?></span></p>
                                <p class="mb-0"><i class="fa-solid fa-envelope text-muted mr-2" style="width: 20px;"></i> <span class="text-secondary small font-weight-bold">E-mail:</span> <span class="text-dark small"><?php echo htmlspecialchars($filiado['email']); ?></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mensalidades / Faturas -->
                    <div class="card card-custom">
                        <div class="card-header-custom bg-white">
                            <h5 class="font-weight-bold text-dark mb-0"><i class="fa-solid fa-file-invoice-dollar text-danger mr-2"></i> Mensalidades</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if (empty($mensalidades)): ?>
                                <p class="text-muted text-center mb-0 small">Nenhum registro de mensalidade encontrado.</p>
                            <?php else: ?>
                                <div style="max-height: 350px; overflow-y: auto;">
                                    <?php foreach ($mensalidades as $m): 
                                        $statusClass = ($m['status_pagamento'] === 'pago') ? 'badge-success' : (($m['status_pagamento'] === 'atrasado') ? 'badge-danger' : 'badge-warning');
                                    ?>
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3 bg-light shadow-sm">
                                            <div>
                                                <h6 class="font-weight-bold mb-1 text-dark">Ref. <?php echo htmlspecialchars($m['referencia']); ?></h6>
                                                <span class="badge <?php echo $statusClass; ?> text-uppercase small" style="font-size: 0.65rem;"><?php echo htmlspecialchars($m['status_pagamento']); ?></span>
                                                <p class="mb-0 text-muted small mt-1">Vencimento: <?php echo date("d/m/Y", strtotime($m['data_vencimento'])); ?></p>
                                            </div>
                                            <div class="text-right">
                                                <h6 class="font-weight-bold text-danger mb-2">R$ <?php echo number_format($m['valor'], 2, ',', '.'); ?></h6>
                                                <?php if ($m['status_pagamento'] === 'pago'): ?>
                                                    <a href="../controllers/gerar_recibo.php?id=<?php echo $m['id']; ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill font-weight-bold"><i class="fa-solid fa-file-pdf mr-1"></i> Recibo</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Coluna Direita: Frequência e Histórico de Exames -->
                <div class="col-lg-8">
                    <!-- Frequência / Presenças -->
                    <div class="card card-custom">
                        <div class="card-header-custom bg-white">
                            <h5 class="font-weight-bold text-dark mb-0"><i class="fa-solid fa-clipboard-user text-danger mr-2"></i> Minha Frequência / Presenças</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if (empty($frequencia)): ?>
                                <p class="text-muted text-center mb-0">Nenhuma informação de chamada de presença registrada.</p>
                            <?php else: ?>
                                <?php foreach ($frequencia as $f): 
                                    $barClass = ($f['taxa_frequencia'] >= 75) ? 'bg-success' : (($f['taxa_frequencia'] >= 50) ? 'bg-warning' : 'bg-danger');
                                ?>
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h6 class="font-weight-bold mb-0 text-dark"><?php echo htmlspecialchars($f['modalidade']); ?></h6>
                                            <span class="font-weight-bold text-dark"><?php echo $f['taxa_frequencia']; ?>%</span>
                                        </div>
                                        <div class="progress rounded-pill mb-2" style="height: 10px;">
                                            <div class="progress-bar <?php echo $barClass; ?>" role="progressbar" style="width: <?php echo $f['taxa_frequencia']; ?>%" aria-valuenow="<?php echo $f['taxa_frequencia']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="text-muted small mb-0">Total Aulas: <strong><?php echo $f['total_aulas']; ?></strong> | Presenças: <strong class="text-success"><?php echo $f['total_presencas'] ?? 0; ?></strong> | Faltas: <strong class="text-danger"><?php echo $f['total_faltas'] ?? 0; ?></strong></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Progresso / Exames de Faixa (Notas) -->
                    <div class="card card-custom">
                        <div class="card-header-custom bg-white">
                            <h5 class="font-weight-bold text-dark mb-0"><i class="fa-solid fa-medal text-danger mr-2"></i> Meu Progresso / Exames de Faixa</h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if (empty($exames)): ?>
                                <p class="text-muted text-center mb-0">Você ainda não participou de nenhum exame de graduação registrado.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle">
                                        <thead>
                                            <tr class="text-secondary small font-weight-bold border-bottom">
                                                <th scope="col">Data Exame</th>
                                                <th scope="col">Modalidade</th>
                                                <th scope="col">De -> Para</th>
                                                <th scope="col" class="text-center">Nota</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Feedback / Comentários</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($exames as $ex): 
                                                $statusClass = ($ex['situacao'] === 'aprovado') ? 'text-success font-weight-bold' : (($ex['situacao'] === 'reprovado') ? 'text-danger font-weight-bold' : 'text-warning font-weight-bold');
                                            ?>
                                                <tr>
                                                    <td class="align-middle text-secondary small"><?php echo date("d/m/Y", strtotime($ex['data_exame'])); ?></td>
                                                    <td class="align-middle font-weight-bold text-dark text-capitalize small"><?php echo htmlspecialchars($ex['modalidade']); ?></td>
                                                    <td class="align-middle text-dark small text-capitalize"><?php echo htmlspecialchars($ex['graduacao_atual']); ?> <i class="fa-solid fa-arrow-right mx-1 text-muted"></i> <strong><?php echo htmlspecialchars($ex['graduacao_pretendida']); ?></strong></td>
                                                    <td class="align-middle text-center font-weight-bold text-dark"><?php echo ($ex['nota'] !== null) ? number_format($ex['nota'], 2, ',', '.') : '-'; ?></td>
                                                    <td class="align-middle text-capitalize small <?php echo $statusClass; ?>"><?php echo htmlspecialchars($ex['situacao']); ?></td>
                                                    <td class="align-middle text-muted small"><?php echo htmlspecialchars($ex['comentarios'] ?? 'Nenhum feedback registrado.'); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal de Aceite de Recorrência -->
    <?php if (isset($recorrencia) && ($recorrencia['status'] ?? '') === 'pendente'): ?>
        <div class="modal fade" id="modalAceiteRecorrencia" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title font-weight-bold" id="modalLabel"><i class="fa-solid fa-file-contract mr-2"></i>Termo de Autorização Recorrente</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <div class="border rounded p-3 bg-light text-secondary text-left mb-3 small" style="max-height: 220px; overflow-y: auto; line-height: 1.5;">
                            <h6 class="font-weight-bold text-dark text-center mb-3">TERMO DE AUTORIZAÇÃO DE DÉBITO AUTOMÁTICO RECORRENTE</h6>
                            <p>Ao marcar a caixa de seleção abaixo e clicar em "Cadastrar Cartão e Ativar Assinatura", você (ou o responsável financeiro) autoriza expressamente este Dojô a realizar cobranças automáticas mensais recorrentes no cartão de crédito fornecido.</p>
                            <p><strong>1. Valor e Frequência:</strong> O débito ocorrerá mensalmente de forma automática no valor proposto de <strong>R$ <?php echo number_format($recorrencia['valor'], 2, ',', '.'); ?></strong> na data de vencimento correspondente ao dia <strong><?php echo $config_aluno['dia_vencimento']; ?></strong> de cada mês.</p>
                            <p><strong>2. Segurança dos Dados:</strong> As informações do seu cartão de crédito são transmitidas, criptografadas e processadas de forma 100% segura diretamente nos servidores da plataforma parceira <strong>Stripe</strong>. Nossos servidores locais não armazenam nem têm acesso a esses dados confidenciais.</p>
                            <p><strong>3. Cancelamento Autônomo:</strong> Esta autorização pode ser cancelada a qualquer momento por você diretamente no Painel do Aluno, sem qualquer taxa ou burocracia, interrompendo futuros débitos imediatamente.</p>
                        </div>
                        
                        <form action="../controllers/alunoController.php" method="post" class="mt-3">
                            <input type="hidden" name="tipo" value="ativar_recorrencia_stripe">
                            <div class="form-group form-check text-left">
                                <input type="checkbox" name="aceita_termos" value="1" class="form-check-input" id="checkTermos" required>
                                <label class="form-check-label text-dark small" for="checkTermos" style="cursor: pointer; user-select: none;">
                                    <strong>Li, compreendi e autorizo</strong> as cobranças automáticas recorrentes descritas acima no meu cartão de crédito.
                                </label>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex align-items-center justify-content-between">
                                <button type="button" class="btn btn-light rounded-pill px-4 font-weight-bold" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4 font-weight-bold shadow-sm d-inline-flex align-items-center">
                                    <i class="fa-brands fa-stripe mr-2" style="font-size: 1.2rem;"></i> Cadastrar Cartão e Ativar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
