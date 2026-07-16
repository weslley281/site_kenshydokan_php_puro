<?php
include "menu.php";

$id_arte = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Dados estruturados e detalhados sobre as artes marciais coletados da web
$artes_dados = [
    1 => [
        'nome' => 'Karatê Kenshydokan',
        'subtitulo' => 'O Caminho do Espírito Guerreiro Inabalável',
        'imagem_capa' => '../img/foto_principal.jpeg',
        'historia' => 'O Karatê Kenshydokan nasceu em Várzea Grande, Mato Grosso, fundado pelo Shihan Jonas Teixeira de Andrade. O estilo surgiu da necessidade de unir a essência espiritual e filosófica das artes marciais clássicas com a eficácia técnica do combate real de contato completo. Desde suas origens humildes, o Kenshydokan expandiu-se e formou campeões mundiais, destacando-se pela seriedade e compromisso de sua metodologia pedagógica e marcial.',
        'filosofia' => 'Baseada no Dojo Kun Kenshydokan, a filosofia do estilo exige o cultivo da benevolência e a contenção da violência. Busca formar cidadãos úteis à sociedade, dotados de um caráter inabalável, respeito mútuo e disciplina rígida. O praticante deve agir com humildade, cortesia e direcionar sua força física sob a tutela da sabedoria.',
        'pilares' => [
            'Atemi Waza' => 'Técnicas de golpes traumáticos utilizando socos, chutes, joelhadas e cotoveladas com forte contato físico.',
            'Nage Waza' => 'Técnicas de desequilíbrio, quedas e projeções derivadas do judô e jiu-jitsu aplicadas ao combate.',
            'Ne Waza' => 'Técnicas de solo, englobando controle posicional, imobilizações e finalizações articulares e estrangulamentos.'
        ],
        'curiosidade' => 'O estilo possui katas próprios denominados "Seishin" (Ichi, Ni, San, Shi, Go), que representam degraus da evolução técnica e espiritual do carateca.'
    ],
    2 => [
        'nome' => 'Judô Kodokan',
        'subtitulo' => 'O Caminho da Suavidade e da Eficiência Máxima',
        'imagem_capa' => '../img/Kano_Jigoro.jpg',
        'historia' => 'Criado por Jigoro Kano em 1882 no Japão, o Judô baseia-se no antigo Ju-Jitsu. Kano removeu as técnicas mais perigosas do jiu-jitsu clássico e transformou a prática em uma atividade educativa, voltada ao aprimoramento físico, intelectual e moral. A Kodokan, primeira escola de judô fundada em Tóquio, tornou-se o centro mundial de desenvolvimento desta arte que hoje é esporte olímpico.',
        'filosofia' => 'O Judô é guiado por dois princípios centrais idealizados por Jigoro Kano: "Seiryoku Zen\'yo" (Uso máximo e eficiente da energia física e mental) e "Jita Kyoei" (Prosperidade e bem-estar mútuos para si e para os outros através da cooperação).',
        'pilares' => [
            'Nage Waza' => 'Técnicas de projeção com o uso do quadril, ombros, pernas e sacrifício para arremessar o oponente ao solo.',
            'Katame Waza' => 'Técnicas de controle no solo, subdivididas em Osaekomi Waza (imobilizações), Shime Waza (estrangulamentos) e Kansetsu Waza (chaves de articulação).',
            'Ukemi Waza' => 'Técnicas de amortecimento de queda, essenciais para proteger o próprio corpo e praticar com segurança.'
        ],
        'curiosidade' => 'A palavra "Judô" é composta pelos ideogramas "Ju" (suavidade ou flexibilidade) e "Do" (caminho ou filosofia).'
    ],
    3 => [
        'nome' => 'Brazilian Jiu-Jitsu (BJJ)',
        'subtitulo' => 'A Arte Suave da Alavancagem e do Controle no Solo',
        'imagem_capa' => '../img/black-belt-894190_640.jpg',
        'historia' => 'O Jiu-Jitsu brasileiro tem suas raízes no Judô Kosen e no Ju-Jitsu tradicional japonês trazido ao Brasil no início do século XX por Mitsuyo Maeda (Conde Koma). A arte foi adaptada e refinada no Rio de Janeiro pela família Gracie (especialmente por Carlos e Hélio Gracie), que modificaram as técnicas enfatizando a luta de chão e a aplicação de alavancas anatômicas.',
        'filosofia' => 'A premissa básica do BJJ é que um lutador menor e mais fraco pode se defender com sucesso contra um agressor maior e mais forte. Isto é alcançado através de técnicas de alavanca, posicionamento inteligente no solo e finalizações, minimizando a necessidade de força bruta.',
        'pilares' => [
            'Guarda' => 'A posição defensiva e ofensiva de solo onde o lutador utiliza as pernas para controlar a distância e desequilibrar o adversário.',
            'Transições e Raspagens' => 'Movimentos estratégicos para passar a guarda do oponente ou inverter a posição de baixo para cima.',
            'Finalizações' => 'Ataques articulares (chaves de braço, perna ou pé) e estrangulamentos que forçam o oponente a desistir batendo três vezes.'
        ],
        'curiosidade' => 'O Jiu-Jitsu brasileiro ganhou enorme notoriedade mundial em 1993, com as vitórias de Royce Gracie nas primeiras edições do UFC.'
    ],
    4 => [
        'nome' => 'Muay Thai',
        'subtitulo' => 'A Arte dos Oito Membros da Tailândia',
        'imagem_capa' => '../img/thaiboxing.jpeg',
        'historia' => 'Originário da Tailândia e descendente do antigo estilo militar Muay Boran, o Muay Thai é uma arte marcial com séculos de história. Foi desenvolvido como um sistema de combate prático para a defesa nacional tailandesa em tempos de guerra. Com o tempo, as regras modernas foram inseridas e o Muay Thai tornou-se um esporte de combate globalizado e altamente dinâmico.',
        'filosofia' => 'Promove resiliência extrema, disciplina férrea, coragem sob pressão e respeito profundo aos mestres. O ritual sagrado do "Wai Kru Ram Muay", dança realizada pelos lutadores antes do combate, serve para homenagear os professores, os pais e a linhagem dos guerreiros.',
        'pilares' => [
            'A Arte dos Oito Membros' => 'Uso coordenado de punhos, cotovelos, joelhos e canelas/pés para desferir golpes ofensivos e defensivos.',
            'Clinch' => 'Técnica de combate em curta distância em pé, onde os lutadores disputam a nuca e o controle do tronco do oponente.',
            'Teep e Chutes Circulares' => 'Chutes frontais rápidos para controle de distância e potentes chutes circulares com a canela voltados às costelas e coxas.'
        ],
        'curiosidade' => 'No Muay Thai tradicional, os lutadores utilizam uma corda trançada sagrada na cabeça chamada "Mongkhon" durante a entrada no ringue.'
    ],
    5 => [
        'nome' => 'Kickboxing',
        'subtitulo' => 'Fusão de Boxe e Chutes em Combate Dinâmico',
        'imagem_capa' => '../img/kickboxing.jpeg',
        'historia' => 'O Kickboxing moderno surgiu de forma independente no Jagão e nos Estados Unidos durante as décadas de 1950 a 1970. Ele se desenvolveu a partir de caratecas de karatê de contato que queriam competir com luvas de boxe e lutadores de boxe ocidental que integraram chutes tradicionais asiáticos. O esporte cresceu mundialmente com organizações proeminentes como o K-1 e a WAKO.',
        'filosofia' => 'Desenvolvimento de alto condicionamento cardiovascular, reflexos rápidos, foco e determinação mental em combate dinâmico. Defende o respeito e o controle emocional tanto dentro quanto fora do tatame.',
        'pilares' => [
            'Boxe Ocidental' => 'Uso avançado de socos (jabs, diretos, cruzados, uppercuts) e esquivas rápidas.',
            'Chutes Dinâmicos' => 'Chutes circulares, frontais, chutes giratórios e chutes baixos (low kicks) herdados do Karatê e do Muay Thai.',
            'Movimentação e Distância' => 'Trabalho de pés (footwork) ágil para atacar e recuar, mantendo o controle espacial do combate.'
        ],
        'curiosidade' => 'Diferente do Muay Thai, as regras clássicas do Kickboxing americano proíbem o uso de cotoveladas e clinches longos.'
    ]
];

