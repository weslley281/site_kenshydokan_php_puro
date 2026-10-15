        </div><!-- /.content -->
        <footer class="text-center text-muted small py-3 border-top bg-white">
            &copy; <?= date('Y') ?> Federação Kenshydokan Jutsu &bull; Sistema de Campeonatos
        </footer>
    </div><!-- /.main -->
</div><!-- /.layout -->

<script src="../libs/bootstrap/jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../libs/alertifyjs/alertify.min.js"></script>
<script src="../libs/select2/js/select2.min.js"></script>
<script>
$(function () {
    if ($.fn.select2) {
        $('.select2, .select2-enable').select2({ width: '100%' });
        $('.select2-modal').each(function () {
            $(this).select2({ width: '100%', dropdownParent: $(this).closest('.modal') });
        });
    }
});
</script>
</body>
</html>
