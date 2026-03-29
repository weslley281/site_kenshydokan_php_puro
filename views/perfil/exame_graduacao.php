<?php
$page_title = "Exame";
include __DIR__ . "/menu.php";
include __DIR__ . "/_perfil_auth.php";
?>

<body>
  <div class="container">

    <div class="row">

      <?php include __DIR__ . "/_perfil_menu.php"; ?>

      <!--dados do perfil -->
      <div class="col-lg-9">     
        <?php include __DIR__ . "/_perfil_info_card.php"; ?>

        <hr>
        <div class="text-center">
          <h1><strong>Inscrição de atletas para Exame de Graduação</strong></h1>
        </div>
        <div class="text-center">
          <form action="../../controllers/exame.php" method="post">
            <div class="form-group">
              <label for="nome">Nome do atleta que fará o exame:</label>
              <input id="nome" class="form-control mb-2" type="text" placeholder="nome" name="nome">
            </div>
            <div class="form-group">
              <label for="documento">CPF ou RG do atleta que fará o exame:</label>
              <input id="documento" class="form-control mb-2" type="text" placeholder="CPF ou RG" name="documento">
            </div>
            <div class="form-group">
              <label for="graduacao_atual">Graduação atual do atleta que fará o exame:</label>
              <select id="graduacao_atual" class="form-control mb-2" name="graduacao_atual">
                <?php
                include_once __DIR__ . "/../../db/conexao.php";
                $c = new Conexao();
                $conexao = $c->conectar();
                $consulta = "SELECT * FROM graduacoes ORDER BY id_graduacao ASC";
                $resultado = mysqli_query($conexao, $consulta);
                while ($dado = mysqli_fetch_array($resultado)) {
                    echo '<option value="' . $dado["id_graduacao"] . '">' . $dado["graduacao"] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="graduacao_pretendida">Graduação pretendida do atleta que fará o exame:</label>
              <select id="graduacao_pretendida" class="form-control mb-2" name="graduacao_pretendida">
                <?php
                mysqli_data_seek($resultado, 0);
                while ($dado = mysqli_fetch_array($resultado)) {
                    echo '<option value="' . $dado["id_graduacao"] . '">' . $dado["graduacao"] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="professor">Nome do seu Professor:</label>
              <input class="form-control mb-2" type="text" value="<?php echo $usuario["nome"]; ?>" name="professor" readonly>
            </div>
            <div class="form-group">
              <label for="email">Email do Professor:</label>
              <input class="form-control mb-2" type="email" value="<?php echo $usuario["email"]; ?>" name="email" readonly>
            </div>
            <div class="form-group">
              <input class="btn btn-success" type="submit" value="enviar" name="enviar">
            </div>
          </form>
        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->
  </div>

<?php
include __DIR__ . "/rodape.php";
?>