<?php
// controllers/usuarioController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/usuarioModel.php";
    include_once "../models/imagemModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $usuarioModel = new Usuario();
        $imagemModel = new Imagem();

        // Apenas Sensei (Administrador) pode inserir ou alterar dados globais
        if (in_array($_POST["tipo"], ["inserir", "edidar_admin", "deletar"]) && $_SESSION["nivel"] !== "sensei") {
            exibirMensagemEredirecionar("Acesso negado.", "../views/login.php");
        }

        if ($_POST["tipo"] == "inserir") {
            $email = mysqli_real_escape_string($usuarioModel->getConnection(), strtolower(trim($_POST["email"])));
            
            if (Usuario::buscarUsuarioPorEmail($email)) {
                exibirMensagemEredirecionar("Erro: Usuário já cadastrado com este e-mail", '../views/admin/index.php?pagina=usuarios');
            } else {
                // Geração automática de senha provisória de 8 caracteres
                $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
                $senhaProvisoria = '';
                for ($i = 0; $i < 8; $i++) {
                    $senhaProvisoria .= $caracteres[rand(0, strlen($caracteres) - 1)];
                }
                
                $senhaSegura = password_hash($senhaProvisoria, PASSWORD_DEFAULT);
                $id_imagem = null;

                // Upload de imagem opcional
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK && $_FILES['imagem']['size'] > 0) {
                    $diretorioUpload = "../img/";
                    $extensaoImagem = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));

                    if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png", "webp"])) {
                        $nomeImagem = uniqid() . '.' . $extensaoImagem;
                        $caminho = $diretorioUpload . $nomeImagem;

                        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                            $novaImagem = new Imagem($nomeImagem, $caminho, $dataMudanca);
                            if ($imagemModel->registrar_imagem($novaImagem)) {
                                $id_imagem = Imagem::procura_id_imagem($nomeImagem);
                            }
                        }
                    }
                }

                $nivel = $_POST["nivel"] ?? 'kohai'; // sensei, sempai, kohai
                $id_fil = !empty($_POST["id_fil"]) ? intval($_POST["id_fil"]) : null;

                // Instancia o novo usuário com primeiro_acesso = 1
                $novoUsuario = new Usuario($_POST["nome"], $id_fil, $id_imagem, $email, $_POST["telefone"], $dataMudanca, $senhaSegura, $nivel);

                if ($usuarioModel->criarUsuario($novoUsuario)) {
                    // Envia email com a senha provisória
                    $assunto = "=?UTF-8?B?" . base64_encode("Acesso ao Sistema - Gerenciador de Dojô") . "?=";
                    
                    $mensagemHtml = "
                    <html>
                    <head>
                      <title>Acesso ao Sistema</title>
                      <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; padding: 20px; }
                        .container { max-width: 600px; background-color: #white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-top: 5px solid #dc3545; }
                        h2 { color: #dc3545; }
                        .senha { font-size: 20px; font-weight: bold; background-color: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; display: inline-block; margin: 15px 0; letter-spacing: 1px; }
                        .footer { margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 15px; }
                      </style>
                    </head>
                    <body>
                      <div class='container'>
                        <h2>Olá, " . htmlspecialchars($_POST["nome"]) . "!</h2>
                        <p>Você foi cadastrado como operador no <strong>Gerenciador de Dojô</strong>.</p>
                        <p>Abaixo estão suas credenciais para o primeiro acesso:</p>
                        <p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>
                        <p><strong>Senha Provisória:</strong></p>
                        <div class='senha'>{$senhaProvisoria}</div>
                        <p>Por favor, acesse o sistema e altere sua senha no primeiro acesso.</p>
                        <a href='http://" . $_SERVER['HTTP_HOST'] . "/gerenciador_dojo/views/login.php' style='display: inline-block; background-color: #dc3545; color: #fff; text-decoration: none; padding: 12px 25px; border-radius: 50px; font-weight: bold; margin-top: 15px;'>Acessar o Sistema</a>
                        <div class='footer'>
                          <p>Este é um e-mail automático. Por favor, não responda.</p>
                        </div>
                      </div>
                    </body>
                    </html>
                    ";

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: Gerenciador de Dojo <noreply@" . $_SERVER['HTTP_HOST'] . ">\r\n";

                    $emailEnviado = false;
                    try {
                        $emailEnviado = mail($email, $assunto, $mensagemHtml, $headers);
                    } catch (Throwable $e) {
                        error_log("Erro ao enviar email de novo usuario: " . $e->getMessage());
                    }

                    if ($emailEnviado) {
                        $msg = "Usuário criado com sucesso!\\n\\nA senha temporária foi enviada para o e-mail: {$email}.\\n\\nSENHA TEMPORÁRIA GERADA: {$senhaProvisoria}";
                    } else {
                        $msg = "Usuário criado com sucesso, mas ocorreu uma falha ao enviar o e-mail.\\n\\nSENHA TEMPORÁRIA GERADA: {$senhaProvisoria}\\n\\nCopie e envie esta senha manualmente para o usuário.";
                    }
                    exibirMensagemEredirecionar($msg, '../views/admin/index.php?pagina=usuarios');
                } else {
                    exibirMensagemEredirecionar("Erro: Usuário não pôde ser cadastrado.", '../views/admin/index.php?pagina=usuarios');
                }
            }
        } elseif ($_POST["tipo"] == "edidar_admin") {
            $id_usuario = intval($_POST["id_usuario"]);
            $nome = $_POST["nome"];
            $id_fil = !empty($_POST["id_fil"]) ? intval($_POST["id_fil"]) : null;
            $id_imagem = !empty($_POST["id_imagem"]) ? intval($_POST["id_imagem"]) : null;
            $nivel = $_POST["nivel"];
            $email = strtolower(trim($_POST["email"]));
            $telefone = $_POST["telefone"];

            $usuarioEditado = new Usuario($nome, $id_fil, $id_imagem, $email, $telefone, $dataMudanca, null, $nivel);
            
            if ($usuarioModel->editarUsuario($id_usuario, $usuarioEditado)) {
                exibirMensagemEredirecionar("Usuário atualizado com sucesso.", '../views/admin/index.php?pagina=usuarios');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=usuarios');
            }
        } elseif ($_POST["tipo"] == "editar_senha") {
            $id_usuario = intval($_POST["id_usuario"]);
            $nova_senha = $_POST["nova_senha"];
            $senha2 = $_POST["senha2"];

            if ($id_usuario !== intval($_SESSION["id_usuario"]) && $_SESSION["nivel"] !== "sensei") {
                exibirMensagemEredirecionar("Acesso negado.", "../views/login.php");
            }

            if ($nova_senha !== $senha2) {
                exibirMensagemEredirecionar("Erro: As senhas digitadas não coincidem.", $_SESSION["nivel"] === 'sensei' ? '../views/admin/index.php?pagina=usuarios' : '../views/dashboard_aluno.php');
                exit();
            }

            $senhaSegura = password_hash($nova_senha, PASSWORD_DEFAULT);

            if ($usuarioModel->editarSenhaUsuario($id_usuario, $senhaSegura)) {
                // Reseta flag de primeiro acesso se o próprio usuário estiver alterando
                if ($id_usuario === intval($_SESSION["id_usuario"])) {
                    $usuarioModel->getConnection()->query("UPDATE usuarios SET primeiro_acesso = 0 WHERE id_usuario = " . $id_usuario);
                }
                exibirMensagemEredirecionar("Senha atualizada com sucesso.", $_SESSION["nivel"] === 'sensei' ? '../views/admin/index.php?pagina=usuarios' : '../views/dashboard_aluno.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, $_SESSION["nivel"] === 'sensei' ? '../views/admin/index.php?pagina=usuarios' : '../views/dashboard_aluno.php');
            }
        } elseif ($_POST["tipo"] == "deletar") {
            $id_usuario = intval($_POST["id_usuario"]);

            if (Usuario::excluirUsuario($id_usuario)) {
                exibirMensagemEredirecionar("Usuário excluído com sucesso", '../views/admin/index.php?pagina=usuarios');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=usuarios');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=usuarios');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/login.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>alert('$mensagem'); window.location='$destino';</script>";
    exit;
}
?>
