<?php
$pageTitle = 'Inscrever atleta | Kenshydokan';
include_once __DIR__ . "/menu.php";
include_once __DIR__ . "/../models/categoriaModel.php";

if (!$campAtual) {
    echo "<div class='alert alert-warning'>Escolha um campeonato primeiro. <a href='inicio.php'>Ver campeonatos</a></div>";
    include_once __DIR__ . "/rodape.php";
    exit;
}
$idCampeonato = $cid;
$categorias = (new Categoria())->buscarPorCampeonato($idCampeonato);
?>

<div class="page-head">
    <div>
        <h1>Inscrever atleta</h1>
        <p>Digite o nome do atleta, escolha a categoria e clique em <strong>Inscrever</strong>.</p>
    </div>
    <a href="inscricoes.php?id=<?= $idCampeonato ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
</div>

<?php if (empty($categorias)): ?>
    <div class="alert alert-warning">Antes de inscrever, crie pelo menos uma categoria. <a href="categorias.php?id=<?= $idCampeonato ?>">Criar categoria</a></div>
<?php else: ?>
<div class="card" style="max-width:720px">
    <div class="card-body p-4">
        <form method="POST" action="../controllers/inscricaoController.php" id="formInscricao">
            <input type="hidden" name="tipo" value="criar_inscricao">
            <input type="hidden" name="id_campeonato" value="<?= $idCampeonato ?>">
            <input type="hidden" id="data_ref_campeonato" value="<?= htmlspecialchars($campAtual['data_referencia_idade']) ?>">

            <div class="mb-4">
                <label for="selectFiliado" class="form-label">1. Nome do atleta</label>
                <select id="selectFiliado" name="id_filiado" class="form-select" required>
                    <option value="">Digite pelo menos 2 letras...</option>
                </select>
                <div id="erroBuscaFiliado" class="alert alert-danger mt-2 mb-0" role="alert" style="display:none"></div>
                <div id="statusBuscaFiliado" class="small text-muted mt-2" role="status" aria-live="polite" style="display:none"></div>
                <details id="diagnosticoBuscaFiliado" class="mt-2" style="display:none">
                    <summary>Detalhes técnicos da busca</summary>
                    <pre class="small bg-light border rounded p-2 mt-2 mb-0" style="white-space:pre-wrap"></pre>
                </details>
                <div id="previewFiliado" class="mt-2 p-3 bg-light rounded" style="display:none">
                    <strong id="previewNome"></strong><br>
                    <span class="text-muted"><span id="previewDojo"></span> · <span id="previewGraduacao"></span> · <span id="previewIdade"></span></span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="sexo" class="form-label">2. Sexo</label>
                    <select class="form-select" id="sexo" name="sexo" required onchange="validarCompatibilidade()">
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label for="id_categoria" class="form-label">3. Categoria</label>
                    <select class="form-select" id="id_categoria" name="id_categoria" required onchange="validarCompatibilidade()">
                        <option value="">Escolha...</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_categoria'] ?>" data-idade-min="<?= $cat['idade_min'] ?>" data-idade-max="<?= $cat['idade_max'] ?>" data-sexo="<?= $cat['sexo'] ?>">
                                <?= htmlspecialchars($cat['nome']) ?> (<?= $cat['idade_min'] ?>–<?= $cat['idade_max'] ?> anos)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="peso_declarado" class="form-label">Peso aproximado (kg) <span class="text-muted fw-normal">— opcional</span></label>
                <input type="number" step="0.1" class="form-control" id="peso_declarado" name="peso_declarado" placeholder="Ex.: 70,5" style="max-width:200px">
            </div>

            <div id="alertaIncompativel" class="alert alert-warning" style="display:none">
                <i class="fas fa-triangle-exclamation me-1"></i> <span id="textoIncompatibilidade"></span>
            </div>

            <button type="submit" class="btn btn-danger btn-lg w-100"><i class="fas fa-check"></i> Inscrever</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
var atletaIdade = null;

