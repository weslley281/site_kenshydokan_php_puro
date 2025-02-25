<?php
include_once "menu.php";
include_once "../db/conexao.php";

$c = new Conexao();
$conexao = $c->conectar();

if (isset($_SESSION["id_usuario"]) && $_SESSION['nivel'] == "admin") {
?>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Cadastrar Filiado</h5>
                        <form class="form-signin" enctype="multipart/form-data" action="../controllers/filiadoController.php" method="post">
                            <input type="hidden" name="tipo" value="inserir">

                            <div class="form-group">
                                <label for="nome">Nome: </label>
                                <input id="nome" type="text" class="form-control" name="nome" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="id_graduacao">Graduação: </label>
                                <select id="id_graduacao" class="form-select form-control js-example-basic-single" name="id_graduacao">
                                    <?php
                                    $consulta = "SELECT id_graduacao, graduacao FROM graduacao";
                                    $resultado = mysqli_query($conexao, $consulta);
                                    if ($resultado) {
                                        while ($dado = mysqli_fetch_array($resultado)) {
                                            echo '<option value="' . $dado["id_graduacao"] . '">' . $dado["graduacao"] . '</option>';
                                        }
                                    } else {
                                        echo '<option>Erro ao carregar os dados</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="dojo">Dojo: </label>
                                <input id="dojo" type="text" class="form-control" name="dojo" required>
                            </div>

                            <div class="form-group">
                                <label for="telefone">Telefone: </label>
                                <input id="telefone" type="text" class="form-control" name="telefone" required>
                            </div>

                            <div class="form-group">
                                <label for="rg">RG: </label>
                                <input id="rg" type="text" class="form-control" name="rg" required>
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail: </label>
                                <input id="email" type="email" class="form-control" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="endereco">Endereço: </label>
                                <input id="endereco" type="text" class="form-control" name="endereco">
                            </div>

                            <div class="form-group">
                                <label for="cidade">Cidade: </label>
                                <input id="cidade" type="text" class="form-control" name="cidade">
                            </div>

                            <div class="form-group">
                                <label for="id_estado">Estado: </label>
                                <select id="id_estado" class="form-select form-control" name="id_estado">
                                    <?php
                                    $consulta = "SELECT id_estado, estado FROM estados";
                                    $resultado = mysqli_query($conexao, $consulta);
                                    if ($resultado) {
                                        while ($dado = mysqli_fetch_array($resultado)) {
                                            echo '<option value="' . $dado["id_estado"] . '">' . $dado["estado"] . '</option>';
                                        }
                                    } else {
                                        echo '<option>Erro ao carregar os dados</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="confirmacao">Confirmação: </label>
                                <input id="confirmacao" type="text" class="form-control" name="confirmacao" value="sim" readonly required>
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