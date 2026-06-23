<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $plano = isset($_POST["plano_selecionado"]) ? trim($_POST["plano_selecionado"]) : "";

    if (empty($plano)) {
        $_SESSION["msg_erro"] = "Plano não selecionado.";
        header("Location: ../views/filiar.php");
        exit();
    }

    $destino = "institutokenshydokan@gmail.com";
    $assunto = "Nova Solicitacao de Filiacao - " . $plano;
    $mensagem_email = "Nova solicitação de filiação recebida pelo site:\n";
    $mensagem_email .= "==================================================\n";
    $mensagem_email .= "PLANO SELECIONADO: " . strtoupper($plano) . "\n";
    $mensagem_email .= "==================================================\n\n";

    $email_resposta = "";

    // Campos específicos para Atleta e Professor (Filiados)
    if ($plano === "Atleta" || $plano === "Professor") {
        $nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : "";
        $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
        $telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : "";
        $data_nascimento = isset($_POST["data_nascimento"]) ? trim($_POST["data_nascimento"]) : "";
        $endereco = isset($_POST["endereco"]) ? trim($_POST["endereco"]) : "";
        $cidade = isset($_POST["cidade"]) ? trim($_POST["cidade"]) : "";
        $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "";
        $dojo = isset($_POST["dojo"]) ? trim($_POST["dojo"]) : "";
        $graduacao = isset($_POST["graduacao"]) ? trim($_POST["graduacao"]) : "";

        if (empty($nome) || empty($email) || empty($telefone)) {
            $_SESSION["msg_erro"] = "Por favor, preencha os campos obrigatórios (Nome, E-mail e Telefone).";
            header("Location: ../views/filiar.php");
            exit();
        }

        $email_resposta = $email;

        $mensagem_email .= "DADOS DO FILIADO:\n";
        $mensagem_email .= "Nome Completo: $nome\n";
        $mensagem_email .= "E-mail: $email\n";
        $mensagem_email .= "Telefone/WhatsApp: $telefone\n";
        $mensagem_email .= "Data de Nascimento: " . (!empty($data_nascimento) ? date("d/m/Y", strtotime($data_nascimento)) : "Não informada") . "\n";
        $mensagem_email .= "Dojo de Origem: $dojo\n";
        $mensagem_email .= "Graduação Atual: $graduacao\n\n";
        
        $mensagem_email .= "ENDEREÇO:\n";
        $mensagem_email .= "Logradouro: $endereco\n";
        $mensagem_email .= "Cidade: $cidade\n";
        $mensagem_email .= "Estado: $estado\n";

    } else {
        // Campos específicos para Dojo e Instituições (Dojos)
        $razao_social = isset($_POST["razao_social"]) ? trim($_POST["razao_social"]) : "";
        $nome_fantasia = isset($_POST["nome_fantasia"]) ? trim($_POST["nome_fantasia"]) : "";
        $cnpj = isset($_POST["cnpj"]) ? trim($_POST["cnpj"]) : "";
        $responsavel = isset($_POST["responsavel"]) ? trim($_POST["responsavel"]) : "";
        $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
        $telefone = isset($_POST["telefone"]) ? trim($_POST["telefone"]) : "";
        $celular = isset($_POST["celular"]) ? trim($_POST["celular"]) : "";
        $cep = isset($_POST["cep"]) ? trim($_POST["cep"]) : "";
        $endereco = isset($_POST["endereco"]) ? trim($_POST["endereco"]) : "";
        $cidade = isset($_POST["cidade"]) ? trim($_POST["cidade"]) : "";
        $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "";

        if (empty($nome_fantasia) || empty($responsavel) || empty($email) || empty($celular)) {
            $_SESSION["msg_erro"] = "Por favor, preencha os campos obrigatórios (Nome do Dojo/Fantasia, Responsável, E-mail e Celular).";
            header("Location: ../views/filiar.php");
            exit();
        }

        $email_resposta = $email;

        $mensagem_email .= "DADOS DO DOJÔ / ENTIDADE:\n";
        $mensagem_email .= "Razão Social: $razao_social\n";
        $mensagem_email .= "Nome Fantasia/Dojo: $nome_fantasia\n";
        $mensagem_email .= "CNPJ: $cnpj\n";
        $mensagem_email .= "Responsável Técnico: $responsavel\n";
        $mensagem_email .= "E-mail de Contato: $email\n";
        $mensagem_email .= "Telefone Comercial: $telefone\n";
        $mensagem_email .= "Celular/WhatsApp: $celular\n\n";

        $mensagem_email .= "LOCALIZAÇÃO:\n";
        $mensagem_email .= "CEP: $cep\n";
        $mensagem_email .= "Endereço: $endereco\n";
        $mensagem_email .= "Cidade: $cidade\n";
        $mensagem_email .= "Estado: $estado\n";
    }

    $mensagem_email .= "\n--------------------------------------------------\n";
    $mensagem_email .= "E-mail gerado automaticamente pelo portal Kenshydokan.\n";

    // Headers do E-mail
    $headers = "From: no-reply@kenshydokan.org.br\r\n";
    if (!empty($email_resposta)) {
        $headers .= "Reply-To: $email_resposta\r\n";
    }
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Tentar enviar o e-mail
    if (mail($destino, $assunto, $mensagem_email, $headers)) {
        $_SESSION["msg_sucesso"] = "Sua solicitação de filiação como <strong>$plano</strong> foi enviada com sucesso! Analisaremos os dados e entraremos em contato.";
    } else {
        $_SESSION["msg_erro"] = "Ocorreu um erro técnico ao tentar enviar sua proposta por e-mail. Por favor, tente novamente ou entre em contato diretamente.";
    }

    header("Location: ../views/filiar.php");
    exit();
} else {
    header("Location: ../views/filiar.php");
    exit();
}