window.addEventListener('load', function () {
    $('#selectFiliado').select2({
        width: '100%',
        placeholder: 'Digite pelo menos 2 letras...',
        minimumInputLength: 2,
        language: {
            inputTooShort: function () { return 'Digite pelo menos 2 letras'; },
            noResults: function () { return 'Nenhum atleta encontrado'; },
            searching: function () { return 'Procurando...'; },
            errorLoading: function () { return 'Falha ao consultar filiados. Confira o banco principal e tente novamente.'; }
        },
        ajax: {
            url: '../controllers/inscricaoController.php?acao=buscar_filiados',
            dataType: 'json',
            delay: 300,
            timeout: 12000,
            data: function (p) { return { q: p.term }; },
            processResults: function (d) {
                if (!d || !Array.isArray(d.results)) {
                    console.error('[Busca de filiados] Resposta inesperada do servidor.', {
                        resposta: d,
                        tipoResposta: typeof d
                    });
                    mostrarDiagnosticoBusca(null, 'O servidor retornou uma resposta inesperada.');
                    return { results: [] };
                }
                mostrarDiagnosticoBusca(d.debug, d.error);
                return { results: d.results || [] };
            },
            transport: function (params, success, failure) {
                var status = document.getElementById('statusBuscaFiliado');
                status.textContent = 'Consultando o banco principal...';
                status.style.display = 'block';
                var request = new XMLHttpRequest();
                var url = params.url;
                var dados = params.data || {};
                var query = Object.keys(dados).map(function (key) {
                    return encodeURIComponent(key) + '=' + encodeURIComponent(dados[key]);
                }).join('&');
                if (query) url += (url.indexOf('?') === -1 ? '?' : '&') + query;

                var urlLog = url.replace(/([?&])q=[^&]*/i, '$1q=[termo oculto]');
                request.timeout = params.timeout || 12000;
                request.onreadystatechange = function () {
                    if (request.readyState !== XMLHttpRequest.DONE) return;
                    status.style.display = 'none';

                    if (request.status >= 200 && request.status < 300) {
                        var respostaValida;
                        try {
                            respostaValida = JSON.parse(request.responseText);
                        } catch (erroJson) {
                            console.error('[Busca de filiados] Falha ao interpretar JSON.', {
                                erro: erroJson,
                                status: request.status,
                                url: urlLog,
                                resposta: request.responseText.substring(0, 4000)
                            });
                            var erroResposta = {
                                status: request.status,
                                statusText: 'Resposta JSON inválida',
                                responseJSON: null
                            };
                            mostrarDiagnosticoBusca(null, 'O servidor respondeu em formato inválido.', request.responseText.substring(0, 2000));
                            failure(erroResposta);
                            return;
                        }
                        console.info('[Busca de filiados] Resposta recebida.', {
                            status: request.status,
                            quantidade: respostaValida && Array.isArray(respostaValida.results) ? respostaValida.results.length : 'formato inesperado'
                        });
                        success(respostaValida);
                        return;
                    }

                    var resposta = null;
                    try { resposta = JSON.parse(request.responseText); } catch (erroJson) {}
                    var erroHttp = {
                        status: request.status,
                        statusText: request.statusText || 'Erro HTTP',
                        responseJSON: resposta
                    };
                    console.error('[Busca de filiados] Requisição HTTP falhou.', {
                        status: request.status,
                        statusText: request.statusText,
                        url: urlLog,
                        resposta: resposta || request.responseText.substring(0, 4000)
                    });
                    var detalheTransporte = request.status
                        ? 'HTTP ' + request.status + (request.statusText ? ' (' + request.statusText + ')' : '')
                        : 'Sem resposta HTTP (' + (request.statusText || 'erro de rede') + ')';
                    mostrarDiagnosticoBusca(
                        resposta && resposta.debug,
                        resposta && resposta.error ? resposta.error : 'Não foi possível concluir a busca. ' + detalheTransporte,
                        resposta && resposta.debug ? resposta.debug.var_dump : detalheTransporte
                    );
                    failure(erroHttp);
                };
                request.ontimeout = function () {
                    status.style.display = 'none';
                    var erroTimeout = {
                        status: 0,
                        statusText: 'timeout',
                        responseJSON: null
                    };
                    var detalhe = 'O servidor não respondeu em até ' + Math.round(request.timeout / 1000) + ' segundos.';
                    console.error('[Busca de filiados] Tempo limite excedido.', {
                        timeoutMs: request.timeout,
                        url: urlLog,
                        readyState: request.readyState
                    });
                    mostrarDiagnosticoBusca(null, 'A busca excedeu o tempo limite.', detalhe);
                    failure(erroTimeout);
                };
                request.onerror = function () {
                    status.style.display = 'none';
                    var erroRede = { status: 0, statusText: 'Erro de rede', responseJSON: null };
                    console.error('[Busca de filiados] Erro de rede.', {
                        url: urlLog,
                        status: request.status,
                        statusText: request.statusText,
                        readyState: request.readyState
                    });
                    mostrarDiagnosticoBusca(null, 'Falha de comunicação com o servidor.', 'Confira a conexão de rede e o console/aba Network do navegador.');
                    failure(erroRede);
                };
                request.onabort = function () {
                    status.style.display = 'none';
                };
                try {
                    request.open(params.type || 'GET', url, true);
                    request.send();
                } catch (erroEnvio) {
                    status.style.display = 'none';
                    console.error('[Busca de filiados] Exceção ao iniciar a requisição.', {
                        erro: erroEnvio,
                        url: urlLog
                    });
                    mostrarDiagnosticoBusca(null, 'Não foi possível iniciar a busca.', erroEnvio.name + ': ' + erroEnvio.message);
                    failure({ status: 0, statusText: erroEnvio.message, responseJSON: null });
                }
                return { abort: function () { request.abort(); } };
            }
        }
    }).on('select2:select', function (e) {
        var d = e.params.data;
        document.getElementById('previewNome').textContent = d.nome;
        document.getElementById('previewDojo').textContent = d.dojo || 'Sem dojo';
        document.getElementById('previewGraduacao').textContent = d.graduacao_nome || 'Faixa não informada';

        atletaIdade = null;
        if (d.dataNascimento && d.dataNascimento !== '0000-00-00') {
            var n = new Date(d.dataNascimento), r = new Date(document.getElementById('data_ref_campeonato').value);
            var idade = r.getFullYear() - n.getFullYear();
            var m = r.getMonth() - n.getMonth();
            if (m < 0 || (m === 0 && r.getDate() < n.getDate())) idade--;
            atletaIdade = idade;
        }
        document.getElementById('previewIdade').textContent = atletaIdade !== null ? atletaIdade + ' anos' : 'sem data de nascimento';
        document.getElementById('previewFiliado').style.display = 'block';
        validarCompatibilidade();
    });
});

