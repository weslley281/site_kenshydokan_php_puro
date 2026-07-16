<?php
include "menu.php";
include_once "../models/filiadoModel.php";

$filiadoModel = new FiliadoModel();
$dbConn = new Conexao();
$conexao = $dbConn->conectar();

// Função para buscar dados dinâmicos de um filiado
function obterDadosMembro($conexao, $filiadoModel, $id_filiado, $default_img, $cargo = '') {
    $nome = '';
    $id_usuario = null;
    $img_nome_db = null;

    // Busca o nome do filiado
    $stmt_fil = $conexao->prepare("SELECT nome FROM filiados WHERE id_filiado = ? LIMIT 1");
    $stmt_fil->bind_param("i", $id_filiado);
    $stmt_fil->execute();
    $res_fil = $stmt_fil->get_result();
    if ($row_fil = $res_fil->fetch_assoc()) {
        $nome = $row_fil['nome'];
    }
    $stmt_fil->close();

    // Busca id_usuario e imagem do usuário vinculado
    $stmt = $conexao->prepare("
        SELECT u.id_usuario, img.nome AS imagem_nome
        FROM usuarios u
        LEFT JOIN imagens img ON u.id_imagem = img.id_imagem
        WHERE u.id_fil = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $id_filiado);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id_usuario = $row['id_usuario'];
        $img_nome_db = $row['imagem_nome'];
    }
    $stmt->close();

    $caminho_foto = $default_img;
    if (!empty($img_nome_db) && file_exists(__DIR__ . '/../img/' . $img_nome_db)) {
        $caminho_foto = '../img/' . $img_nome_db;
    }

    $graduacoes = $filiadoModel->buscarGraduacoesFiliado($id_filiado);

    return [
        'id_filiado' => $id_filiado,
        'id_usuario' => $id_usuario,
        'nome' => !empty($nome) ? $nome : 'Nome não disponível',
        'caminho_foto' => $caminho_foto,
        'cargo' => $cargo,
        'graduacoes' => $graduacoes
    ];
}

$membros = [
    'jonas' => obterDadosMembro($conexao, $filiadoModel, 14, '../img/shihan.jpg', 'Presidente do Instituto e Fundador do Estilo Karatê Kenshydokan.'),
    'everson' => obterDadosMembro($conexao, $filiadoModel, 20, '../img/sensei-everson.jpg'),
    'weslley' => obterDadosMembro($conexao, $filiadoModel, 23, '../img/sensei_weslley.jpg'),
    'elyakin' => obterDadosMembro($conexao, $filiadoModel, 22, '../img/sensei_elyakin.jpg'),
    'rafael' => obterDadosMembro($conexao, $filiadoModel, 25, '../img/sensei_rafael.jpg'),
    'roset' => obterDadosMembro($conexao, $filiadoModel, 33, '../img/sensei_rose.jpg'),
    'nilson' => obterDadosMembro($conexao, $filiadoModel, 98, '../img/sensei_nilson.jpg')
];

$conexao->close();
?>

<!-- Custom Style overrides for premium design -->
<style>
.about-hero {
    background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.9)), url('../arquivos/background.png') center/cover no-repeat;
    padding: 70px 0;
    color: white;
    border-bottom: 4px solid #d9232d;
}
.founder-card {
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.founder-img {
    height: 420px;
    object-fit: cover;
    object-position: center top;
}
.member-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
}
.member-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.15) !important;
}
.download-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 12px;
}
.download-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important;
}
.download-img {
    height: 200px;
    object-fit: cover;
    object-position: center top;
}
.history-p {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #4a4a4a;
    text-align: justify;
    margin-bottom: 1.5rem;
}
</style>

<div class="about-hero text-center mb-5">
    <div class="container">
        <img src="../img/wkka.jpg" width="80" alt="Logo" class="mb-3 rounded-circle shadow-sm border border-danger" style="border-width: 2px !important;">
        <h1 class="display-4 font-weight-bold text-white mb-2">Quem Somos</h1>
        <p class="lead text-light font-weight-light">História, Fundadores e Corpo de Professores do Karatê Kenshydokan</p>
    </div>
</div>

