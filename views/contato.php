<?php
$pageTitle = "Fale Conosco | Contato do Instituto Kenshydokan";
$pageDescription = "Entre em contato conosco para tirar dúvidas, propor parcerias ou agendar uma aula experimental de karatê e artes marciais.";
include "menu.php";
?>

<div class="container text-center mt-5">
  <form action="../controllers/enviar.php" method="post">
    <div class="text-danger mt-5">
      <h1><strong>Contato</strong></h1>
    </div>

    <!-- nome -->
    <div class="form-group mb-3">
      <label for="nome">Nome: </label>
      <input type="text" class="form-control form-control-lg" name="nome" id="nome">
    </div>

    <!-- email -->
    <div class="form-group mb-3">
      <label for="email">Email: </label>
      <input type="email" class="form-control form-control-lg" name="email" id="email">
    </div>

    <!-- Telefone -->
    <div class="form-group mb-3">
      <label for="telefone">Numero com DDD: </label>
      <input type="text" class="form-control form-control-lg" name="telefone" id="telefone">
    </div>

    <!-- Mensagem -->
    <div class="form-group mb-3">
      <label for="mensagem">Mensagem: </label>
      <textarea class="form-control form-control-lg" id="mensagem" name="mensagem" placeholder="Por Favor escreva a sua menssagem..." rows="10"></textarea>
    </div>
    <div class="form-group mb-3">
      <input class="btn btn-primary btn-lg" type="submit" name="enviar" value="enviar">
    </div>

  </form>
</div>

<?php include "rodape.php"; ?>