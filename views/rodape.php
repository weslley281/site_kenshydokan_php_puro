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

<footer class="py-5 bg-dark mt-5">
    <div class="container text-center">
        <p class="m-0 text-white font-weight-bold">Instituto de Artes Marciais e Defesa Pessoal Kenshydokan</p>
        <p class="m-0 mb-3 text-white-50"><small>Copyright &copy; WKKA <?php echo date("Y"); ?>. Todos os direitos reservados.</small></p>
        
        <p class="m-0">
            <a href="politicas.php">Políticas de Privacidade</a> | 
            <a href="termos.php">Termos e Condições</a>
        </p>
        
        <hr class="bg-secondary my-4" style="opacity: 0.3;">
        
        <p class="m-0 text-white-50"><small>Desenvolvido por Weslley Henrique Vieira Ferraz<br>
            Faça um orçamento sem compromisso <a href="https://engenheirosoftwareweslley.com.br" target="_blank" class="text-white font-weight-bold">clicando aqui</a></small></p>
    </div>
</footer>
<!-- Vue.js -->
<script src="./../libs/vue.js"></script>

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
<!-- Select2 -->
<script src="../libs/select2/js/select2.min.js"></script>
<script src="../../libs/select2/js/select2.min.js"></script>
<script src="../../../libs/select2/js/select2.min.js"></script>
<script src="../libs/select2/js/i18n/pt-BR.js"></script>
<script src="../../libs/select2/js/i18n/pt-BR.js"></script>
<script src="../../../libs/select2/js/i18n/pt-BR.js"></script>

<script src="../libs/DataTables/datatables.js"></script>
<script src="../../libs/DataTables/datatables.js"></script>
<script src="../../../libs/DataTables/datatables.js"></script>

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
    // Função para comprimir e mostrar a pré-visualização da imagem
    function processAndPreviewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Apenas processa se for imagem
            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    // Limita as dimensões máximas
                    // Se for foto de galeria (name="foto"), permitimos uma resolução maior (ex: 1200px)
                    // Para perfil/logo (name="imagem"), 600px é suficiente
                    const isGallery = input.name === 'foto';
                    const MAX_WIDTH = isGallery ? 1200 : 600;
                    const MAX_HEIGHT = isGallery ? 1200 : 600;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Converte para JPEG com qualidade 0.75 (excelente relação qualidade/tamanho)
                    canvas.toBlob(function(blob) {
                        // Cria um novo arquivo a partir do blob comprimido
                        const extensao = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
                        const novoNome = file.name.substring(0, file.name.lastIndexOf('.')) + '_min' + (extensao === '.webp' ? '.webp' : '.jpg');
                        const novoFormato = extensao === '.webp' ? 'image/webp' : 'image/jpeg';
                        
                        const compressedFile = new File([blob], novoNome, {
                            type: novoFormato,
                            lastModified: Date.now()
                        });

                        // Substitui o arquivo no input usando DataTransfer para que o form envie a versão leve
                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(compressedFile);
                            input.files = dataTransfer.files;
                        } catch (err) {
                            console.error("Erro ao definir arquivos via DataTransfer:", err);
                        }

                        // Atualiza a pré-visualização na tela com o blob comprimido
                        const blobURL = URL.createObjectURL(blob);
                        $('#imagePreview').attr('src', blobURL);
                        $('#imagePreview').show();
                    }, file.type === 'image/webp' ? 'image/webp' : 'image/jpeg', 0.75);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Ouvinte de evento para o campo de entrada de arquivo
    $('#imagem').change(function() {
        processAndPreviewImage(this);
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