<div class="container mb-5">
    <!-- Shihan Jonas -->
    <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card founder-card border-0 shadow-sm">
                <?php if ($membros['jonas']['id_usuario']): ?>
                    <a href="ver_perfil.php?id_usuario=<?php echo $membros['jonas']['id_usuario']; ?>">
                        <img class="card-img-top founder-img" src="<?php echo $membros['jonas']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['jonas']['nome']); ?>">
                    </a>
                <?php else: ?>
                    <img class="card-img-top founder-img" src="<?php echo $membros['jonas']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['jonas']['nome']); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6 pl-lg-5">
            <span class="badge badge-danger px-3 py-2 rounded-pill font-weight-bold text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Fundador & Shihan</span>
            <h2 class="font-weight-bold text-dark mb-3">
                <?php if ($membros['jonas']['id_usuario']): ?>
                    <a href="ver_perfil.php?id_usuario=<?php echo $membros['jonas']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['jonas']['nome']); ?></a>
                <?php else: ?>
                    <?php echo htmlspecialchars($membros['jonas']['nome']); ?>
                <?php endif; ?>
            </h2>
            <p class="text-secondary font-weight-bold mb-4"><?php echo htmlspecialchars($membros['jonas']['cargo']); ?></p>
            
            <div class="border-top pt-4">
                <h5 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-award text-danger mr-2"></i>Graduações e Títulos:</h5>
                <ul class="list-unstyled row">
                    <?php if (!empty($membros['jonas']['graduacoes'])): ?>
                        <?php foreach ($membros['jonas']['graduacoes'] as $g): ?>
                            <li class="col-md-6 mb-2 text-secondary"><i class="fa-solid fa-circle-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="col-12 text-muted"><i class="fa-solid fa-circle-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Seção História -->
<div class="bg-light py-5 mb-5 border-top border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 mb-4 mb-lg-0 text-center text-lg-left">
                <span class="text-danger font-weight-bold text-uppercase small" style="letter-spacing: 1px;">Tradição e Força</span>
                <h2 class="font-weight-bold text-dark mt-2">Nossa História</h2>
                <p class="text-muted font-weight-light">Conheça a jornada de dedicação, superação e vitórias que moldou o estilo Kenshydokan.</p>
                <img src="../img/wkka.jpg" width="120" alt="Símbolo" class="mt-3 rounded-circle shadow-sm opacity-75 d-none d-lg-inline-block">
            </div>
            <div class="col-lg-8 pl-lg-5">
                <p class="history-p">O Karatê Kenshydokan nasceu em Várzea Grande, Mato Grosso, distante dos grandes centros marciais do país, mas carregando uma força que logo se mostraria impossível de ignorar. Forjado por seu criador, o Shihan Jonas Teixeira de Andrade, o estilo começou de forma humilde, treinando em locais simples e às vezes improvisados, com estrutura quase nenhuma e poucos recursos financeiros. No entanto, desde o início havia ali algo diferente: propósito.</p>
                <p class="history-p">Os treinos eram duros, formais e objetivos. A metodologia ainda estava em construção e, embora bruta, era autêntica. A busca era formar lutadores completos, conectando a essência real do combate com a tradição marcial. Dessa prática surgiram os três pilares técnicos do Kenshydokan: o atemi waza, o nage waza e o ne waza — golpes, projeções e domínio no solo — resultado da formação de seu fundador em Karatê Kyokushin, Judô Kodokan, Jiu-Jitsu, Kickboxing e Muay Thai.</p>
                <p class="history-p">Com o tempo, o estilo começou a crescer. Vieram eventos como a 1ª Copa Várzea-grandense e o Campeonato Estadual de Karatê de Contato Kenshydokan, promovendo visibilidade e reconhecimento. A consagração veio no Mundial no Chile, quando dois atletas se tornaram campeões mundiais em suas categorias: Everson Jones Batista Leite e Emanuel Cristhian C. da Cruz.</p>
                <p class="history-p">O crescimento também se refletiu na formação de faixas pretas, como Elyakin Vinicius C. de M. Metello, Roset de Almeida Lobo e Rafael Carlos de Almeida Faria. Entre eles, destaca-se Weslley Henrique Vieira Ferraz, 3º Dan, discípulo direto do fundador e mais novo diretor técnico, responsável pelo registro histórico do estilo, sua expansão digital e treinamento de novos instrutores, levando o Kenshydokan ao reconhecimento nacional e internacional.</p>
                <p class="history-p">Hoje, o Karatê Kenshydokan segue fiel à sua origem. Seu propósito não é fama, mas verdade; não é quantidade, mas qualidade. Seu juramento — o Dojô Kum Kenshydokan — representa o compromisso moral, a disciplina, a humildade, o respeito e a força guiada por sabedoria. Cada praticante jura semear o respeito, cultivar espírito inabalável e honrar o estilo.</p>
            </div>
        </div>
    </div>
