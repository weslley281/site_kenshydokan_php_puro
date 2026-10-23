<?php
$pageTitle = 'Certificados | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/certificadoModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$certificadoModel = new Certificado();
$atletas = $certificadoModel->buscarAtletasPorCampeonato($idCampeonato);
$encerrado = $campeonato['status'] === 'finalizado';
$certificadosEmitidos = count(array_filter($atletas, static function (array $atleta): bool {
    return !empty($atleta['id_certificado']);
}));
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="campeonatos.php" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Certificados</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-award text-success me-2"></i>Certificados
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Consulte os atletas e emita seus certificados com todas as colocações homologadas.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="validar_certificado.php" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-shield-alt me-1"></i> Validar certificado
        </a>
        <form action="../controllers/certificadoController.php" method="POST" style="display:inline;" onsubmit="return confirm('Gerar certificados para todos os atletas aprovados neste campeonato?');"><?= csrfCampo() ?>
            <input type="hidden" name="tipo" value="gerar_lote">
            <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
            <button type="submit" class="btn btn-success" <?= !$encerrado ? 'disabled title="Disponível após finalizar o campeonato"' : '' ?>>
                <i class="fas fa-file-invoice me-1"></i> Emitir todos
            </button>
        </form>
        <a href="detalhes_campeonato.php?id=<?= $idCampeonato ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 border-start border-4 border-success">
    <div class="card-body p-3">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-check-shield text-success me-1"></i> Código de validação</h6>
                <p class="text-muted small mb-0">
                    Cada atleta terá um certificado com seus resultados de todas as modalidades e um código de validação.
                    <?php if (!$encerrado): ?>
                        <strong> A emissão e a consulta no perfil ficam disponíveis após finalizar o campeonato.</strong>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-success fs-7">Certificados emitidos: <?= $certificadosEmitidos ?></span>
                <span class="badge bg-secondary fs-7">Atletas participantes: <?= count($atletas) ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-list-check text-primary me-2"></i>Atletas com inscrição aprovada (<?= count($atletas) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable-responsive">
                <thead class="table-light text-uppercase fs-7 text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 80px;">#</th>
                        <th>Atleta / Titular</th>
                        <th>Dojo</th>
                        <th>Categorias</th>
                        <th>Certificado</th>
                        <th class="text-end pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($atletas)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Nenhum atleta com inscrição aprovada neste campeonato.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($atletas as $idx => $atleta): ?>
                            <tr>
                                <td class="ps-3 text-muted fw-bold"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($atleta['nome_titular']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($atleta['dojo']) ?></span>
                                </td>
                                <td>
                                    <?= (int)$atleta['total_categorias'] ?>
                                </td>
                                <td>
                                    <?php if (!empty($atleta['id_certificado'])): ?>
                                        <span class="badge bg-success">Emitido</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pendente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <?php if ($encerrado): ?>
                                        <a href="../controllers/certificadoController.php?acao=gerar_pdf&<?= !empty($atleta['id_certificado']) ? 'id_certificado=' . (int)$atleta['id_certificado'] : 'id_inscricao=' . (int)$atleta['id_inscricao'] ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf me-1"></i> <?= !empty($atleta['id_certificado']) ? 'Baixar PDF' : 'Gerar PDF' ?>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Disponível após finalizar o campeonato">
                                            Após encerramento
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . "/rodape.php"; ?>
