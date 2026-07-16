</div> <!-- Fechamento da div de padding py-4 do menu.php -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../libs/select2/js/select2.min.js"></script>
<script src="../../libs/select2/js/i18n/pt-BR.js"></script>
<!-- DataTables -->
<script src="../../libs/DataTables/datatables.js"></script>

<script>
// Funções globais de máscara de telefone
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

$(document).ready(function() {
    // Inicialização genérica para tabelas DataTables se houverem
    $('.js-datatable').each(function() {
        if (!$.fn.DataTable.isDataTable(this)) {
            $(this).DataTable({
                "pageLength": 10,
                "searching": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json"
                }
            });
        }
    });

    // Inicialização genérica do Select2 para filiados se a biblioteca estiver carregada
    if ($.fn.select2) {
        $('.js-example-basic-single').select2({
            placeholder: "Selecione...",
            allowClear: true
        });
    }
});
</script>
</body>
</html>