</div>

<!-- Seção Reconhecimento Oficial -->
<div class="container mb-5">
    <div class="card border-0 shadow-sm rounded-lg overflow-hidden bg-white">
        <div class="row no-gutters">
            <div class="col-lg-1 bg-danger text-white d-flex align-items-center justify-content-center p-4">
                <i class="fa-solid fa-building-columns fa-3x d-none d-lg-block"></i>
                <i class="fa-solid fa-building-columns fa-2x d-lg-none"></i>
            </div>
            <div class="col-lg-11 p-4 pl-lg-5">
                <span class="badge badge-danger px-3 py-2 rounded-pill font-weight-bold text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                    <i class="fa-solid fa-landmark mr-1"></i> Reconhecimento Oficial
                </span>
                <h3 class="font-weight-bold text-dark mb-3">Utilidade Pública Estadual</h3>
                <p class="history-p mb-4">
                    No dia 12 de junho de 2026, o Instituto Kenshydokan alcançou um dos marcos mais importantes de sua trajetória. Através da promulgação da **Lei Nº 13.446**, de autoria do Deputado Eduardo Botelho e assinada pelo Presidente da Assembleia Legislativa do Estado de Mato Grosso, Deputado Max Russi, fomos oficialmente declarados como uma instituição de **Utilidade Pública Estadual**.
                </p>

                <!-- Bloco Destacado -->
                <div class="border-left border-danger pl-3 mb-4 bg-light py-3 pr-3 rounded-right" style="border-left-width: 4px !important;">
                    <h5 class="font-weight-bold text-dark mb-2">O que isso significa?</h5>
                    <p class="text-secondary mb-0 small" style="line-height: 1.6;">
                        Este título é o reconhecimento solene do Governo e da Assembleia Legislativa de Mato Grosso de que o trabalho esportivo, educacional e social desempenhado pela Kenshydokan é de relevância pública e gera um impacto altamente positivo e transformador na sociedade mato-grossense.
                    </p>
                </div>

                <!-- Detalhes em lista -->
                <h6 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-list-check text-danger mr-2"></i>Detalhes do Registro Oficial:</h6>
                <div class="row text-secondary mb-4">
                    <div class="col-md-6 mb-2">
                        <i class="fa-solid fa-scroll text-danger mr-2"></i><strong>Lei Estadual:</strong> Nº 13.446, de 12 de junho de 2026
                    </div>
                    <div class="col-md-6 mb-2">
                        <i class="fa-solid fa-user-tie text-danger mr-2"></i><strong>Autor do Projeto:</strong> Deputado Eduardo Botelho
                    </div>
                    <div class="col-md-12 mb-2">
                        <i class="fa-solid fa-file-invoice text-danger mr-2"></i><strong>Publicação Oficial:</strong> Diário Oficial Eletrônico da ALMT, Ano XI, Nº 2057 (18 de Junho de 2026)
                    </div>
                    <div class="col-md-12">
                        <i class="fa-solid fa-users-rectangle text-danger mr-2"></i><strong>Entidade Beneficiada:</strong> Instituto de Artes Marciais e Defesa Pessoal Kenshydokan (CNPJ: 10.707.722/0001-34), sediado em Várzea Grande - MT.
                    </div>
                </div>

                <!-- Futuro -->
                <p class="history-p mb-4">
                    <strong>🚀 O Futuro: Compromisso Social e Marcial</strong><br>
                    Com a chancela de Utilidade Pública, o Instituto Kenshydokan reafirma seu compromisso em utilizar o karatê de contato e a defesa pessoal não apenas como ferramentas de autodefesa, mas como pilares de transformação social, disciplina e cidadania para crianças, jovens e adultos de Mato Grosso.
                </p>

                <!-- Botão de Ver Diário Oficial -->
                <a href="https://diariooficial.al.mt.gov.br/media/publicacoes/2026/6/18/2974_cc942433-bbdf-4554-85c5-8fe3ed413836_2026-6-18.pdf" target="_blank" class="btn btn-outline-danger font-weight-bold rounded-pill shadow-sm px-4">
                    <i class="fa-solid fa-file-pdf mr-2"></i>Ver Diário Oficial (Pág. 9)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Corpo Técnico / Faixas Pretas -->
