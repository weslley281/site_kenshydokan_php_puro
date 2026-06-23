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
<!-- /Navigation -->
<div class="container mt-5">
  <!-- Project One -->
  <div class="row mt-5">
    <div class="col-lg-7">
      <?php if ($membros['jonas']['id_usuario']): ?>
        <a href="ver_perfil.php?id_usuario=<?php echo $membros['jonas']['id_usuario']; ?>">
          <img class="img-fluid rounded mb-3 mb-lg-0 shadow-sm border" src="<?php echo $membros['jonas']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['jonas']['nome']); ?> Presidente da FKCMT" style="width: 100%; height: 400px; object-fit: cover; object-position: center top;">
        </a>
      <?php else: ?>
        <img class="img-fluid rounded mb-3 mb-lg-0 shadow-sm border" src="<?php echo $membros['jonas']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['jonas']['nome']); ?> Presidente da FKCMT" style="width: 100%; height: 400px; object-fit: cover; object-position: center top;">
      <?php endif; ?>
    </div>
    <div class="col-lg-5">
      <h3>
        <?php if ($membros['jonas']['id_usuario']): ?>
          <a href="ver_perfil.php?id_usuario=<?php echo $membros['jonas']['id_usuario']; ?>" class="text-danger font-weight-bold text-decoration-none"><?php echo htmlspecialchars($membros['jonas']['nome']); ?></a>
        <?php else: ?>
          <?php echo htmlspecialchars($membros['jonas']['nome']); ?>
        <?php endif; ?>
      </h3>
      <p class="text-muted font-weight-bold"><?php echo htmlspecialchars($membros['jonas']['cargo']); ?></p>
      <h4 class="font-weight-bold mt-4"><i class="fa-solid fa-award text-danger mr-2"></i>Suas Graduações: </h4>
      <ul class="list-unstyled">
        <?php if (!empty($membros['jonas']['graduacoes'])): ?>
          <?php foreach ($membros['jonas']['graduacoes'] as $g): ?>
            <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <br>

  <div class="container-fluid px-0">
    <h1 class="my-4 font-weight-bold">Seus Alunos Graduados
      <small class="text-secondary font-weight-light" style="font-size: 1.5rem;">Faixas Pretas</small>
    </h1>

    <div class="row">
      <!-- Everson -->
      <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
          <?php if ($membros['everson']['id_usuario']): ?>
            <a href="ver_perfil.php?id_usuario=<?php echo $membros['everson']['id_usuario']; ?>">
              <img class="card-img-top" src="<?php echo $membros['everson']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['everson']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
            </a>
          <?php else: ?>
            <img class="card-img-top" src="<?php echo $membros['everson']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['everson']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
          <?php endif; ?>
          <div class="card-body p-4">
            <h4 class="card-title font-weight-bold">
              <?php if ($membros['everson']['id_usuario']): ?>
                <a href="ver_perfil.php?id_usuario=<?php echo $membros['everson']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['everson']['nome']); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($membros['everson']['nome']); ?>
              <?php endif; ?>
            </h4>
            <h5 class="font-weight-bold mt-3 text-secondary" style="font-size: 0.95rem;">GRADUAÇÕES:</h5>
            <ul class="list-unstyled small mt-2">
              <?php if (!empty($membros['everson']['graduacoes'])): ?>
                <?php foreach ($membros['everson']['graduacoes'] as $g): ?>
                  <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- Weslley -->
      <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
          <?php if ($membros['weslley']['id_usuario']): ?>
            <a href="ver_perfil.php?id_usuario=<?php echo $membros['weslley']['id_usuario']; ?>">
              <img class="card-img-top" src="<?php echo $membros['weslley']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['weslley']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
            </a>
          <?php else: ?>
            <img class="card-img-top" src="<?php echo $membros['weslley']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['weslley']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
          <?php endif; ?>
          <div class="card-body p-4">
            <h4 class="card-title font-weight-bold">
              <?php if ($membros['weslley']['id_usuario']): ?>
                <a href="ver_perfil.php?id_usuario=<?php echo $membros['weslley']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['weslley']['nome']); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($membros['weslley']['nome']); ?>
              <?php endif; ?>
            </h4>
            <h5 class="font-weight-bold mt-3 text-secondary" style="font-size: 0.95rem;">GRADUAÇÕES:</h5>
            <ul class="list-unstyled small mt-2">
              <?php if (!empty($membros['weslley']['graduacoes'])): ?>
                <?php foreach ($membros['weslley']['graduacoes'] as $g): ?>
                  <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- Elyakin -->
      <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
          <?php if ($membros['elyakin']['id_usuario']): ?>
            <a href="ver_perfil.php?id_usuario=<?php echo $membros['elyakin']['id_usuario']; ?>">
              <img class="card-img-top" src="<?php echo $membros['elyakin']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['elyakin']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
            </a>
          <?php else: ?>
            <img class="card-img-top" src="<?php echo $membros['elyakin']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['elyakin']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
          <?php endif; ?>
          <div class="card-body p-4">
            <h4 class="card-title font-weight-bold">
              <?php if ($membros['elyakin']['id_usuario']): ?>
                <a href="ver_perfil.php?id_usuario=<?php echo $membros['elyakin']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['elyakin']['nome']); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($membros['elyakin']['nome']); ?>
              <?php endif; ?>
            </h4>
            <h5 class="font-weight-bold mt-3 text-secondary" style="font-size: 0.95rem;">GRADUAÇÕES:</h5>
            <ul class="list-unstyled small mt-2">
              <?php if (!empty($membros['elyakin']['graduacoes'])): ?>
                <?php foreach ($membros['elyakin']['graduacoes'] as $g): ?>
                  <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Rafael -->
      <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
          <?php if ($membros['rafael']['id_usuario']): ?>
            <a href="ver_perfil.php?id_usuario=<?php echo $membros['rafael']['id_usuario']; ?>">
              <img class="card-img-top" src="<?php echo $membros['rafael']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['rafael']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
            </a>
          <?php else: ?>
            <img class="card-img-top" src="<?php echo $membros['rafael']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['rafael']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
          <?php endif; ?>
          <div class="card-body p-4">
            <h4 class="card-title font-weight-bold">
              <?php if ($membros['rafael']['id_usuario']): ?>
                <a href="ver_perfil.php?id_usuario=<?php echo $membros['rafael']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['rafael']['nome']); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($membros['rafael']['nome']); ?>
              <?php endif; ?>
            </h4>
            <h5 class="font-weight-bold mt-3 text-secondary" style="font-size: 0.95rem;">GRADUAÇÕES:</h5>
            <ul class="list-unstyled small mt-2">
              <?php if (!empty($membros['rafael']['graduacoes'])): ?>
                <?php foreach ($membros['rafael']['graduacoes'] as $g): ?>
                  <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- Roset -->
      <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
          <?php if ($membros['roset']['id_usuario']): ?>
            <a href="ver_perfil.php?id_usuario=<?php echo $membros['roset']['id_usuario']; ?>">
              <img class="card-img-top" src="<?php echo $membros['roset']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['roset']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
            </a>
          <?php else: ?>
            <img class="card-img-top" src="<?php echo $membros['roset']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['roset']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
          <?php endif; ?>
          <div class="card-body p-4">
            <h4 class="card-title font-weight-bold">
              <?php if ($membros['roset']['id_usuario']): ?>
                <a href="ver_perfil.php?id_usuario=<?php echo $membros['roset']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['roset']['nome']); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($membros['roset']['nome']); ?>
              <?php endif; ?>
            </h4>
            <h5 class="font-weight-bold mt-3 text-secondary" style="font-size: 0.95rem;">GRADUAÇÕES:</h5>
            <ul class="list-unstyled small mt-2">
              <?php if (!empty($membros['roset']['graduacoes'])): ?>
                <?php foreach ($membros['roset']['graduacoes'] as $g): ?>
                  <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- Nilson -->
      <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
          <?php if ($membros['nilson']['id_usuario']): ?>
            <a href="ver_perfil.php?id_usuario=<?php echo $membros['nilson']['id_usuario']; ?>">
              <img class="card-img-top" src="<?php echo $membros['nilson']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['nilson']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
            </a>
          <?php else: ?>
            <img class="card-img-top" src="<?php echo $membros['nilson']['caminho_foto']; ?>" alt="<?php echo htmlspecialchars($membros['nilson']['nome']); ?>" style="height: 280px; object-fit: cover; object-position: center top;">
          <?php endif; ?>
          <div class="card-body p-4">
            <h4 class="card-title font-weight-bold">
              <?php if ($membros['nilson']['id_usuario']): ?>
                <a href="ver_perfil.php?id_usuario=<?php echo $membros['nilson']['id_usuario']; ?>" class="text-danger text-decoration-none"><?php echo htmlspecialchars($membros['nilson']['nome']); ?></a>
              <?php else: ?>
                <?php echo htmlspecialchars($membros['nilson']['nome']); ?>
              <?php endif; ?>
            </h4>
            <h5 class="font-weight-bold mt-3 text-secondary" style="font-size: 0.95rem;">GRADUAÇÕES:</h5>
            <ul class="list-unstyled small mt-2">
              <?php if (!empty($membros['nilson']['graduacoes'])): ?>
                <?php foreach ($membros['nilson']['graduacoes'] as $g): ?>
                  <li class="mb-2"><i class="fa-solid fa-check text-danger mr-2"></i> <?php echo htmlspecialchars($g['graduacao_nome']) . ' em ' . htmlspecialchars($g['arte_nome']); ?></li>
                <?php endforeach; ?>
              <?php else: ?>
                <li class="text-muted"><i class="fa-solid fa-xmark text-secondary mr-2"></i> Sem registro de graduação</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    </div>
    <hr>
  </div>

  <br>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <h2 class="my-4">Sobre o Karate Kenshydokan a História</h2>
        <p>O Karatê Kenshydokan nasceu em Várzea Grande, Mato Grosso, distante dos grandes centros marciais do país, mas carregando uma força que logo se mostraria impossível de ignorar. Forjado por seu criador, o Shihan Jonas Teixeira de Andrade, o estilo começou de forma humilde, treinando em locais simples e às vezes improvisados, com estrutura quase nenhuma e poucos recursos financeiros. No entanto, desde o início havia ali algo diferente: propósito.
        </p>
        <p>
          Os treinos eram duros, formais e objetivos. A metodologia ainda estava em construção e, embora bruta, era autêntica. A busca era formar lutadores completos, conectando a essência real do combate com a tradição marcial. Dessa prática surgiram os três pilares técnicos do Kenshydokan: o atemi waza, o nage waza e o ne waza — golpes, projeções e domínio no solo — resultado da formação de seu fundador em Karatê Kyokushin, Judô Kodokan, Jiu-Jitsu, Kickboxing e Muay Thai.
        </p>
        <p>
          Com o tempo, o estilo começou a crescer. Vieram eventos como a 1ª Copa Várzea-grandense e o Campeonato Estadual de Karatê de Contato Kenshydokan, promovendo visibilidade e reconhecimento. Em competições maiores, como o 1º Open de Kyokushinkai, alunos provaram sua força, conquistando os primeiros lugares. A consagração veio no Mundial no Chile, quando dois atletas se tornaram campeões mundiais em suas categorias: Everson Jones Batista Leite e Emanuel Cristhian C. da Cruz.
        </p>
        <p>
          O crescimento também se refletiu na formação de faixas pretas, como Elyakin Vinicius C. de M. Metello, Roset de Almeida Lobo e Rafael Carlos de Almeida Faria. Entre eles, destaca-se Weslley Henrique Vieira Ferraz, 3º Dan, discípulo direto do fundador, responsável pelo registro histórico do estilo e sua expansão digital, levando o Kenshydokan ao reconhecimento nacional e internacional.
        </p>
        <p>
          Hoje, o Karatê Kenshydokan segue fiel à sua origem. Seu propósito não é fama, mas verdade; não é quantidade, mas qualidade. Seu juramento — o Dojô Kum Kenshydokan — representa o compromisso moral, a disciplina, a humildade, o respeito e a força guiada por sabedoria. Cada praticante jura semear o respeito, cultivar espírito inabalável e honrar o estilo.
        </p>
        <p>
          O estilo possui katas próprios, chamados Seishin (Ichi, Ni, San, Shi, Go), representando etapas da evolução técnica e espiritual. As graduações seguem do kyu ao dan, culminando no 10º Dan. O símbolo do estilo — círculo vermelho com o kanji Kenshy Dō Kan — representa energia vital e espírito guerreiro. O dogi branco, com o emblema sobre o coração, simboliza disciplina e caráter.
        </p>
        <p>
          O treino Kenshydokan se destaca pela abordagem realista: contato forte, projeções, luta no solo, autodefesa real e condicionamento funcional. A missão é formar guerreiros completos para a vida — fortes, honestos e inquebráveis.
        </p>
      </div>
    </div>
  </div>

  <br>

  <div class="container mt-5">
    <h1 class="my-4">Certificados de Representatividade e Homologação
      <small>Downloads</small>
    </h1>

    <!-- Project Two -->
    <div class="row mt-5">
      <div class="col-lg-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-lg-0" src="../img/certificado 1.jpeg" alt="Certificado se Representatividade da WKA do Karate Kenshydokan">
        </a>
      </div>
      <div class="col-lg-5">
        <h3>Certificado se Representatividade da WKA</h3>
        <p>.</p>
        <a href="imagens/certificado 1.jpeg" download="certificado 1.jpeg" class="btn btn-primary"> Baixar </a>
      </div>
    </div>
    <!-- /.row -->

    <hr>

    <!-- Project Three -->
    <div class="row">
      <div class="col-lg-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-lg-0" src="../img/certificado 2.jpeg" alt="Certificado de Homologação do Karate Kenshydokan">
        </a>
      </div>
      <div class="col-lg-5">
        <h3>Certificado de Homologação</h3>
        <p></p>
        <a href="imagens/certificado 2.jpeg" download="certificado 2.jpeg" class="btn btn-primary"> Baixar </a>
      </div>
    </div>
    <!-- /.row -->

    <hr>

    <!-- Project For -->
    <div class="row">

      <div class="col-lg-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-lg-0" src="../img/homologacao.jpeg" alt="Certificado de Homologação do Karate Kenshydokan">
        </a>
      </div>
      <div class="col-lg-5">
        <h3>Certificado de Homologação</h3>
        <p></p>
        <a href="imagens/homologacao.jpeg" download="homologacao.jpeg" class="btn btn-primary"> Baixar </a>
      </div>
    </div>
    <!-- /.row -->

    <hr>

    <!-- Project Five -->
    <div class="row">

      <div class="col-lg-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-lg-0" src="../arquivos/certificado instituto muay thai.png" alt="Certificado de Filiação Concedida do KickBosing a Kenshydokan">
        </a>
      </div>
      <div class="col-lg-5">
        <h3>Certificado de Filiação Muay Thai</h3>
        <p></p>
        <a href="imagens/homologacao.jpeg" download="homologacao.jpeg" class="btn btn-primary"> Baixar </a>
      </div>
    </div>
    <!-- /.row -->

    <hr>

    <!-- Project Five -->
    <div class="row">

      <div class="col-lg-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-lg-0" src="../arquivos/filiação consedida.jpg" alt="Certificado de Filiação Concedida do KickBosing a Kenshydokan">
        </a>
      </div>
      <div class="col-lg-5">
        <h3>Certificado de Filiação KickBoxing</h3>
        <p></p>
        <a href="imagens/homologacao.jpeg" download="homologacao.jpeg" class="btn btn-primary"> Baixar </a>
      </div>
    </div>
    <!-- /.row -->

    <hr>

    <!-- Project Six -->
    <div class="row">

      <div class="col-lg-7">
        <a href="#">
          <img class="img-fluid rounded mb-3 mb-lg-0" src="../img/gradução kenshydokan.jpg" alt="Sistema de Graduação do Karate Kenshydokan">
        </a>
      </div>
      <div class="col-lg-5">
        <h3>Sistema de Graduação do Karatê Kenshydokan</h3>
        <p></p>
        <a href="imagens/gradução kenshydokan.jpg" download="gradução kenshydokan.jpg" class="btn btn-primary"> Baixar </a>
      </div>
    </div>
    <!-- /.row -->

    <hr>
  </div>

</div>

<?php include "rodape.php"; ?>