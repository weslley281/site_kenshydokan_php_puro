<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1 small">
                <li class="breadcrumb-item"><a href="/campeonatos" class="text-decoration-none">Campeonatos</a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>" class="text-decoration-none"><?= htmlspecialchars($campeonato['titulo']) ?></a></li>
                <li class="breadcrumb-item"><a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/inscricoes" class="text-decoration-none">Inscrições</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nova Inscrição</li>
            </ol>
        </nav>
        <h2 class="h3 fw-bold mb-0 text-dark">
            <i class="fas fa-user-plus text-danger me-2"></i>Nova Inscrição de Atleta
        </h2>
        <p class="text-muted small mb-0"><?= htmlspecialchars($campeonato['titulo']) ?></p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/inscricoes" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Voltar à Lista
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt text-danger fa-lg me-2"></i>
                    <h5 class="card-title mb-0 fs-6 fw-bold">Formulário de Inscrição Oficial com Snapshot Imutável</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                    <i class="fas fa-info-circle fa-2x text-info me-3"></i>
                    <div class="small">
                        <strong>Integração com Cadastro de Filiados da Kenshydokan:</strong>
                        Ao selecionar um atleta filiado, os dados cadastrais (Nome, Dojo, Graduação, Data de Nascimento) serão lidos da base principal e gravados como um <strong>snapshot imutável</strong> para fins de histórico esportivo e certificados oficiais.
                    </div>
                </div>

                <form method="POST" action="/inscricoes" id="formInscricao">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                    <input type="hidden" name="id_campeonato" value="<?= $campeonato['id_campeonato'] ?>">
                    <input type="hidden" id="data_ref_campeonato" value="<?= $campeonato['data_referencia_idade'] ?>">

                    <div class="row g-3">
                        <!-- Busca de Filiado -->
                        <div class="col-12">
                            <label for="selectFiliado" class="form-label fw-bold">
                                Atleta Filiado <span class="text-danger">*</span>
                                <span class="text-muted small fw-normal">(Digite o nome do atleta para pesquisar)</span>
                            </label>
                            <select id="selectFiliado" name="id_filiado" class="form-select" required>
                                <option value="">Digite o nome do atleta para pesquisar na base...</option>
                            </select>
                        </div>

                        <!-- Card de Dados do Filiado Selecionado (Live Preview) -->
                        <div class="col-12" id="previewFiliado" style="display: none;">
                            <div class="card bg-light border p-3">
                                <h6 class="fw-bold text-dark mb-2 border-bottom pb-2">
                                    <i class="fas fa-id-badge text-primary me-2"></i>Dados Carregados do Filiado
                                </h6>
                                <div class="row g-2 small">
                                    <div class="col-md-4">
                                        <span class="text-muted">Nome:</span> <strong id="previewNome" class="text-dark"></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Dojo / Academia:</span> <strong id="previewDojo" class="text-dark"></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Graduação Atual:</span> <strong id="previewGraduacao" class="text-danger"></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Data Nasc.:</span> <strong id="previewNascimento" class="text-dark"></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Idade Calculada no Evento:</span> <strong id="previewIdade" class="text-success fs-6"></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-muted">Ref. Idade:</span> <?= date('d/m/Y', strtotime($campeonato['data_referencia_idade'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sexo -->
                        <div class="col-md-4">
                            <label for="sexo" class="form-label fw-bold">Sexo / Gênero de Disputa <span class="text-danger">*</span></label>
                            <select class="form-select" id="sexo" name="sexo" required onchange="validarCompatibilidade()">
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                        </div>

                        <!-- Peso Declarado -->
                        <div class="col-md-4">
                            <label for="peso_declarado" class="form-label fw-bold">Peso Declarado (kg)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="10" max="250" class="form-control" id="peso_declarado" name="peso_declarado" placeholder="Ex: 75.5">
                                <span class="input-group-text">kg</span>
                            </div>
                            <small class="text-muted">Será checado na balança oficial.</small>
                        </div>

                        <!-- Categoria -->
                        <div class="col-md-4">
                            <label for="id_categoria" class="form-label fw-bold">Categoria de Disputa <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required onchange="validarCompatibilidade()">
                                <option value="">-- Selecione a Categoria --</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"
                                        data-modalidade="<?= $cat['modalidade'] ?>"
                                        data-idade-min="<?= $cat['idade_min'] ?>"
                                        data-idade-max="<?= $cat['idade_max'] ?>"
                                        data-sexo="<?= $cat['sexo'] ?>"
                                        data-peso-min="<?= $cat['peso_min'] ?>"
                                        data-peso-max="<?= $cat['peso_max'] ?>"
                                        data-exige-pesagem="<?= $cat['exige_pesagem'] ?>">
                                        [<?= strtoupper($cat['modalidade']) ?>] <?= htmlspecialchars($cat['nome']) ?> (<?= $cat['idade_min'] ?> a <?= $cat['idade_max'] ?> anos<?= $cat['sexo'] !== 'misto' ? ' - ' . ($cat['sexo'] === 'M' ? 'Masc' : 'Fem') : ' - Misto' ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Alerta Dinâmico de Validação de Idade/Sexo -->
                        <div class="col-12" id="alertaIncompativel" style="display: none;">
                            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-0">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                                <div>
                                    <strong>Atenção às regras de enquadramento:</strong>
                                    <span id="textoIncompatibilidade"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4 pt-3 border-top">
                            <a href="/campeonatos/<?= $campeonato['id_campeonato'] ?>/inscricoes" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" id="btnSalvar" class="btn btn-danger px-4">
                                <i class="fas fa-check me-1"></i> Confirmar Inscrição
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var atletaIdadeCalculada = null;

$(document).ready(function() {
    $('#selectFiliado').select2({
        theme: 'bootstrap-5',
        placeholder: 'Digite ao menos 2 letras do nome do atleta...',
        minimumInputLength: 2,
        ajax: {
            url: '/api/filiados/busca',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        var data = e.params.data;
        document.getElementById('previewNome').textContent = data.nome;
        document.getElementById('previewDojo').textContent = data.dojo || 'Independente';
        document.getElementById('previewGraduacao').textContent = data.graduacao_nome || 'Não informada';
        document.getElementById('previewNascimento').textContent = data.dataNascimentoFormatada;

        // Calcula a idade do atleta com base na data_referencia_idade do campeonato
        if (data.dataNascimento && data.dataNascimento !== '0000-00-00') {
            var dataNasc = new Date(data.dataNascimento);
            var ref = new Date(document.getElementById('data_ref_campeonato').value);
            var idade = ref.getFullYear() - dataNasc.getFullYear();
            var m = ref.getMonth() - dataNasc.getMonth();
            if (m < 0 || (m === 0 && ref.getDate() < dataNasc.getDate())) {
                idade--;
            }
            atletaIdadeCalculada = idade;
            document.getElementById('previewIdade').textContent = idade + ' anos';
        } else {
            atletaIdadeCalculada = null;
            document.getElementById('previewIdade').textContent = 'Data de nascimento não cadastrada!';
        }

        document.getElementById('previewFiliado').style.display = 'block';
        validarCompatibilidade();
    });
});

function validarCompatibilidade() {
    var catSelect = document.getElementById('id_categoria');
    var selectedOption = catSelect.options[catSelect.selectedIndex];
    var sexoAtleta = document.getElementById('sexo').value;
    var alerta = document.getElementById('alertaIncompativel');
    var texto = document.getElementById('textoIncompatibilidade');

    if (!selectedOption || !selectedOption.value) {
        alerta.style.display = 'none';
        return;
    }

    var catSexo = selectedOption.getAttribute('data-sexo');
    var catIdadeMin = parseInt(selectedOption.getAttribute('data-idade-min'));
    var catIdadeMax = parseInt(selectedOption.getAttribute('data-idade-max'));

    var erros = [];

    // Checa sexo
    if (catSexo !== 'misto' && catSexo !== sexoAtleta) {
        erros.push('O sexo selecionado (' + (sexoAtleta === 'M' ? 'Masculino' : 'Feminino') + ') não coincide com a categoria (' + (catSexo === 'M' ? 'Masculino' : 'Feminino') + ').');
    }

    // Checa idade se atleta foi selecionado
    if (atletaIdadeCalculada !== null) {
        if (atletaIdadeCalculada < catIdadeMin || atletaIdadeCalculada > catIdadeMax) {
            erros.push('A idade do atleta (' + atletaIdadeCalculada + ' anos) está fora da faixa etária da categoria (' + catIdadeMin + ' a ' + catIdadeMax + ' anos).');
        }
    }

    if (erros.length > 0) {
        texto.innerHTML = '<br>&bull; ' + erros.join('<br>&bull; ');
        alerta.style.display = 'block';
    } else {
        alerta.style.display = 'none';
    }
}
</script>
