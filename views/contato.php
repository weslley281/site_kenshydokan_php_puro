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
    <div class="form-group mb-3 text-left">
      <label for="nome" class="text-secondary small font-weight-bold text-uppercase">Nome <span class="text-danger">*</span></label>
      <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="nome" id="nome" required>
    </div>

    <!-- email -->
    <div class="form-group mb-3 text-left">
      <label for="email" class="text-secondary small font-weight-bold text-uppercase">E-mail <span class="text-danger">*</span></label>
      <input type="email" class="form-control form-control-lg bg-light border-0 shadow-sm" name="email" id="email" required>
    </div>

    <!-- Telefone -->
    <div class="form-group mb-3 text-left">
      <label for="telefone" class="text-secondary small font-weight-bold text-uppercase">Telefone com DDD</label>
      <input type="text" class="form-control form-control-lg bg-light border-0 shadow-sm" name="telefone" id="telefone" placeholder="Ex: (65) 99999-9999">
    </div>

    <!-- Mensagem -->
    <div class="form-group mb-3 text-left">
      <label for="mensagem" class="text-secondary small font-weight-bold text-uppercase">Mensagem <span class="text-danger">*</span></label>
      <textarea class="form-control form-control-lg bg-light border-0 shadow-sm" id="mensagem" name="mensagem" placeholder="Por favor escreva a sua mensagem..." rows="8" required></textarea>
    </div>
    <div class="form-group mb-3">
      <input class="btn btn-primary btn-lg" type="submit" name="enviar" value="enviar">
    </div>

  </form>
</div>

<?php include "rodape.php"; ?>