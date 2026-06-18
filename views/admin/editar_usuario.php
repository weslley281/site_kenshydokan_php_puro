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
        $estaFiliado = ($filiado["confirmacao"] == "sim") ? "Você está filiado" : "Aguardando Confirmação de Filiação, Não Está Filiado Não";

        $id_graduacao = $filiado_obj->getIdGraduacao();
        $graduacao = Graduacao::buscarGraduacao($id_graduacao);
    } else {
        $estaFiliado = "Sem dados";
        $filiado = array(
            "nome" => "A definir",
            "dojo" => "A definir",
            "id_filiado" => 0,
        );
        $graduacao = array(
            'graduacao' => 'sem registro',
        );
    }
} else {
    $estaFiliado = "Sem dados";
    $filiado = array(
        "nome" => "A definir",
        "dojo" => "A definir",
        "id_filiado" => 0,
    );
    $graduacao = array(
        'graduacao' => 'sem registro',
    );
}

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
    ?>
<!-- /Navigation -->

<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card card-signin my-5">
        <div class="card-body">
          <h5 class="card-title text-center">Editar</h5>
          <form class="form-signin" enctype="multipart/form-data" action="../../controllers/usuarioController.php" method="post">
            <input type="hidden" name="tipo" value="edidar_admin">
            <input type="hidden" name="id_imagem" value="<?php echo $usuario["id_imagem"]; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

            <div class="form-group">
              <label for="nome">Nome: </label>
              <input id="nome" type="text" class="form-control" value="<?php echo $usuario["nome"] ?>" name="nome" required autofocus>
            </div>

            <div class="form-group">
              <label for="id_fil">Registro de Filiado: </label>
                <select id="id_fil" class="form-select form-control js-example-basic-single"" aria-label="Default select example" name="id_fil">
                <option value="<?php echo $filiado["id_filiado"] ?>" selected><?php echo htmlspecialchars($filiado["nome"]) ?></option>
            <?php
    $todos_filiados = $filiadoModelRepo->listarFiliados();
    if (!empty($todos_filiados)) {
        foreach ($todos_filiados as $dado) {
            echo '<option value="' . $dado["id_filiado"] . '">' . htmlspecialchars($dado["nome"]) . '</option>';
        }
    } else {
        echo '<option>Erro ao carregar os dados</option>';
    }
    ?>
                </select>
            </div>

            <div class="form-group">
              <label for="nome">Nivel: </label>
              <select id="nivel" class="form-select form-control" name="nivel">
                <option value="<?php echo $usuario["nivel"] ?>"><?php echo ucfirst($usuario["nivel"]); ?></option>

                <?php if ($usuario["nivel"] != "aluno") {?>
                    <option value="aluno">Aluno</option>
                <?php }?>

                <?php if ($usuario["nivel"] != "kohai") {?>
                    <option value="kohai">Kohai</option>
                <?php }?>

                <?php if ($usuario["nivel"] != "sensei") {?>
                    <option value="sensei">Sensei</option>
                <?php }?>

                <?php if ($usuario["nivel"] != "admin") {?>
                    <option value="admin">Admin</option>
                <?php }?>
              </select>
            </div>

            <div class="form-group">
              <label for="email">Email: </label>
                <input id="email" type="email" class="form-control" value="<?php echo $usuario["email"] ?>" name="email" required>
            </div>

            <div class="form-group">
              <label for="telefone">Telefone: </label>
              <input id="telefone" type="text" class="form-control" value="<?php echo $usuario["telefone"] ?>" name="telefone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
            </div>

            <input class="btn btn-lg btn-success btn-block text-uppercase" type="submit" value="Salvar">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include "rodape.php";
} else {
    echo "<script language='javascript'>window.location='login.php'; </script>";
}
?>