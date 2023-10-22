<?php

function contar_pagina($url_atual)
{
    // Obtém o caminho da URL atual
    $url_atual = trim($url_atual, '/');

    // Substitua caracteres inválidos por underscores (ou outra forma que preferir)
    $nome_do_arquivo = str_replace('/', '_', $url_atual);

    // Define o nome do diretório onde você deseja armazenar os arquivos de contador
    $contador_directory = "contador/";

    // Verifique se o diretório existe e crie-o se não existir
    if (!is_dir($contador_directory)) {
        mkdir($contador_directory, 0755, true);
    }

    // Crie o caminho completo do arquivo
    $nome_arquivo = $contador_directory . "contagem_visualizacoes_" . $nome_do_arquivo . ".txt";

    if (file_exists($nome_arquivo)) {
        $view_count = (int) file_get_contents($nome_arquivo);
        $view_count += 1;
    } else {
        $view_count = 1;
    }

    file_put_contents($nome_arquivo, $view_count);
}
