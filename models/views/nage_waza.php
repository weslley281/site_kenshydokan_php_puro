<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nage Waza - Técnicas de Projeção</title>
    <!-- Adicionando Bootstrap CSS -->
    <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .principle-block {
            margin-bottom: 40px;
        }

        .principle-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .comparison-section {
            background-color: #e9ecef;
            padding: 30px;
            border-radius: 8px;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <!-- Se você tiver um menu, inclua ele aqui. Ex: <?php include 'menu.php'; ?> -->

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1>Nage Waza (投げ技)</h1>
            <p class="lead">Princípios e fundamentos das técnicas de projeção e arremesso.</p>
        </div>

        <!-- Princípio 1: Kuzushi -->
        <div class="row align-items-center principle-block">
            <div class="col-md-5">
                <img src="../arquivos/pegada.jpg" alt="Princípio do Kuzushi" class="principle-img">
            </div>
            <div class="col-md-7">
                <h4>Kuzushi (崩し): O Desequilíbrio</h4>
                <p>
                    O primeiro e mais crucial princípio. Kuzushi é a arte de quebrar a postura e o equilíbrio do oponente (uke). Sem um desequilíbrio eficaz, a aplicação de qualquer técnica de arremesso se torna uma questão de força bruta. O objetivo é mover o centro de gravidade do oponente para uma posição onde ele possa ser facilmente arremessado.
                </p>
            </div>
        </div>

        <!-- Princípio 2: Tsukuri -->
        <div class="row align-items-center principle-block">
            <div class="col-md-7 order-md-1">
                <h4>Tsukuri (作り): A Preparação</h4>
                <p>
                    Após o desequilíbrio, vem o Tsukuri. Esta é a fase de "entrada" ou preparação, onde você (tori) posiciona seu corpo de forma ideal para executar o arremesso. Envolve o trabalho de pés (sabaki), o posicionamento do quadril e o contato corporal para se encaixar na técnica escolhida.
                </p>
            </div>
            <div class="col-md-5 order-md-2">
                <img src="../arquivos/preparacao.jpg" alt="Princípio do Tsukuri" class="principle-img">
            </div>
        </div>

        <!-- Princípio 3: Kake -->
        <div class="row align-items-center principle-block">
            <div class="col-md-5">
                <img src="../arquivos/arremesso.png" alt="Princípio do Kake" class="principle-img">
            </div>
            <div class="col-md-7">
                <h4>Kake (掛け): A Execução</h4>
                <p>
                    Kake é a fase final: a execução do arremesso. É o momento em que a energia acumulada nas fases de Kuzushi e Tsukuri é liberada para projetar o oponente ao solo de forma controlada. A execução deve ser contínua e fluida, completando o movimento.
                </p>
            </div>
        </div>

        <!-- Seção de Comparação -->
        <div class="comparison-section">
            <h2 class="text-center mb-4">Nage Waza vs. Katame Waza</h2>
            <p>
                A principal diferença entre <strong>Nage Waza</strong> (técnicas de projeção) e <strong>Katame Waza</strong> (técnicas de controle no solo) está no momento e no local do combate.
            </p>
            <p>
                O <strong>Nage Waza</strong> foca em levar o oponente da posição em pé para o chão através de arremessos e quedas. Seu objetivo é finalizar a luta com uma projeção impactante ou obter uma posição dominante no solo.
            </p>
            <p>
                Já o <strong>Katame Waza</strong> começa onde o Nage Waza termina: no chão. Ele engloba técnicas de imobilização (Osaekomi-waza), estrangulamento (Shime-waza) e chaves de articulação (Kansetsu-waza). O objetivo é controlar, subjugar e forçar a submissão do oponente que já se encontra no solo.
            </p>
            <p class="text-center">Em resumo: <strong>Nage Waza</strong> é a arte de derrubar, <strong>Katame Waza</strong> é a arte de dominar no chão.</p>
        </div>

    </div>

    <div class="container my-4">
        <!-- Se você tiver um rodapé, inclua ele aqui. Ex: <?php include 'rodape.php'; ?> -->
    </div>

    <!-- Adicionando Bootstrap JS e dependências -->
    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>