<?php
$pageTitle = 'Mais opções | Kenshydokan';
include_once __DIR__ . "/menu.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
?>

<div class="page-head">
    <div>
        <h1>Mais opções</h1>
        <p>Ferramentas extras do campeonato atual.</p>
    </div>
    <a href="detalhes_campeonato.php?id=<?= $cid ?>" class="btn btn-danger btn-lg">Voltar às etapas</a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <a href="auditoria.php?id=<?= $cid ?>" class="card h-100 text-decoration-none text-dark">
            <div class="card-body">
                <h2 class="h5"><i class="fas fa-clock-rotate-left text-danger me-2"></i>Histórico de alterações</h2>
                <p class="text-muted mb-0">Consulte as mudanças feitas neste campeonato.</p>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="certificados.php?id=<?= $cid ?>" class="card h-100 text-decoration-none text-dark">
            <div class="card-body">
                <h2 class="h5"><i class="fas fa-award text-danger me-2"></i>Certificados</h2>
                <p class="text-muted mb-0">Emita e consulte os certificados dos atletas.</p>
            </div>
        </a>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