// Fallback se o ID não existir
if (!array_key_exists($id_arte, $artes_dados)) {
    $id_arte = 1;
}

$dados = $artes_dados[$id_arte];
?>

<style>
    .arte-header {
        background: linear-gradient(135deg, #1f1f1f 0%, #3d0000 100%), url('<?php echo $dados['imagem_capa']; ?>');
        background-size: cover;
        background-position: center;
        background-blend-mode: overlay;
        color: white;
        padding: 80px 0;
        position: relative;
    }
    .pilar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #dc3545;
        margin-top: 15px;
    }
    .pilar-text {
        font-size: 0.95rem;
        color: #495057;
    }
</style>

<div class="arte-header text-center">
    <div class="container position-relative" style="z-index: 2;">
        <h1 class="font-weight-bold text-uppercase display-4 mb-2"><?php echo htmlspecialchars($dados['nome']); ?></h1>
        <p class="lead font-italic text-white-50"><?php echo htmlspecialchars($dados['subtitulo']); ?></p>
        <div class="mt-4">
            <a href="inicio.php" class="btn btn-outline-light rounded-pill px-4 font-weight-bold shadow-sm">
                <i class="fa-solid fa-home mr-2"></i>Ir para o Início
            </a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 pr-lg-5">
            <!-- História -->
            <section class="mb-5">
                <h3 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-book-open text-danger mr-2"></i>História e Origem
                </h3>
                <p class="text-justify text-muted leading-relaxed" style="font-size: 1.05rem; line-height: 1.7;">
                    <?php echo htmlspecialchars($dados['historia']); ?>
                </p>
            </section>

            <!-- Filosofia -->
            <section class="mb-5">
                <h3 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-yin-yang text-danger mr-2"></i>Princípios e Filosofia
                </h3>
                <p class="text-justify text-muted leading-relaxed" style="font-size: 1.05rem; line-height: 1.7;">
                    <?php echo htmlspecialchars($dados['filosofia']); ?>
                </p>
            </section>

            <!-- Pilares Técnicos -->
            <section class="mb-5">
                <h3 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-shield-halved text-danger mr-2"></i>Fundamentos Técnicos
                </h3>
                <div class="row">
                    <?php foreach ($dados['pilares'] as $pilar => $descricao): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm p-4 bg-white rounded-lg">
                                <div class="pilar-title"><i class="fa-solid fa-square-check text-danger mr-2"></i><?php echo htmlspecialchars($pilar); ?></div>
                                <p class="pilar-text mt-2 mb-0"><?php echo htmlspecialchars($descricao); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm bg-dark text-white rounded-lg p-4 mb-4">
                <h5 class="font-weight-bold text-danger mb-3"><i class="fa-solid fa-circle-info mr-2"></i>Curiosidades</h5>
                <p class="small text-white-50 leading-relaxed" style="font-size: 0.9rem;">
                    <?php echo htmlspecialchars($dados['curiosidade']); ?>
                </p>
            </div>

            <div class="card border-0 shadow-sm rounded-lg p-4 bg-white">
                <h5 class="font-weight-bold text-dark mb-3"><i class="fa-solid fa-circle-question mr-2"></i>Quer Praticar?</h5>
                <p class="small text-muted mb-4">
                    Inscreva-se e faça parte do nosso dojô! Oferecemos exames oficiais, registro de filiação de alta qualidade e professores homologados.
                </p>
                <a href="filiar.php" class="btn btn-danger btn-block font-weight-bold rounded-pill shadow-sm">
                    Filiar-se Agora
                </a>
            </div>
        </div>
    </div>
</div>

<?php include "rodape.php"; ?>