<div class="container mb-5">
    <div class="text-center mb-5">
        <span class="text-danger font-weight-bold text-uppercase small" style="letter-spacing: 1px;">Corpo Técnico</span>
        <h2 class="font-weight-bold text-dark mt-2">Professores e Faixas Pretas</h2>
        <p class="text-muted font-weight-light">Discípulos graduados que mantêm viva a chama e o ensino do estilo Kenshydokan.</p>
    </div>
    <div class="row">
        <?php 
        $faixas_pretas = ['everson', 'weslley', 'elyakin', 'rafael', 'roset', 'nilson'];
        foreach ($faixas_pretas as $chave): 
            $m = $membros[$chave];
        ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card member-card h-100 border-0 shadow-sm overflow-hidden bg-white">
                <?php if ($m['id_usuario']): ?>
                    <a href="ver_perfil.php?id_usuario=<?php echo $m['id_usuario']; ?>">
                        <img class="card-img-top" src="<?php echo $m['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($m['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
                    </a>
                <?php else: ?>
                    <img class="card-img-top" src="<?php echo $m['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($m['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
                <?php endif; ?>
                <div class="card-body p-4">
                    <h5 class="card-title font-weight-bold mb-3">
                        <?php if ($m['id_usuario']): ?>
                            <a href="ver_perfil.php?id_usuario=<?php echo $m['id_usuario']; ?>" class="text-danger text-decoration-none hover-danger"><?php echo htmlspecialchars($m['nome']); ?></a>
                        <?php else: ?>
                            <?php echo htmlspecialchars($m['nome']); ?>
                        <?php endif; ?>
                    </h5>
                    <span class="badge badge-light border text-secondary font-weight-bold text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">Instrutor Faixa Preta</span>
                    <h6 class="font-weight-bold text-dark small mb-2"><i class="fa-solid fa-medal text-danger mr-1"></i>Graduações:</h6>
                    <ul class="list-unstyled small text-secondary">
                        <?php if (!empty($m['graduacoes'])): ?>
                            <?php foreach ($m['graduacoes'] as $g): ?>
                                <li class="mb-1"><i class="fa-solid fa-check text-danger mr-1"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="text-muted"><i class="fa-solid fa-circle-minus text-secondary mr-1"></i> Sem registro</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Galeria de Credenciais -->
<div class="bg-light py-5 border-top">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-danger font-weight-bold text-uppercase small" style="letter-spacing: 1px;">Documentação Oficial</span>
            <h2 class="font-weight-bold text-dark mt-2">Certificados e Homologações</h2>
            <p class="text-muted font-weight-light">Documentos de representatividade e filiação oficial da Associação Kenshydokan.</p>
        </div>
        <div class="row">
            <?php
            $certificados_down = [
                ['titulo' => 'Certificado de Representatividade da WKA', 'imagem' => '../img/certificado 1.jpeg'],
                ['titulo' => 'Certificado de Homologação', 'imagem' => '../img/certificado 2.jpeg'],
                ['titulo' => 'Certificado de Homologação Geral', 'imagem' => '../img/homologacao.jpeg'],
                ['titulo' => 'Certificado de Filiação Muay Thai', 'imagem' => '../arquivos/certificado instituto muay thai.png'],
                ['titulo' => 'Certificado de Filiação KickBoxing', 'imagem' => '../arquivos/filiação consedida.jpg'],
                ['titulo' => 'Sistema de Graduação do Karatê Kenshydokan', 'imagem' => '../img/gradução kenshydokan.jpg']
            ];

            foreach ($certificados_down as $c):
                $caminho_imagem = $c['imagem'];
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card download-card h-100 border-0 shadow-sm overflow-hidden bg-white">
                    <img class="card-img-top download-img border-bottom" src="<?php echo $caminho_imagem; ?>" alt="<?php echo htmlspecialchars($c['titulo']); ?>">
                    <div class="card-body p-4 text-center">
                        <h6 class="font-weight-bold text-dark mb-0"><?php echo htmlspecialchars($c['titulo']); ?></h6>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include "rodape.php"; ?>