<?php
include "menu.php";
include_once "../models/usuarioModel.php";
include_once "../models/imagemModel.php";
include_once "../models/filiadoModel.php";
include_once "../models/graduacaoModel.php";

if (!isset($_GET['id_usuario']) || empty($_GET['id_usuario'])) {
    echo "<script>window.location.href='inicio.php';</script>";
    exit;
}

$id_usuario = $_GET['id_usuario'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

if (!$usuario) {
    echo "<div class='container mt-5 text-center'><div class='alert alert-danger py-4 shadow-sm border-0'><i class='fa-solid fa-circle-exclamation fa-2x mb-3 text-danger'></i><h4>Usuário não encontrado</h4><p>O perfil selecionado não existe ou foi removido.</p><a href='inicio.php' class='btn btn-danger rounded-pill px-4 mt-2'>Ir para a Página Inicial</a></div></div>";
    include "rodape.php";
    exit;
}

$imagem = Imagem::procura_imagem($usuario["id_imagem"]);
$caminho_foto = '../img/sem_foto.png';
if ($imagem && !empty($imagem['caminho'])) {
    if (file_exists(__DIR__ . '/' . $imagem['caminho'])) {
        $caminho_foto = $imagem['caminho'];
    } else if (file_exists(__DIR__ . '/../img/' . $imagem['nome'])) {
        $caminho_foto = '../img/' . $imagem['nome'];
    }
}

$id_filiado = $usuario["id_fil"];
$filiadoModelRepo = new FiliadoModel();
$filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);

if ($filiado_obj) {
    $filiado = [
        'confirmacao' => $filiado_obj->getConfirmacao(),
        'dojo' => $filiado_obj->getDojo()
    ];
    $id_graduacao = $filiado_obj->getIdGraduacao();
} else {
    $filiado = ['confirmacao' => 'nao', 'dojo' => 'Não informado'];
    $id_graduacao = 0;
}

$confirmacao = $filiado["confirmacao"];
if ($confirmacao == "sim") {
    $ativo = "Filiado Confirmado";
    $status_color = "success";
    $status_icon = "fa-circle-check";
} else {
    $ativo = "Filiação Pendente";
    $status_color = "warning";
    $status_icon = "fa-clock";
}

$graduacao = Graduacao::buscarGraduacao($id_graduacao);
?>

<style>
  .text-muted-light {
      color: rgba(255, 255, 255, 0.7) !important;
  }
  .profile-card {
      border-radius: 16px !important;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .profile-card:hover {
      box-shadow: 0 1rem 3rem rgba(0,0,0,.15) !important;
  }
  .profile-left {
      background: linear-gradient(135deg, #1f1f1f 0%, #3d0000 100%), url('../img/foto_principal.jpeg');
      background-size: cover;
      background-position: center;
      background-blend-mode: overlay;
      position: relative;
  }
  .profile-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(18, 18, 18, 0.85);
      z-index: 1;
  }
  .profile-content {
      position: relative;
      z-index: 2;
  }
  .info-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #6c757d;
      margin-bottom: 2px;
      font-weight: 600;
  }
  .info-val {
      font-size: 1rem;
      font-weight: 700;
      color: #212529;
  }
</style>

