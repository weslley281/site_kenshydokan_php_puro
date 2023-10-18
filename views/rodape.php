    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Weslley Henrique Vieira Ferraz <?php echo date("Y"); ?></p>
            <br>
            <br>
            <p class="m-0 text-white">© Federação de Karatê de Contato do Estado de Mato Grosso <?php echo date("Y"); ?>. Todos os direitos reservados.</p>
            <hr class="bg-light">
            <p class="m-0 text-white">Desenvolvido por Weslley Henrique Vieira Ferraz<br>
            Tenha um site incrivel como esse faça um orçamento sem compromisso <a href="https://api.whatsapp.com/send/?phone=5565999157130">clicando aqui</a></p>
        </div>
    </footer>
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
      });
    </script>

    <!-- Scripts de Telefone -->
	<script type="text/javascript">
		function mask(o, f) {
			setTimeout(function() {
				var v = mphone(o.value);
				if (v != o.value) {
					o.value = v;
				}
			}, 1);
		}

		function mphone(v) {
			var r = v.replace(/\D/g, "");
			r = r.replace(/^0/, "");
			if (r.length > 10) {
				r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
			} else if (r.length > 5) {
				r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
			} else if (r.length > 2) {
				r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
			} else {
				r = r.replace(/^(\d*)/, "($1");
			}
			return r;
		}
	</script>

    <script>
      $(document).ready(function() {
        $('.js-example-basic-single').select2();
      });
    </script>

    <script type="text/javascript">
    // Função para mostrar a pré-visualização da imagem
    function showImagePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreview').show();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Adicione um ouvinte de evento para o campo de entrada de arquivo
    $('#imagem').change(function() {
        showImagePreview(this);
    });
</script>

  <!-- Video JS -->
  <script src="https://vjs.zencdn.net/8.6.0/video.min.js"></script>
  </body>
</html>