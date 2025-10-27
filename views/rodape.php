<?php
// Verifique se o cookie "cookie_accepted" não foi definido
if (!isset($_COOKIE['cookie_accepted'])) {
    // Se o cookie não foi definido, exiba a mensagem de uso de cookies
    echo '<div class="alert alert-info mb-0 text-center" role="alert" id="cookie-message">
        Este site utiliza cookies para garantir a melhor experiência. <a href="#" id="accept-cookie" class="alert-link">Aceitar</a>
    </div>';
}

// Verifique se o usuário clicou em "Aceitar" na mensagem de uso de cookies
if (isset($_POST['accept_cookie'])) {
    // Defina o cookie "cookie_accepted" com um valor para indicar que o usuário aceitou os cookies
    setcookie('cookie_accepted', 'yes', time() + 365 * 24 * 60 * 60, '/');
}
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Quando a página é carregada, verifique se o cookie "cookie_accepted" foi definido
        if (document.cookie.indexOf("cookie_accepted=yes") === -1) {
            // Se não foi definido, mostre a mensagem de uso de cookies
            document.getElementById("cookie-message").style.display = "block";
        }

        // Quando o usuário clica em "Aceitar", defina o cookie e esconda a mensagem
        document.getElementById("accept-cookie").addEventListener("click", function() {
            document.cookie = "cookie_accepted=yes; expires=" + new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
            document.getElementById("cookie-message").style.display = "none";
        });
    });
</script>

<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; WKKA <?php echo date("Y"); ?></p>
        <br>
        <br>
        <p class="m-0 text-white">© World Kenshydokan Karate Association <?php echo date("Y"); ?>. Todos os direitos reservados.</p>
        <p class="m-0 text-white"><a class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="politicas.php">Politicas e Privácidade</a> | <a class="link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="termos.php">Termos e Condições</a></p>
        <hr class="bg-light">
        <p class="m-0 text-white">Desenvolvido por Weslley Henrique Vieira Ferraz<br>
            Tenha um site incrivel como esse faça um orçamento sem compromisso <a href="https://engenheirosoftwareweslley.com.br" target="_blank">clicando aqui</a></p>
    </div>
</footer>
<!-- Vue.js -->
<script src="/libs/vue.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('perfil-info-app')) {
            const app = Vue.createApp({
                data() {
                    return {
                        infoVisible: false
                    }
                },
                computed: {
                    buttonText() {
                        return this.infoVisible ? 'Esconder Informações' : 'Mostrar Informações';
                    }
                }
            });
            app.mount('#perfil-info-app');
        }
    });
</script>


<!-- JavaScript (Opcional) -->
<!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="/libs/DataTables/datatables.js"></script>

<script src="https://vjs.zencdn.net/7.11.4/video.js"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        language: 'pt_BR',
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#minhaTabela').DataTable({
            "order": [
                [0, "asc"]
            ], // Ordena a primeira coluna em ordem crescente
            "pageLength": 10, // Define o número de registros por página
            "searching": true // Habilita a pesquisa
        });

        $('#minhaTabela2').DataTable({
            "order": [
                [0, "asc"]
            ], // Ordena a primeira coluna em ordem crescente
            "pageLength": 10, // Define o número de registros por página
            "searching": true // Habilita a pesquisa
        });

        $('#minhaTabela3').DataTable({
            "order": [
                [0, "asc"]
            ], // Ordena a primeira coluna em ordem crescente
            "pageLength": 10, // Define o número de registros por página
            "searching": true // Habilita a pesquisa
        });

        $('#minhaTabela4').DataTable({
            "order": [
                [0, "asc"]
            ], // Ordena a primeira coluna em ordem crescente
            "pageLength": 10, // Define o número de registros por página
            "searching": true // Habilita a pesquisa
        });
    });
</script>

<!-- Video JS -->
<script type="text/javascript" src="https://vjs.zencdn.net/8.6.0/video.min.js"></script>
<script src="https://kit.fontawesome.com/e880bf5077.js" crossorigin="anonymous"></script>
</body>

</html>