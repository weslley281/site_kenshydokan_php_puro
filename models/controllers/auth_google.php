<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// Configurações das credenciais do Google
// Substitua com as credenciais obtidas no Console do Google
// ==========================================
define('GOOGLE_CLIENT_ID', '81640487042-u7tuof4c9c2ufrhvm239cjg1bmgq3fga.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'https://kenshydokan.org.br/controllers/auth_google.php');

// Endpoint de Autorização do Google
$oauth_url = 'https://accounts.google.com/o/oauth2/v2/auth';

// 1. Se NÃO recebeu o parâmetro 'code' do Google, inicia o redirecionamento
if (!isset($_GET['code'])) {
    // Parâmetros para solicitar autorização
    $params = [
        'client_id'     => GOOGLE_CLIENT_ID,
        'redirect_uri'  => GOOGLE_REDIRECT_URI,
        'response_type' => 'code',
        'scope'         => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        'access_type'   => 'offline',
        'prompt'        => 'select_account'
    ];

    // Redireciona o usuário para a página de consentimento do Google
    header('Location: ' . $oauth_url . '?' . http_build_query($params));
    exit();
}

// 2. Se recebeu o 'code', faz a troca pelo Token de Acesso
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Endpoint para troca de token
    $token_url = 'https://oauth2.googleapis.com/token';

    // Parâmetros da requisição POST
    $post_data = [
        'code'          => $code,
        'client_id'     => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri'  => GOOGLE_REDIRECT_URI,
        'grant_type'    => 'authorization_code'
    ];

    // Faz a requisição POST via cURL
    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);

    if (isset($token_data['access_token'])) {
        $access_token = $token_data['access_token'];

        // Busca as informações do perfil do usuário no Google
        $userinfo_url = 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $access_token;

        $ch = curl_init($userinfo_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $user_response = curl_exec($ch);
        curl_close($ch);

        $google_user = json_decode($user_response, true);

        if (isset($google_user['email'])) {
            $email = $google_user['email'];

            // Carrega o modelo de Usuário
            include_once "../models/usuarioModel.php";

            // Busca o usuário pelo e-mail
            $usuario = Usuario::buscarUsuarioPorEmail($email);

            if ($usuario) {
                // Usuário encontrado! Inicia a sessão no sistema
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nome']       = $usuario['nome'];
                $_SESSION['nivel']      = $usuario['nivel'];
                $_SESSION['id_fil']     = $usuario['id_fil'];

                header('Location: ../views/perfil/perfil.php');
                exit();
            } else {
                // E-mail não cadastrado no banco do site
                $msg = "O e-mail do Google ($email) nao esta cadastrado no sistema. Por favor, cadastre-se primeiro no site.";
                echo "<script language='javascript'>window.alert('$msg');</script>";
                echo "<script language='javascript'>window.location='../views/login.php';</script>";
                exit();
            }
        }
    }

    // Se falhar na troca de token ou dados do perfil
    $msg = "Falha na autenticacao com a conta do Google.";
    echo "<script language='javascript'>window.alert('$msg');</script>";
    echo "<script language='javascript'>window.location='../views/login.php';</script>";
    exit();
}
