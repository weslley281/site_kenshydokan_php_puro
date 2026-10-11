<div class="row justify-content-center py-5">
    <div class="col-md-6 text-center">
        <div class="card-kenshy p-5">
            <h1 class="display-1 text-warning fw-bold">403</h1>
            <h3 class="text-white mb-3">Acesso Não Autorizado</h3>
            <p class="text-muted mb-4">
                <?= htmlspecialchars($message ?? 'Você não possui permissões suficientes para acessar este módulo.') ?>
            </p>
            <a href="<?= \App\Core\Router::getBasePath() ?>/" class="btn btn-kenshy">
                <i class="fa-solid fa-house me-2"></i> Voltar ao Painel
            </a>
        </div>
    </div>
</div>
