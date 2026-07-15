<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function contar_pagina($url_atual)
{
    // Obtém e limpa o caminho da URL atual
    $url_atual = trim($url_atual, '/');

    // Evita contar acessos repetidos por atualização (F5) na mesma sessão em menos de 10 minutos
    $agora = time();
    if (!isset($_SESSION['paginas_visitadas'])) {
        $_SESSION['paginas_visitadas'] = array();
    }
    if (isset($_SESSION['paginas_visitadas'][$url_atual]) && ($agora - $_SESSION['paginas_visitadas'][$url_atual] < 600)) {
        return;
    }
    $_SESSION['paginas_visitadas'][$url_atual] = $agora;

    // Registra a visualização no banco de dados MySQL
    try {
        include_once __DIR__ . "/../db/conexao.php";
        if (class_exists('Conexao')) {
            $db = new Conexao();
            $conexao = $db->conectar();
            if ($conexao) {
                // Cria a tabela se não existir
                $conexao->query("CREATE TABLE IF NOT EXISTS visualizacoes_paginas (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    caminho VARCHAR(255) NOT NULL,
                    data_acesso DATETIME NOT NULL,
                    INDEX (caminho),
                    INDEX (data_acesso)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

                // Importa dados de históricos contador.json legados (executa apenas uma vez)
                importar_json_historico($conexao);

                // Registra o novo acesso
                $data_atual = date("Y-m-d H:i:s");
                $stmt = $conexao->prepare("INSERT INTO visualizacoes_paginas (caminho, data_acesso) VALUES (?, ?)");
                if ($stmt) {
                    $stmt->bind_param("ss", $url_atual, $data_atual);
                    $stmt->execute();
                    $stmt->close();
                }
                $conexao->close();
            }
        }
    } catch (Throwable $t) {
        // Falha silenciosa para não interromper a navegação em caso de falha de DB
        error_log("Erro ao contar pagina: " . $t->getMessage());
    }
}

function importar_json_historico($conexao)
{
    // Caminhos prováveis onde os arquivos contador.json podem ter sido gerados
    $caminhos_json = [
        __DIR__ . "/../views/admin/contador.json",
        __DIR__ . "/../views/perfil/contador.json",
        __DIR__ . "/../views/contador.json",
        __DIR__ . "/contador.json",
        "contador.json"
    ];

    foreach ($caminhos_json as $caminho_arquivo) {
        if (file_exists($caminho_arquivo)) {
            try {
                $json_content = file_get_contents($caminho_arquivo);
                $json_data = json_decode($json_content, true);
                if (is_array($json_data)) {
                    foreach ($json_data as $url => $info) {
                        $url_limpa = trim($url, '/');
                        $contagem = isset($info['contagem']) ? (int)$info['contagem'] : 0;
                        $data = isset($info['data']) ? $info['data'] : date("Y-m-d H:i:s");

                        if ($contagem > 0) {
                            $stmt = $conexao->prepare("INSERT INTO visualizacoes_paginas (caminho, data_acesso) VALUES (?, ?)");
                            if ($stmt) {
                                for ($i = 0; $i < $contagem; $i++) {
                                    $stmt->bind_param("ss", $url_limpa, $data);
                                    $stmt->execute();
                                }
                                $stmt->close();
                            }
                        }
                    }
                }
                // Renomeia o arquivo importado para evitar duplicar a importação
                rename($caminho_arquivo, $caminho_arquivo . ".bak");
            } catch (Throwable $t) {
                error_log("Erro ao importar json de visualizações: " . $t->getMessage());
            }
        }
    }
}
