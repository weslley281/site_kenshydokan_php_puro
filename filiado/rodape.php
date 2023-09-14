<footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Weslley Henrique Vieira Ferraz <?php echo date("Y"); ?></p>
      <br>
      <br>
      <p class="m-0 text-white">© Federação de Karatê de Contato do Estado de Mato Grosso <?php echo date("Y"); ?>. Todos os direitos reservados.</p>
      <hr class="bg-light">
      <p class="m-0 text-white">Desenvolvido por Weslley Henrique Vieira Ferraz<br>
      Tenha um site incrivel como esse faça um orçamento sem compromisso clicando aqui</p>
    </div>
    <!-- /.container -->
</footer>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom JavaScript for this theme -->
  <script src="../js/scrolling-nav.js"></script>
  <script type="text/javascript" src="../js/datatables.min.js"></script>
  <script type="text/javascript" src="../js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.bundle.min"></script>
  <script type="text/javascript" src="../js/datatables-demo.js"></script>
  <script type="text/javascript" src="../js/vue.js"></script>

  <script>
    $(document).ready(function(){
      $("#hide").click(function(){
        $("#esconder").hide();
      });
      $("#show").click(function(){
        $("#esconder").show();
      });
    });
  </script>

  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
      toolbar_mode: 'floating',
   });
  </script>