function mostrarDiagnosticoBusca(diagnostico, mensagem, detalheExtra) {
    var erro = document.getElementById('erroBuscaFiliado');
    var detalhes = document.getElementById('diagnosticoBuscaFiliado');
    var pre = detalhes.querySelector('pre');
    erro.textContent = mensagem || '';
    erro.style.display = mensagem ? 'block' : 'none';
    pre.textContent = detalheExtra || (diagnostico && diagnostico.var_dump ? diagnostico.var_dump : '');
    detalhes.style.display = pre.textContent ? 'block' : 'none';
}

function validarCompatibilidade() {
    var sel = document.getElementById('id_categoria');
    var op = sel.options[sel.selectedIndex];
    var alerta = document.getElementById('alertaIncompativel');
    if (!op || !op.value) { alerta.style.display = 'none'; return; }

    var erros = [];
    var catSexo = op.getAttribute('data-sexo');
    if (catSexo !== 'misto' && catSexo !== document.getElementById('sexo').value) {
        erros.push('o sexo não é o da categoria');
    }
    var min = parseInt(op.getAttribute('data-idade-min')), max = parseInt(op.getAttribute('data-idade-max'));
    if (atletaIdade !== null && (atletaIdade < min || atletaIdade > max)) {
        erros.push('a idade (' + atletaIdade + ' anos) não é da categoria (' + min + ' a ' + max + ' anos)');
    }
    document.getElementById('textoIncompatibilidade').textContent = erros.length ? 'Atenção: ' + erros.join(' e ') + '.' : '';
    alerta.style.display = erros.length ? 'block' : 'none';
}
</script>

<?php include_once __DIR__ . "/rodape.php"; ?>