<div class="container py-5">
    <div class="mb-4">
        <a href="filiados.php" class="btn btn-outline-danger btn-sm rounded-pill px-4 shadow-sm font-weight-bold">
            <i class="fa-solid fa-arrow-left mr-2"></i>Voltar para Filiados
        </a>
    </div>

    <div class="card border-0 shadow-lg profile-card overflow-hidden">
        <div class="row no-gutters">
            <!-- Left Side Panel (Cover & Avatar) -->
            <div class="col-lg-4 text-white text-center py-5 px-4 d-flex flex-column align-items-center justify-content-center profile-left">
                <div class="profile-overlay"></div>
                
                <div class="profile-content w-100">
                    <div class="mb-4 d-inline-block position-relative">
                        <img class="rounded-circle shadow-lg border border-danger" 
                             src="<?php echo htmlspecialchars($caminho_foto); ?>" 
                             alt="Foto de <?php echo htmlspecialchars($usuario["nome"]); ?>" 
                             style="width: 160px; height: 160px; object-fit: cover; border-width: 4px !important;">
                        <span class="position-absolute shadow-sm" style="bottom: 5px; right: 8px; background-color: <?php echo $confirmacao == 'sim' ? '#28a745' : '#ffc107'; ?>; width: 26px; height: 26px; border-radius: 50%; border: 3px solid #1f1f1f; display: flex; align-items: center; justify-content: center;" title="<?php echo $ativo; ?>">
                            <i class="fa-solid <?php echo $confirmacao == 'sim' ? 'fa-check text-white' : 'fa-clock text-dark'; ?>" style="font-size: 10px;"></i>
                        </span>
                    </div>

                    <h3 class="font-weight-bold text-white mb-2 text-capitalize"><?php echo htmlspecialchars($usuario["nome"]); ?></h3>
                    
                    <span class="badge badge-danger text-uppercase px-3 py-2 font-weight-bold shadow-sm" 
                          style="letter-spacing: 1px; font-size: 0.75rem; border-radius: 30px; background-color: #dc3545;">
                        <?php 
                        $niveis = ['admin' => 'Administrador', 'sensei' => 'Sensei', 'aluno' => 'Aluno', 'dojo' => 'Representante de Dojô'];
                        echo isset($niveis[$usuario["nivel"]]) ? $niveis[$usuario["nivel"]] : ucfirst($usuario["nivel"]);
                        ?>
                    </span>
                    
                    <div class="mt-4 text-muted-light small">
                        <i class="fa-regular fa-calendar-days mr-2"></i>Membro desde: <?php echo date('d/m/Y', strtotime($usuario['dataCriacao'])); ?>
                    </div>
                </div>
            </div>

            <!-- Right Side Panel (Information details) -->
            <div class="col-lg-8 bg-white p-4 p-md-5">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4 border-bottom pb-3">
                    <h4 class="font-weight-bold text-dark mb-2 mb-sm-0">
                        <i class="fa-solid fa-address-card text-danger mr-2"></i>Ficha Cadastral
                    </h4>
                    <span class="badge badge-<?php echo $status_color; ?> px-3 py-2 rounded-pill font-weight-bold shadow-sm" style="font-size: 0.85rem;">
                        <i class="fa-solid <?php echo $status_icon; ?> mr-2"></i><?php echo $ativo; ?>
                    </span>
                </div>

                <div class="row">
                    <!-- General details -->
                    <div class="col-md-6 pr-md-4 border-right">
                        <h5 class="font-weight-bold text-danger mb-4">
                            <i class="fa-solid fa-circle-info mr-2"></i>Dados Gerais
                        </h5>
                        
                        <div class="mb-4">
                            <div class="info-label">E-mail</div>
                            <div class="info-val d-flex align-items-center text-break">
                                <i class="fa-solid fa-envelope text-muted mr-3" style="width: 16px;"></i>
                                <?php echo htmlspecialchars($usuario["email"]); ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="info-label">Telefone / WhatsApp</div>
                            <div class="info-val d-flex align-items-center">
                                <i class="fa-solid fa-phone text-muted mr-3" style="width: 16px;"></i>
                                <?php 
                                $tel = $usuario["telefone"];
                                if (strlen($tel) == 11) {
                                    echo "(".substr($tel, 0, 2).") ".substr($tel, 2, 5)."-".substr($tel, 7);
                                } elseif (strlen($tel) == 10) {
                                    echo "(".substr($tel, 0, 2).") ".substr($tel, 2, 4)."-".substr($tel, 6);
                                } else {
                                    echo htmlspecialchars($tel);
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="info-label">Dojô de Origem</div>
                            <div class="info-val d-flex align-items-center">
                                <i class="fa-solid fa-gopuran text-muted mr-3" style="width: 16px;"></i>
                                <?php echo htmlspecialchars($filiado["dojo"]); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Graduations list -->
                    <div class="col-md-6 pl-md-4 mt-4 mt-md-0">
                        <h5 class="font-weight-bold text-danger mb-4">
                            <i class="fa-solid fa-award mr-2"></i>Graduações Oficiais
                        </h5>
                        
                        <?php if ($filiado_obj): ?>
                            <?php 
                            $filiadoGraduacoes = $filiadoModelRepo->buscarGraduacoesFiliado($id_filiado);
                            if (!empty($filiadoGraduacoes)): 
                            ?>
                                <div class="list-group list-group-flush rounded shadow-sm border overflow-hidden">
                                    <?php foreach ($filiadoGraduacoes as $fg): ?>
                                        <div class="list-group-item d-flex align-items-center py-3 px-3 bg-light border-bottom">
                                            <div class="mr-3 text-center d-flex align-items-center justify-content-center bg-danger text-white rounded-circle shadow-sm" style="width: 36px; height: 36px; min-width: 36px;">
                                                <i class="fa-solid fa-graduation-cap" style="font-size: 0.85rem;"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-capitalize text-dark mb-0" style="font-size: 0.95rem; line-height: 1.2;">
                                                    <?php echo htmlspecialchars($fg['graduacao_nome']); ?>
                                                </div>
                                                <small class="text-danger font-weight-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                                    <?php echo htmlspecialchars($fg['arte_nome']); ?>
                                                </small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light py-4 text-center border shadow-sm rounded">
                                    <i class="fa-solid fa-circle-exclamation text-muted mb-2 d-block fa-lg"></i>
                                    <span class="text-muted small font-weight-bold">Sem graduações registradas</span>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="alert alert-light py-4 text-center border shadow-sm rounded">
                                <i class="fa-solid fa-user-slash text-muted mb-2 d-block fa-lg"></i>
                                <span class="text-muted small font-weight-bold">Este usuário não possui registro de filiado</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "rodape.php"; ?>