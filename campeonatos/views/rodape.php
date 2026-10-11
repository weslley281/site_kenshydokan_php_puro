</div><!-- /.container-fluid -->

<footer class="bg-dark text-white py-4 mt-auto" style="border-top: 3px solid #d9232d; background: #0f172a !important;">
    <div class="container-fluid px-lg-4 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center small text-white-50">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-torii-gate text-danger fa-lg"></i>
            <div>
                &copy; <?= date('Y') ?> <strong class="text-white">Federação Kenshydokan Jutsu</strong> &bull; Sistema Oficial de Gestão de Campeonatos e Súmulas.
            </div>
        </div>
        <div class="mt-2 mt-sm-0 text-white-50">
            <span>Versão 2.0 &bull; Subdomínio Oficial: <strong class="text-danger">campeonatos.kenshydokan.org.br</strong></span>
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
        $('.select2-enable, .select2').select2({
            width: '100%'
        });
    }
});
</script>
</body>
</html>
