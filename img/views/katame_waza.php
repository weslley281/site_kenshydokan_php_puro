<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katame Waza - Técnicas de Controle</title>
    <!-- Adicionando Bootstrap CSS -->
    <link rel="stylesheet" href="../libs/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .technique-block {
            margin-bottom: 40px;
        }
        .technique-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <!-- Se você tiver um menu, inclua ele aqui. Ex: <?php include 'menu.php'; ?> -->

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1>Katame Waza (固技)</h1>
            <p class="lead">Técnicas de controle, imobilização e submissão no solo.</p>
        </div>

        <!-- Técnica 1: Osaekomi-waza -->
        <div class="row align-items-center technique-block">
            <div class="col-md-5">
                <img src="../img/jiu-jitsu-2184597_640.jpg" alt="Osaekomi-waza" class="technique-img">
            </div>
            <div class="col-md-7">
                <h4>Osaekomi-waza (押込技): Imobilizações</h4>
                <p>
                    São técnicas fundamentais de controle no solo, onde o objetivo é manter o oponente imobilizado de costas no chão por um determinado período. O controle sobre o quadril e os ombros do adversário é essencial. Exemplos clássicos incluem Kesa-gatame, Yoko-shiho-gatame e Kami-shiho-gatame.
                </p>
            </div>
        </div>

        <!-- Técnica 2: Shime-waza -->
        <div class="row align-items-center technique-block">
            <div class="col-md-7 order-md-1">
                <h4>Shime-waza (絞技): Estrangulamentos</h4>
                <p>
                    Técnicas que visam forçar a submissão através da aplicação de pressão nas artérias do pescoço (estrangulamento sanguíneo) ou na traqueia (estrangulamento respiratório). Requerem grande precisão e controle para serem aplicadas de forma segura e eficaz. Exemplos incluem Hadaka-jime e Sankaku-jime.
                </p>
            </div>
            <div class="col-md-5 order-md-2">
                <img src="../arquivos/estrangulamento.jpg" alt="Shime-waza" class="technique-img">
            </div>
        </div>

        <!-- Técnica 3: Kansetsu-waza -->
        <div class="row align-items-center technique-block">
            <div class="col-md-5">
                <img src="../arquivos/articulacao.jpg" alt="Kansetsu-waza" class="technique-img">
            </div>
            <div class="col-md-7">
                <h4>Kansetsu-waza (関節技): Chaves de Articulação</h4>
                <p>
                    São técnicas que forçam a submissão através da hiperextensão ou torção de uma articulação (cotovelo, ombro, joelho, etc.), levando-a ao seu limite. O controle do corpo do oponente é vital para isolar a articulação a ser atacada. Exemplos famosos são o Juji-gatame (chave de braço) e o Ude-garami.
                </p>
            </div>
        </div>

        <!-- Seção de Comparação -->
        <div class="comparison-section" style="background-color: #e9ecef; padding: 30px; border-radius: 8px; margin-top: 40px;">
            <h2 class="text-center mb-4">Katame Waza vs. Ne Waza</h2>
            <p>
                Embora os termos sejam frequentemente usados de forma intercambiável, existe uma diferença sutil entre <strong>Katame Waza</strong> e <strong>Ne Waza</strong>.
            </p>
            <p>
                <strong>Ne Waza (寝技)</strong> é um termo mais amplo que se refere a todo o conjunto de 'técnicas de solo'. Ele engloba não apenas as finalizações, mas também as passagens de guarda, as raspagens, as reversões e toda a movimentação e estratégia de luta no chão.
            </p>
            <p>
                <strong>Katame Waza (固技)</strong>, por outro lado, é uma categoria específica dentro do Ne Waza. Refere-se estritamente às 'técnicas de controle e finalização', que são as três que vimos nesta página: imobilizações (Osaekomi), estrangulamentos (Shime) e chaves de articulação (Kansetsu).
            </p>
            <p class="text-center">Em resumo: Todo <strong>Katame Waza</strong> é <strong>Ne Waza</strong>, mas nem todo <strong>Ne Waza</strong> é <strong>Katame Waza</strong>.</p>
        </div>

    </div>

    <div class="container my-4">
        <!-- Se você tiver um rodapé, inclua ele aqui. Ex: <?php include 'rodape.php'; ?> -->
    </div>

    <!-- Adicionando Bootstrap JS e dependências -->
    <script src="../libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
