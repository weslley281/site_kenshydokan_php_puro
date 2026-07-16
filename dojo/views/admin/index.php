<?php
// views/admin/index.php
include "menu.php";
include_once __DIR__ . "/../../models/usuarioModel.php";
include_once __DIR__ . "/../../models/imagemModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$id_usuario = $_SESSION['id_usuario'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "sensei") {
  $pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 'gerenciamento_dojo';
  
  if ($pagina === 'gerenciamento_dojo') {
      include_once __DIR__ . '/../../models/dojoAlunoConfigModel.php';
      include_once __DIR__ . '/../../models/dojoMensalidadeModel.php';
      include_once __DIR__ . '/../../models/dojoFinanceiroModel.php';

      $referencia = isset($_GET["referencia"]) ? $_GET["referencia"] : date("Y-m");

      $alunoConfigModel = new DojoAlunoConfigModel();
      $mensalidadeModel = new DojoMensalidadeModel();
      $financeiroModel = new DojoFinanceiroModel();

      // Atualiza inadimplentes automaticamente ao carregar
      $mensalidadeModel->atualizarInadimplencias();

      // Dados para cartões
      $resumo_financeiro = $financeiroModel->buscarResumoFinanceiroMes($referencia);
      $counts_alunos = $alunoConfigModel->contarStatus();

      $sub = isset($_GET["sub"]) ? $_GET["sub"] : 'dashboard';
      
      ?>
      <div class="container py-2">
          <div class="d-flex align-items-center justify-content-between mb-4">
              <div>
                  <div class="d-flex align-items-center flex-wrap">
                      <h2 class="font-weight-bold text-dark mb-0 mr-2">Gestão do Dojô</h2>
                      <a href="index.php?pagina=gerenciamento_dojo&sub=ajuda" class="btn btn-sm btn-outline-danger border-0 rounded-circle d-inline-flex align-items-center justify-content-center" title="Como usar o sistema? / Ajuda" style="width: 32px; height: 32px; padding: 0;">
                          <i class="fa-solid fa-circle-question" style="font-size: 1.3rem;"></i>
                      </a>
                  </div>
                  <p class="text-muted mb-0"><i class="fa-regular fa-calendar mr-2"></i>Mês de Referência: <?php echo date("m/Y", strtotime($referencia . '-01')); ?></p>
              </div>
              <div>
                  <!-- Seletor de Referência de Mês -->
                  <form method="get" action="index.php" class="form-inline bg-white p-2 rounded-pill shadow-sm border">
                      <input type="hidden" name="pagina" value="gerenciamento_dojo">
                      <input type="hidden" name="sub" value="<?php echo htmlspecialchars($sub); ?>">
                      <label for="referencia" class="mr-2 ml-1 text-secondary small font-weight-bold text-uppercase">Período:</label>
                      <input type="month" id="referencia" name="referencia" value="<?php echo htmlspecialchars($referencia); ?>" class="form-control form-control-sm bg-light border-0 mr-2 rounded-pill px-3" onchange="this.form.submit()">
                  </form>
              </div>
          </div>

          <!-- Navegação por Abas (Premium Pills) -->
          <ul class="nav nav-pills nav-fill mb-4 p-1 bg-white rounded-pill shadow-sm border" style="gap: 5px;">
              <li class="nav-item">
                  <a class="nav-link rounded-pill font-weight-bold <?php echo ($sub == 'dashboard') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=gerenciamento_dojo&sub=dashboard&referencia=<?php echo $referencia; ?>">
                      <i class="fa-solid fa-gauge mr-1"></i> Painel Geral
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link rounded-pill font-weight-bold <?php echo ($sub == 'alunos') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=gerenciamento_dojo&sub=alunos&referencia=<?php echo $referencia; ?>">
                      <i class="fa-solid fa-user-gear mr-1"></i> Config. Alunos
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link rounded-pill font-weight-bold <?php echo ($sub == 'mensalidades') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=gerenciamento_dojo&sub=mensalidades&referencia=<?php echo $referencia; ?>">
                      <i class="fa-solid fa-sack-dollar mr-1"></i> Mensalidades
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link rounded-pill font-weight-bold <?php echo ($sub == 'financeiro') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=gerenciamento_dojo&sub=financeiro&referencia=<?php echo $referencia; ?>">
                      <i class="fa-solid fa-cash-register mr-1"></i> Livro Caixa
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link rounded-pill font-weight-bold <?php echo ($sub == 'link_stripe') ? 'active bg-danger text-white' : 'text-secondary'; ?>" href="index.php?pagina=gerenciamento_dojo&sub=link_stripe&referencia=<?php echo $referencia; ?>">
                      <i class="fa-brands fa-stripe mr-1"></i> Enviar Link Stripe
                  </a>
              </li>
          </ul>

          <!-- Conteúdo das Abas -->
          <div class="tab-content" id="dojoTabContent">
              <?php
              if ($sub === 'dashboard') {
                  include_once __DIR__ . '/../gerenciamento_dojo/dashboard.php';
              } elseif ($sub === 'alunos') {
                  include_once __DIR__ . '/../gerenciamento_dojo/alunos.php';
              } elseif ($sub === 'mensalidades') {
                  include_once __DIR__ . '/../gerenciamento_dojo/mensalidades.php';
              } elseif ($sub === 'financeiro') {
                  include_once __DIR__ . '/../gerenciamento_dojo/financeiro.php';
              } elseif ($sub === 'link_stripe') {
                  include_once __DIR__ . '/../gerenciamento_dojo/link_stripe.php';
              } elseif ($sub === 'ajuda') {
                  include_once __DIR__ . '/../gerenciamento_dojo/ajuda.php';
              } else {
                  include_once __DIR__ . '/../gerenciamento_dojo/dashboard.php';
              }
              ?>
          </div>
      </div>

      <!-- Modais fora do container de padding -->
      <?php
      if ($sub === 'alunos') {
          include_once __DIR__ . '/../gerenciamento_dojo/alunos_modals.php';
      } elseif ($sub === 'mensalidades') {
          include_once __DIR__ . '/../gerenciamento_dojo/mensalidades_modals.php';
      } elseif ($sub === 'financeiro') {
          include_once __DIR__ . '/../gerenciamento_dojo/financeiro_modals.php';
      }
  } else {
      if ($pagina === 'usuarios') {
          include_once "usuarios.php";
      } elseif ($pagina === 'filiados') {
          include_once "filiados.php";
      } elseif ($pagina === 'dojos') {
          include_once "dojos.php";
      } elseif ($pagina === 'graduacoes') {
          include_once "graduacoes.php";
      } elseif ($pagina === 'artes') {
          include_once "artes.php";
      } elseif ($pagina === 'chamada') {
          include_once "chamada.php";
      } elseif ($pagina === 'exames') {
          include_once "exames.php";
      } else {
          echo "<div class='container'><div class='alert alert-warning'>Página não encontrada ou módulo indisponível neste gerenciador.</div></div>";
      }
  }

  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='../login.php'; </script>";
}
?>
