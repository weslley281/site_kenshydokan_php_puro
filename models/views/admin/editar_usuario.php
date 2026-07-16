<?php
include_once "menu.php";
include_once "../../models/usuarioModel.php";
include_once "../../models/imagemModel.php";
include_once "../../models/filiadoModel.php";
include_once "../../models/graduacaoModel.php";

$id_usuario = $_GET['id'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

$imagem = Imagem::procura_imagem($usuario["id_imagem"]);

$filiadoModelRepo = new FiliadoModel();

if ($usuario["id_fil"] != 0 && $usuario["id_fil"] != null) {
    $id_filiado = $usuario["id_fil"];
    $filiado_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);
    
    if ($filiado_obj) {
        $filiado = [
            'id_filiado' => $filiado_obj->getIdFiliado(),
            'nome' => $filiado_obj->getNome(),
            'confirmacao' => $filiado_obj->getConfirmacao()
        ];
        $estaFiliado = ($filiado["confirmacao"] == "sim") ? "Filiado Ativo" : "Filiação Pendente";

        $id_graduacao = $filiado_obj->getIdGraduacao();
        $graduacao = Graduacao::buscarGraduacao($id_graduacao);
    } else {
        $estaFiliado = "Sem registro";
        $filiado = array(
            "nome" => "Nenhum filiado vinculado",
            "id_filiado" => 0,
        );
    }
} else {
    $estaFiliado = "Sem registro";
    $filiado = array(
        "nome" => "Nenhum filiado vinculado",
        "id_filiado" => 0,
    );
}

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="font-weight-bold text-dark mb-0">Editar Usuário</h2>
      <a href="index.php?pagina=usuarios" class="btn btn-outline-secondary font-weight-bold rounded-pill shadow-sm px-4">
        <i class="fas fa-arrow-left mr-2"></i> Voltar para Usuários
      </a>
    </div>

    <div class="card border-0 shadow-sm rounded-lg" style="max-width: 650px; margin: 0 auto;">
      <div class="card-body p-4">
        <h5 class="text-danger font-weight-bold mb-4"><i class="fa-solid fa-user-pen mr-2"></i>Informações do Usuário</h5>
        
        <form enctype="multipart/form-data" action="../../controllers/usuarioController.php" method="post">
          <input type="hidden" name="tipo" value="edidar_admin">
          <input type="hidden" name="id_imagem" value="<?php echo $usuario["id_imagem"]; ?>">
          <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

          <div class="form-group mb-3">
            <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome do Usuário</label>
            <input id="nome" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($usuario["nome"]); ?>" name="nome" required autofocus>
          </div>

          <div class="form-group mb-3">
            <label for="id_fil" class="text-secondary small font-weight-bold text-uppercase">Vincular Registro de Filiado</label>
            <select id="id_fil" class="form-control form-control-lg bg-light border-0 shadow-sm js-example-basic-single" name="id_fil">
              <option value="<?php echo $filiado["id_filiado"]; ?>" selected><?php echo htmlspecialchars($filiado["nome"]); ?> (<?php echo $estaFiliado; ?>)</option>
              <option value="0">Nenhum filiado vinculado</option>
              <?php
              $todos_filiados = $filiadoModelRepo->listarFiliados();
              if (!empty($todos_filiados)) {
                foreach ($todos_filiados as $dado) {
                  if ($dado["id_filiado"] != $filiado["id_filiado"]) {
                    echo '<option value="' . $dado["id_filiado"] . '">' . htmlspecialchars($dado["nome"]) . '</option>';
                  }
                }
              }
              ?>
            </select>
          </div>

          <div class="form-row mb-3">
            <div class="form-group col-md-6 mb-0">
              <label for="nivel" class="text-secondary small font-weight-bold text-uppercase">Nível de Acesso</label>
              <select id="nivel" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nivel" required>
                <option value="aluno" <?php echo ($usuario["nivel"] == 'aluno') ? 'selected' : ''; ?>>Aluno</option>
                <option value="kohai" <?php echo ($usuario["nivel"] == 'kohai') ? 'selected' : ''; ?>>Kohai</option>
                <option value="sensei" <?php echo ($usuario["nivel"] == 'sensei') ? 'selected' : ''; ?>>Sensei</option>
                <option value="admin" <?php echo ($usuario["nivel"] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
              </select>
            </div>
            <div class="form-group col-md-6 mb-0">
              <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone</label>
              <input id="telefone" type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($usuario["telefone"]); ?>" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
            </div>
          </div>

          <div class="form-group mb-4">
            <label for="email" class="text-secondary small font-weight-bold text-uppercase">Email</label>
            <input id="email" type="email" class="form-control form-control-lg bg-light border-0 shadow-sm" value="<?php echo htmlspecialchars($usuario["email"]); ?>" name="email" required>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-danger font-weight-bold rounded-pill shadow-sm px-5 py-2 mr-2">
              <i class="fas fa-save mr-2"></i> Salvar Alterações
            </button>
            <a href="index.php?pagina=usuarios" class="btn btn-light font-weight-bold rounded-pill shadow-sm px-5 py-2 border">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  include "rodape.php";
} else {
  echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>