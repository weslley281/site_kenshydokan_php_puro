<?php
function contar_pagina($url_atual)
{
    // Obtém o caminho da URL atual
    $url_atual = trim($url_atual, '/');

    // Substitua caracteres inválidos por underscores (ou outra forma que preferir)
    $nome_do_arquivo = str_replace('/', '_', $url_atual);

    // Define o nome do arquivo JSON onde você deseja armazenar os dados
    $contador_json = "contador.json";

    // Verifique se o arquivo JSON existe ou crie um novo array vazio
    if (file_exists($contador_json)) {
        $data = json_decode(file_get_contents($contador_json), true);
    } else {
        $data = array();
    }

    // Obtém a data atual
    $data_atual = date("Y-m-d H:i:s");

    // Verifica se já existe um registro para a página atual
    if (isset($data[$url_atual])) {
        // Atualiza o registro existente com a data atual
        $data[$url_atual]['data'] = $data_atual;
        $data[$url_atual]['contagem']++;
    } else {
        // Cria um novo registro para a página atual
        $data[$url_atual] = array(
            'data' => $data_atual,
            'contagem' => 1,
            // Outras informações relevantes podem ser adicionadas aqui
        );
    }

    // Salva os dados de volta no arquivo JSON
    file_put_contents($contador_json, json_encode($data));
}
