<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/campeonatoModel.php";

$nomeUsuario = $_SESSION['nome'] ?? 'Visitante';
$paginaAtual = basename($_SERVER['PHP_SELF']);

// Páginas em que o ?id= é o id do campeonato: guardamos como "campeonato atual"
$paginasDoCampeonato = [
    'detalhes_campeonato.php', 'editar_campeonato.php', 'categorias.php', 'inscricoes.php',
    'criar_inscricao.php', 'pesagem.php', 'chaves.php', 'resultados.php',
    'tatames.php', 'certificados.php', 'auditoria.php', 'mais_opcoes.php'
];
if (in_array($paginaAtual, $paginasDoCampeonato) && !empty($_GET['id'])) {
    $_SESSION['camp_atual'] = (int)$_GET['id'];
}
// Páginas que recebem o id da categoria podem informar o campeonato por $campAtualId antes de incluir o menu
if (!empty($campAtualId)) {
    $_SESSION['camp_atual'] = (int)$campAtualId;
}

$campAtual = null;
if (!empty($_SESSION['camp_atual'])) {
    $campAtual = (new Campeonato())->buscarPorId((int)$_SESSION['camp_atual']);
    if (!$campAtual) {
        unset($_SESSION['camp_atual']);
    }
}
$cid = $campAtual ? (int)$campAtual['id_campeonato'] : 0;

// Itens do menu na ordem do dia do evento
$itensMenu = [
    ['num' => 1, 'icone' => 'fa-trophy',       'texto' => 'Campeonatos',       'link' => 'inicio.php',              'paginas' => ['inicio.php', 'campeonatos.php', 'criar_campeonato.php', 'editar_campeonato.php', 'detalhes_campeonato.php'], 'precisaCamp' => false],
    ['num' => 2, 'icone' => 'fa-layer-group',  'texto' => 'Categorias',        'link' => "categorias.php?id=$cid",  'paginas' => ['categorias.php'], 'precisaCamp' => true],
    ['num' => 3, 'icone' => 'fa-user-plus',    'texto' => 'Atletas inscritos', 'link' => "inscricoes.php?id=$cid",  'paginas' => ['inscricoes.php', 'criar_inscricao.php'], 'precisaCamp' => true],
    ['num' => 4, 'icone' => 'fa-weight-scale', 'texto' => 'Pesagem',           'link' => "pesagem.php?id=$cid",     'paginas' => ['pesagem.php'], 'precisaCamp' => true],
    ['num' => 5, 'icone' => 'fa-hand-fist',    'texto' => 'Lutas',             'link' => "chaves.php?id=$cid",      'paginas' => ['chaves.php', 'ver_chave.php', 'sumula_kata.php', 'sumula_tameshiwari.php', 'tatames.php', 'painel_tatame.php'], 'precisaCamp' => true],
    ['num' => 6, 'icone' => 'fa-medal',        'texto' => 'Resultados',        'link' => "resultados.php?id=$cid",  'paginas' => ['resultados.php'], 'precisaCamp' => true],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Campeonatos | Kenshydokan') ?></title>
    <link rel="icon" href="../img/wkka.jpg" type="image/jpg">
    <link rel="stylesheet" href="../libs/fontawesome-free-6.5.2-web/css/all.min.css">
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/alertify.min.css">
    <link rel="stylesheet" href="../libs/alertifyjs/css/themes/bootstrap.min.css">
    <link rel="stylesheet" href="../libs/select2/css/select2.min.css">
    <link rel="stylesheet" href="../css/custom.css?v=3">
</head>
<body>
<div class="layout">

    <aside class="sidebar" id="sidebar">
        <a class="sidebar-brand" href="inicio.php">
            <img src="../img/wkka.jpg" alt="Kenshydokan">
            <span>Kenshydokan<small>CAMPEONATOS</small></span>
        </a>

        <div class="camp-atual">
            <div class="label">Campeonato atual</div>
            <?php if ($campAtual): ?>
                <a href="detalhes_campeonato.php?id=<?= $cid ?>" title="Ver etapas deste campeonato">
                    <?= htmlspecialchars($campAtual['titulo']) ?>
                </a>
                <a href="inicio.php" class="small fw-normal text-secondary mt-1">Trocar campeonato</a>
            <?php else: ?>
                <a href="inicio.php" class="text-warning">Escolha um campeonato</a>
            <?php endif; ?>
        </div>

        <ul class="side-nav">
            <?php foreach ($itensMenu as $item):
                $ativo = in_array($paginaAtual, $item['paginas']);
                $bloqueado = $item['precisaCamp'] && !$campAtual;
            ?>
                <li>
                    <a href="<?= $item['link'] ?>" class="<?= $ativo ? 'active' : '' ?> <?= $bloqueado ? 'disabled' : '' ?>">
                        <span class="num"><?= $item['num'] ?></span>
                        <i class="fas <?= $item['icone'] ?>"></i>
                        <?= $item['texto'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="sidebar-footer">
            <?php if ($campAtual): ?>
                <a href="mais_opcoes.php?id=<?= $cid ?>"><i class="fas fa-ellipsis me-2"></i>Mais opções</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['id_usuario'])): ?>
                <a href="../controllers/sair.php"><i class="fas fa-right-from-bracket me-2"></i>Sair (<?= htmlspecialchars($nomeUsuario) ?>)</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-right-to-bracket me-2"></i>Entrar</a>
            <?php endif; ?>
        </div>
    </aside>

    <div class="main">
        <div class="topbar-mobile">
            <button type="button" onclick="document.getElementById('sidebar').classList.toggle('open')" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>
            <strong>Kenshydokan Campeonatos</strong>
            <span></span>
        </div>
        <div class="content">
