<?php
$pageTitle = 'Atletas inscritos | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = $cid;
$idCategoriaFiltro = !empty($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : null;

$categorias = (new Categoria())->buscarPorCampeonato($idCampeonato);
$inscricoes = (new Inscricao())->buscarPorCampeonato($idCampeonato, $idCategoriaFiltro);

$situacao = [
    'aprovada'  => ['Liberado', 'bg-success'],
    'pendente'  => ['Falta pesar', 'bg-warning text-dark'],
    'reprovada' => ['Não liberado', 'bg-danger'],
];
?>

<div class="page-head">
    <div>
        <h1>3. Atletas inscritos</h1>
        <p>Inscreva os atletas filiados nas categorias. Basta digitar o nome.</p>
    </div>
    <a href="criar_inscricao.php?id=<?= $idCampeonato ?>" class="btn btn-danger btn-lg"><i class="fas fa-user-plus"></i> Inscrever atleta</a>
</div>

<div class="card">
    <div class="card-header py-3 d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <span><?= count($inscricoes) ?> atleta(s)</span>
        <form method="GET" action="inscricoes.php" class="d-flex gap-2" style="min-width:280px">
            <input type="hidden" name="id" value="<?= $idCampeonato ?>">
            <select name="categoria_id" class="form-select" onchange="this.form.submit()" aria-label="Filtrar por categoria">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id_categoria'] ?>" <?= $idCategoriaFiltro == $cat['id_categoria'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (empty($inscricoes)): ?>
        <div class="empty"><i class="fas fa-user-plus"></i>Nenhum atleta inscrito ainda. Clique em <strong>Inscrever atleta</strong>.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead><tr><th>Atleta</th><th>Categoria</th><th>Situação</th><th class="text-end">Alterar</th></tr></thead>
                <tbody>
                <?php foreach ($inscricoes as $ins):
                    $s = $situacao[$ins['status_elegibilidade']] ?? ['—', 'bg-secondary'];
                ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($ins['nome_atleta_snapshot']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($ins['dojo_snapshot'] ?: 'Sem dojo') ?> · <?= (int)$ins['idade_evento'] ?> anos</small>
                        </td>
                        <td><?= htmlspecialchars($ins['categoria_nome']) ?></td>
                        <td><span class="badge <?= $s[1] ?> fs-6"><?= $s[0] ?></span></td>
                        <td class="text-end">
                            <form method="POST" action="../controllers/inscricaoController.php" class="d-inline-flex gap-1"><?= csrfCampo() ?>
                                <input type="hidden" name="tipo" value="atualizar_status">
                                <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
                                <input type="hidden" name="id_inscricao" value="<?= $ins['id_inscricao'] ?>">
                                <input type="hidden" name="motivo" value="Alterado na lista de atletas">
                                <?php if ($ins['status_elegibilidade'] !== 'aprovada'): ?>
                                    <button type="submit" name="status" value="aprovada" class="btn btn-sm btn-outline-success" title="Liberar"><i class="fas fa-check"></i></button>
                                <?php endif; ?>
                                <?php if ($ins['status_elegibilidade'] !== 'reprovada'): ?>
                                    <button type="submit" name="status" value="reprovada" class="btn btn-sm btn-outline-danger" title="Não liberar" onclick="return confirm('Marcar este atleta como NÃO liberado?');"><i class="fas fa-ban"></i></button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="text-end mt-3">
    <a href="pesagem.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary">Próxima etapa: Pesagem <i class="fas fa-arrow-right"></i></a>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
