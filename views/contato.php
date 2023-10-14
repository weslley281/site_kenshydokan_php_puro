<!-- Navigation -->
  <?php include "menu.php";?>
<!-- /Navigation -->

 <div class="container text-center mt-5">
        <form action="../controllers/enviar.php" method="post">
            <div class="text-danger mt-5"><h1><b>Contato</b></h1></div>
            <div class="row text-center mt-5">
              <!-- nome -->
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default">Nome</span>
                </div>
                <input type="text" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="name">
              </div>

              <!-- email -->
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default">E-mail</span>
                </div>
                <input type="email" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="email">
              </div>

              <!-- Telefone -->
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default">Numero com DDD</span>
                </div>
                <input type="number" class="form-control" aria-label="Exemplo do tamanho do input" aria-describedby="inputGroup-sizing-default" name="phone">
              </div>

              <!-- Mensagem -->
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroup-sizing-default">Mensagem</span>
                </div>
                <textarea class="form-control" id="message" name="message" placeholder="Por Favor escreva a sua menssagem..." rows="5"></textarea>
              </div>
              <div class="input-group mb-3">
                <input class="btn btn-primary btn-lg" type="submit" name="enviar" value="enviar">
              </div>
            </div>
        </form>
    </div>

<!-- Footer -->
  <?php
include "rodape.php";
?>
<!-- /Footer -->