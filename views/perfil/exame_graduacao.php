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
              <label for="nome">RG do atleta que fará o exame:</label>
              <input class="form-control mb-2" type="text" placeholder="rg" name="rg">
            </div>
            <div class="form-group">
              <label for="nome">Graduação atual do atleta que fará o exame:</label>
              <input class="form-control mb-2" type="text" placeholder="graduacao atual" name="graduacao_atual">
            </div>
            <div class="form-group">
              <label for="nome">Graduação pretendida do atleta que fará o exame:</label>
              <input class="form-control mb-2" type="text" placeholder="graduacao pretendida" name="graduacao_pretendida">
            </div>
            <div class="form-group">
              <label for="professor">Nome do seu Professor:</label>
              <input class="form-control mb-2" type="text" value="<?php echo $usuario["nome"]; ?>" name="professor" readonly>
            </div>
            <div class="form-group">
              <label for="nome">Email do atleta que fará o exame:</label>
              <input class="form-control mb-2" type="email" placeholder="Email" name="email">
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
include __DIR__ . "/../rodape.php";
?>