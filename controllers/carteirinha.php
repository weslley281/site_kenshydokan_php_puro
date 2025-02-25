<?php

// Dados do aluno (poderiam vir de um banco de dados ou formulário)
if (!isset($_GET["nome"]) || !isset($_GET["documento"]) || !isset($_GET["nascimento"]) || !isset($_GET["graduacao"])) {
    die("Dados insuficientes para gerar a carteirinha.");
}

$nome = $_GET["nome"];
$documento = $_GET["documento"];
$nascimento = $_GET["nascimento"];
$graduacao = $_GET["graduacao"];

// Define o tipo de conteúdo como imagem PNG
header("Content-type: image/png");

// Criando uma nova imagem (largura x altura)
$largura = 400;
$altura = 250;
$imagem = imagecreatetruecolor($largura, $altura);

// Definindo cores
$cor_fundo = imagecolorallocate($imagem, 255, 255, 255); // Branco
$cor_texto = imagecolorallocate($imagem, 0, 0, 0); // Preto
$cor_borda = imagecolorallocate($imagem, 200, 0, 0); // Vermelho

// Preenchendo o fundo
imagefilledrectangle($imagem, 0, 0, $largura, $altura, $cor_fundo);

// Criando uma borda
imagerectangle($imagem, 1, 1, $largura - 2, $altura - 2, $cor_borda);

// Adicionando textos na imagem
$fonte = 5; // Fonte interna do GD
imagestring($imagem, $fonte, 20, 20, "Karate de Contato Kenshydokan", $cor_texto);
imagestring($imagem, $fonte, 20, 50, "Nome: $nome", $cor_texto);
imagestring($imagem, $fonte, 20, 80, "Documento: $documento", $cor_texto);
imagestring($imagem, $fonte, 20, 110, "Nascimento: $nascimento", $cor_texto);
imagestring($imagem, $fonte, 20, 140, "Graduacao: $graduacao", $cor_texto);

// Carregar a logo (deve estar na mesma pasta ou fornecer o caminho correto)
$logo_path = "../img/kenshydokan_pequeno.png"; // Alterar para o nome correto do arquivo
if (file_exists($logo_path)) {
    $logo = imagecreatefrompng($logo_path); // Para PNG
    // $logo = imagecreatefromjpeg($logo_path); // Use esta linha para JPG

    // Obtendo dimensões da logo
    list($logo_largura, $logo_altura) = getimagesize($logo_path);

    // Definindo tamanho e posição da logo
    $nova_largura = 80; // Ajuste conforme necessário
    $nova_altura = 80;
    $pos_x = $largura - $nova_largura - 10; // Margem da direita
    $pos_y = 10; // Margem superior

    // Redimensionar e copiar a logo para a carteirinha
    imagecopyresampled($imagem, $logo, $pos_x, $pos_y, 0, 0, $nova_largura, $nova_altura, $logo_largura, $logo_altura);
}

// Gerando a imagem na tela
imagepng($imagem);
imagedestroy($imagem);

//http://localhost/site_kenshydokan_php_puro/controllers/carteirinha.php?nome=Weslley%20H.%20V.%20Ferraz&documento=*********&nascimento=12-12-24&graduacao=faixa%20preta