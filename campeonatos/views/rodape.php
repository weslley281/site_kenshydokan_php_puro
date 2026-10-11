</div><!-- /.container-fluid -->

<footer class="bg-white border-top py-3 mt-auto">
    <div class="container-fluid px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center small text-muted">
        <div>
            &copy; <?= date('Y') ?> <strong>Federação Kenshydokan Jutsu</strong> &bull; Sistema Oficial de Gestão de Campeonatos e Súmulas.
        </div>
        <div class="mt-2 mt-sm-0">
            <span>Versão 2.0 (PHP Puro) &bull; Subdomínio Oficial: campeonatos.kenshydokan.org.br</span>
        </div>
    </div>
</footer>

<!-- Scripts do Sistema -->
<script src="../libs/bootstrap/js/jquery.min.js"></script>
<script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../libs/alertifyjs/alertify.min.js"></script>
<script src="../libs/select2/js/select2.min.js"></script>
<script src="../libs/DataTables/datatables.min.js"></script>

<script>
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('.datatable-responsive').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            responsive: true,
            pageLength: 25
        });
    }

    if ($.fn.select2) {
        $('.select2-enable').select2({
            width: '100%'
        });
    }
});
</script>
</body>
</html>
