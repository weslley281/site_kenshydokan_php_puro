<?php
$pageTitle = 'Campeonatos | Kenshydokan';
include_once __DIR__ . "/menu.php";

$campeonatos = (new Campeonato())->buscarTodos();

$nomesStatus = [
    'rascunho' => ['Em preparação', 'secondary'],
    'inscricoes_abertas' => ['Inscrições abertas', 'success'],
    'inscricoes_encerradas' => ['Inscrições encerradas', 'warning'],
    'pesagem' => ['Pesagem', 'warning'],
    'chaveamento' => ['Sorteio das lutas', 'info'],
    'em_andamento' => ['Acontecendo agora', 'danger'],
    'finalizado' => ['Encerrado', 'dark'],
    'cancelado' => ['Cancelado', 'secondary'],
];
?>

<div class="page-head">
    <div>
        <h1>Campeonatos</h1>
        <p>Escolha o campeonato com que vai trabalhar ou crie um novo.</p>
    </div>
    <a href="criar_campeonato.php" class="btn btn-danger btn-lg"><i class="fas fa-plus"></i> Novo campeonato</a>
</div>

<?php if (empty($campeonatos)): ?>
    <div class="card"><div class="empty">
        <i class="fas fa-trophy"></i>
        <h5>Nenhum campeonato ainda</h5>
        <p>Clique em <strong>Novo campeonato</strong> para começar.</p>
    </div></div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($campeonatos as $c):
            [$txtStatus, $corStatus] = $nomesStatus[$c['status']] ?? [$c['status'], 'secondary'];
            $selecionado = !empty($_SESSION['camp_atual']) && $_SESSION['camp_atual'] == $c['id_campeonato'];
        ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 <?= $selecionado ? 'border-danger border-2' : '' ?>">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-<?= $corStatus ?> align-self-start mb-2"><?= $txtStatus ?></span>
                        <h5 class="mb-2"><?= htmlspecialchars($c['titulo']) ?></h5>
                        <div class="text-muted mb-3">
                            <div><i class="far fa-calendar me-2"></i><?= date('d/m/Y', strtotime($c['data_inicio'])) ?></div>
                            <div><i class="fas fa-location-dot me-2"></i><?= htmlspecialchars($c['cidade']) ?> - <?= htmlspecialchars($c['estado']) ?></div>
                        </div>
                        <div class="mt-auto d-flex gap-2">
                            <a href="detalhes_campeonato.php?id=<?= $c['id_campeonato'] ?>" class="btn btn-danger flex-fill">
                                <?= $selecionado ? 'Continuar' : 'Abrir' ?> <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="editar_campeonato.php?id=<?= $c['id_campeonato'] ?>" class="btn btn-outline-secondary" title="Editar dados">
                                <i class="fas fa-pen"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include_once __DIR__ . "/rodape.php"; ?>
