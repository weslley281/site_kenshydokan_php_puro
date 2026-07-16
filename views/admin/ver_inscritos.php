<?php
include_once "menu.php";
include_once __DIR__ . "/../../models/campeonatoModel.php";
include_once __DIR__ . "/../../models/inscricaoModel.php";

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    $id_campeonato = $_GET["id"] ?? null;
    if (!$id_campeonato) {
        echo "<script language='javascript'>window.location='index.php?pagina=campeonatos'; </script>";
        exit;
    }

    $campModel = new Campeonato();
    $camp = $campModel->buscarCampeonatoPorId($id_campeonato);

    if (!$camp) {
        echo "<script language='javascript'>window.location='index.php?pagina=campeonatos'; </script>";
        exit;
    }

    $inscModel = new Inscricao();
    $inscritos = $inscModel->buscarInscricoesPorCampeonato($id_campeonato);
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Atletas Inscritos</h2>
      <a href="index.php?pagina=campeonatos" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Campeonatos
      </a>
    </div>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm rounded-lg mb-4 bg-light">
      <div class="card-body p-4">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h4 class="font-weight-bold text-danger mb-1"><?php echo htmlspecialchars($camp->getTitulo()); ?></h4>
            <p class="text-muted mb-0"><?php echo htmlspecialchars($camp->getSubtitulo()); ?></p>
          </div>
          <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <span class="d-block text-secondary small"><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($camp->getDataCriacao())); ?></span>
            <span class="d-block text-secondary small"><strong>Local:</strong> <?php echo htmlspecialchars($camp->getEndereco()); ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Inscritos Table -->
    <div class="card border-0 shadow-sm rounded-lg">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabelaInscritos" class="table table-hover align-middle" width="100%" cellspacing="0">
            <thead>
              <tr class="text-secondary small font-weight-bold border-bottom">
                <th scope="col">Atleta</th>
                <th scope="col">E-mail / Telefone</th>
                <th scope="col" class="text-center">Nível</th>
                <th scope="col" class="text-center">Modalidade</th>
                <th scope="col" class="text-center">Categoria/Peso</th>
                <th scope="col" class="text-center">Status</th>
                <th scope="col" class="text-center" style="width: 180px;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($inscritos)): ?>
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">Nenhum atleta inscrito neste campeonato até o momento.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($inscritos as $insc): 
                    $id_insc = $insc["id_inscricao"];
                    $nome = $insc["nome_usuario"];
                    $email = $insc["email_usuario"];
                    $telefone = $insc["telefone_usuario"];
                    $nivel_user = $insc["nivel_usuario"];
                    $modalidade = $insc["modalidade"];
                    $categoria_peso = $insc["categoria_peso"] ? $insc["categoria_peso"] : 'Absoluto';
                    $status = strtolower($insc["status_inscricao"]);

                    $status_class = 'secondary';
                    if ($status === 'confirmada') $status_class = 'success';
                    elseif ($status === 'pendente') $status_class = 'info';
                    elseif ($status === 'cancelada') $status_class = 'danger';
                ?>
                  <tr>
                    <td class="align-middle font-weight-bold text-dark text-capitalize"><?php echo htmlspecialchars($nome); ?></td>
                    <td class="align-middle text-muted small">
                      <?php echo htmlspecialchars($email); ?>
                      <span class="d-block"><?php echo htmlspecialchars($telefone ? $telefone : 'Telefone N/A'); ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="badge badge-dark text-uppercase px-2 py-1" style="font-size: 0.75rem;">
                        <?php echo htmlspecialchars($nivel_user); ?>
                      </span>
                    </td>
                    <td class="align-middle text-center font-weight-bold text-primary"><?php echo htmlspecialchars($modalidade); ?></td>
                    <td class="align-middle text-center text-secondary small font-weight-bold"><?php echo htmlspecialchars($categoria_peso); ?></td>
                    <td class="align-middle text-center">
                      <span class="badge badge-<?php echo $status_class; ?> text-uppercase px-3 py-1 font-weight-bold">
                        <?php echo htmlspecialchars($status); ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="d-flex align-items-center justify-content-center">
                        <!-- Confirmar -->
                        <?php if ($status !== 'confirmada'): ?>
                          <form action="../../controllers/campeonatoController.php" method="post" class="m-0 mr-1">
                            <input type="hidden" name="tipo" value="alterar_status_inscricao">
                            <input type="hidden" name="id_inscricao" value="<?php echo $id_insc; ?>">
                            <input type="hidden" name="id_campeonato" value="<?php echo $id_campeonato; ?>">
                            <input type="hidden" name="status_inscricao" value="confirmada">
                            <button type="submit" class="btn btn-sm btn-success border-0 rounded-circle" title="Confirmar Inscrição" style="width: 32px; height: 32px; padding: 5px 0;">
                              <i class="fa-solid fa-check"></i>
                            </button>
                          </form>
                        <?php endif; ?>

                        <!-- Pendente -->
                        <?php if ($status !== 'pendente'): ?>
                          <form action="../../controllers/campeonatoController.php" method="post" class="m-0 mr-1">
                            <input type="hidden" name="tipo" value="alterar_status_inscricao">
                            <input type="hidden" name="id_inscricao" value="<?php echo $id_insc; ?>">
                            <input type="hidden" name="id_campeonato" value="<?php echo $id_campeonato; ?>">
                            <input type="hidden" name="status_inscricao" value="pendente">
                            <button type="submit" class="btn btn-sm btn-info border-0 rounded-circle" title="Marcar como Pendente" style="width: 32px; height: 32px; padding: 5px 0;">
                              <i class="fa-solid fa-clock"></i>
                            </button>
                          </form>
                        <?php endif; ?>

                        <!-- Cancelar -->
                        <?php if ($status !== 'cancelada'): ?>
                          <form action="../../controllers/campeonatoController.php" method="post" class="m-0 mr-1">
                            <input type="hidden" name="tipo" value="alterar_status_inscricao">
                            <input type="hidden" name="id_inscricao" value="<?php echo $id_insc; ?>">
                            <input type="hidden" name="id_campeonato" value="<?php echo $id_campeonato; ?>">
                            <input type="hidden" name="status_inscricao" value="cancelada">
                            <button type="submit" class="btn btn-sm btn-warning border-0 rounded-circle" title="Rejeitar/Cancelar" style="width: 32px; height: 32px; padding: 5px 0;">
                              <i class="fa-solid fa-xmark"></i>
                            </button>
                          </form>
                        <?php endif; ?>

                        <!-- Deletar Totalmente -->
                        <form action="../../controllers/campeonatoController.php" method="post" class="m-0">
                          <input type="hidden" name="tipo" value="cancelar_inscricao">
                          <input type="hidden" name="id_inscricao" value="<?php echo $id_insc; ?>">
                          <input type="hidden" name="redirect" value="../views/admin/ver_inscritos.php?id=<?php echo $id_campeonato; ?>">
                          <button type="submit" class="btn btn-sm btn-danger border-0 rounded-circle" title="Excluir Inscrição" onclick="return confirm('Deseja excluir permanentemente este inscrito?');" style="width: 32px; height: 32px; padding: 5px 0;">
                            <i class="fa-solid fa-trash-can"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
      if (typeof $ !== 'undefined' && $.fn.DataTable) {
          if (!$.fn.DataTable.isDataTable('#tabelaInscritos')) {
              $('#tabelaInscritos').DataTable({
                  "order": [[0, "asc"]],
                  "pageLength": 25,
                  "searching": true,
                  "language": {
                      "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                  }
              });
          }
      }
  });
  </script>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='../login.php'; </script>";
}
?>
