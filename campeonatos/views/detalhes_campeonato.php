<?php
$pageTitle = 'Etapas do campeonato | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/chaveModel.php";
include_once __DIR__ . "/../models/classificacaoModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado. <a href='inicio.php'>Voltar</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$id = $cid;

$categorias = (new Categoria())->buscarPorCampeonato($id);
$inscricoes = (new Inscricao())->buscarPorCampeonato($id);
$liberados = count(array_filter($inscricoes, fn($i) => $i['status_elegibilidade'] === 'aprovada'));

$chaveModel = new Chave();
$classModel = new Classificacao();
$comLutas = 0;
$comResultado = 0;
foreach ($categorias as $cat) {
    if ($chaveModel->buscarPorCategoria((int)$cat['id_categoria'])) $comLutas++;
    if (!empty($classModel->buscarPorCategoria((int)$cat['id_categoria']))) $comResultado++;
}
$totalCat = count($categorias);

$etapas = [
    ['Dados do campeonato', 'Nome, data e local.', true, "editar_campeonato.php?id=$id", 'Editar'],
    ['Categorias', $totalCat ? "$totalCat categoria(s) criada(s)." : 'Crie as categorias (ex.: Kumite Adulto até 70 kg).', $totalCat > 0, "categorias.php?id=$id", 'Abrir'],
    ['Atletas inscritos', count($inscricoes) ? count($inscricoes) . ' atleta(s) inscrito(s).' : 'Inscreva os atletas em cada categoria.', count($inscricoes) > 0, "inscricoes.php?id=$id", 'Abrir'],
    ['Pesagem', "$liberados de " . count($inscricoes) . ' atleta(s) liberado(s) para lutar.', count($inscricoes) > 0 && $liberados === count($inscricoes), "pesagem.php?id=$id", 'Abrir'],
    ['Lutas', $totalCat ? "Lutas sorteadas em $comLutas de $totalCat categoria(s)." : 'Sorteie quem luta contra quem.', $totalCat > 0 && $comLutas === $totalCat, "chaves.php?id=$id", 'Abrir'],
    ['Resultados', $totalCat ? "Resultado confirmado em $comResultado de $totalCat categoria(s)." : 'Confirme o pódio de cada categoria.', $totalCat > 0 && $comResultado === $totalCat, "resultados.php?id=$id", 'Abrir'],
];

$proxima = null;
foreach ($etapas as $i => $e) {
    if (!$e[2]) { $proxima = $i; break; }
}
if ($proxima === null && $etapas) {
    $proxima = count($etapas) - 1;
}
?>

<div class="page-head">
    <div>
        <h1><?= htmlspecialchars($campAtual['titulo']) ?></h1>
        <p><i class="far fa-calendar me-1"></i><?= date('d/m/Y', strtotime($campAtual['data_inicio'])) ?>
            &nbsp;&bull;&nbsp; <i class="fas fa-location-dot me-1"></i><?= htmlspecialchars($campAtual['cidade']) ?> - <?= htmlspecialchars($campAtual['estado']) ?>
            &nbsp;&bull;&nbsp; Status: <strong><?= htmlspecialchars(str_replace('_', ' ', ucfirst($campAtual['status']))) ?></strong></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= $etapas[$proxima][3] ?>" class="btn btn-danger btn-lg">Continuar <i class="fas fa-arrow-right"></i></a>
        <?php if ($campAtual['status'] === 'finalizado'): ?>
            <span class="btn btn-success btn-lg disabled" aria-disabled="true"><i class="fas fa-check me-1"></i> Campeonato encerrado</span>
        <?php elseif ($campAtual['status'] !== 'cancelado'): ?>
            <form method="POST" action="../controllers/campeonatoController.php" onsubmit="return confirm('Encerrar este campeonato? Depois disso, os certificados dos atletas ficarão disponíveis no site principal. Confirme que os resultados das categorias já foram homologados.');">
                <input type="hidden" name="tipo" value="encerrar_campeonato">
                <input type="hidden" name="id_campeonato" value="<?= $id ?>">
                <button type="submit" class="btn btn-outline-success btn-lg">
                    <i class="fas fa-flag-checkered me-1"></i> Encerrar campeonato
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="help">Siga as etapas na ordem. As etapas com <i class="fas fa-check text-success"></i> já estão prontas.</div>

<ul class="steps">
    <?php foreach ($etapas as $i => [$titulo, $desc, $feito, $link, $botao]):
        $classe = $feito ? 'done' : ($i === $proxima ? 'todo next' : 'todo');
    ?>
        <li class="<?= $classe ?>">
            <div class="icon"><?= $feito ? '<i class="fas fa-check" aria-label="Feito"></i>' : '<i class="far fa-hourglass" aria-label="Falta fazer"></i>' ?></div>
            <div class="txt">
                <strong><?= $i + 1 ?>. <?= $titulo ?></strong>
                <span><?= $desc ?></span>
            </div>
            <a href="<?= $link ?>" class="btn <?= $i === $proxima ? 'btn-danger' : 'btn-outline-secondary' ?>"><?= $botao ?></a>
        </li>
    <?php endforeach; ?>
</ul>

<?php include_once __DIR__ . "/rodape.php"; ?>
