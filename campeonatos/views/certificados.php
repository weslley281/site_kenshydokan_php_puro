<?php
$pageTitle = 'Certificados | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/certificadoModel.php";

$idCampeonato = (int)($_GET['id'] ?? 0);
$campeonatoModel = new Campeonato();
$campeonato = $campeonatoModel->buscarPorId($idCampeonato);

if (!$campeonato) {
    echo "<div class='alert alert-danger'>Campeonato não encontrado.</div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}

$inscricaoModel = new Inscricao();
$inscricoes = $inscricaoModel->buscarPorCampeonato($idCampeonato);

$certificadoModel = new Certificado();
$certificados = $certificadoModel->buscarPorCampeonato($idCampeonato);
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
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?> &bull; Gere ou consulte certificados.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="validar_certificado.php" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-shield-alt me-1"></i> Validar certificado
        </a>
        <form action="../controllers/certificadoController.php" method="POST" style="display:inline;" onsubmit="return confirm('Gerar certificados para todos os atletas aprovados neste campeonato?');">
            <input type="hidden" name="tipo" value="gerar_lote">
            <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-file-invoice me-1"></i> Gerar certificados
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
                    Cada certificado tem um código único que pode ser usado para confirmar se o documento é válido.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                <span class="badge bg-success fs-7">Certificados Emitidos: <?= count($certificados) ?></span>
                <span class="badge bg-secondary fs-7">Atletas Inscritos: <?= count($inscricoes) ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-list-check text-primary me-2"></i>Certificados emitidos (<?= count($certificados) ?>)
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
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Código de validação</th>
                        <th>Data Emissão</th>
                        <th class="text-end pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($certificados)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                Nenhum certificado emitido neste campeonato.
                                <br>
                                <span class="small">Clique em <strong>Gerar certificados</strong> para emitir os documentos dos atletas aprovados.</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($certificados as $idx => $c): ?>
                            <tr>
                                <td class="ps-3 text-muted fw-bold"><?= $idx + 1 ?></td>
                                <td>
                                    <strong class="text-dark"><?= htmlspecialchars($c['nome_titular']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($c['dojo']) ?></span>
                                </td>
                                <td class="small text-muted">
                                    <?= htmlspecialchars($c['categoria_nome']) ?>
                                </td>
                                <td>
                                    <?php if ($c['tipo_certificado'] === 'participacao'): ?>
                                        <span class="badge bg-info text-dark">PARTICIPAÇÃO</span>
                                    <?php elseif ($c['tipo_certificado'] === 'podio'): ?>
                                        <span class="badge bg-warning text-dark">PÓDIO</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= strtoupper(htmlspecialchars($c['tipo_certificado'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code class="px-2 py-1 bg-light border rounded text-danger fw-bold">
                                        <?= htmlspecialchars($c['codigo_autenticidade']) ?>
                                    </code>
                                </td>
                                <td class="small text-muted">
                                    <?= date('d/m/Y H:i', strtotime($c['data_emissao'])) ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="../controllers/certificadoController.php?acao=gerar_pdf&id_inscricao=<?= $c['id_inscricao'] ?>&tipo=<?= $c['tipo_certificado'] ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Visualizar / Imprimir PDF">
                                        <i class="fas fa-file-pdf me-1"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
            <i class="fas fa-user-plus text-primary me-2"></i>Emitir Certificado Individual
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Selecione o Atleta</label>
                <select id="selectAtletaIndividual" class="form-select select2">
                    <option value="">-- Selecione o atleta participante --</option>
                    <?php foreach ($inscricoes as $ins): ?>
                        <option value="<?= $ins['id_inscricao'] ?>">
                            <?= htmlspecialchars($ins['nome_atleta_snapshot']) ?> (<?= htmlspecialchars($ins['dojo_snapshot']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Tipo de Certificado</label>
                <select id="selectTipoCertificado" class="form-select">
                    <option value="participacao">Certificado de Participação Oficial</option>
                    <option value="podio">Certificado de Premiação e Pódio</option>
                    <option value="arbitragem">Certificado de Arbitragem</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" id="btnEmitirIndividual" class="btn btn-primary w-100 fw-bold">
                    <i class="fas fa-print me-1"></i> Emitir PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btnEmitirIndividual').addEventListener('click', function() {
    const idInscricao = document.getElementById('selectAtletaIndividual').value;
    const tipo = document.getElementById('selectTipoCertificado').value;

    if (!idInscricao) {
        alertify.error('Selecione um atleta para emitir o certificado.');
        return;
    }

    const url = `../controllers/certificadoController.php?acao=gerar_pdf&id_inscricao=${idInscricao}&tipo=${tipo}`;
    window.open(url, '_blank');
});
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
