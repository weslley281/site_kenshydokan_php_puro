<?php
// Check for empty fields
if (
   empty($_POST['nome'])      ||
   empty($_POST['email'])     ||
   empty($_POST['rg'])     ||
   empty($_POST['graduacao_atual'])     ||
   empty($_POST['graduacao_pretendida'])     ||
   empty($_POST['professor'])     ||
   //empty($_POST['message'])   ||
   !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
   echo "<script language='javascript'>window.alert('Preencha todos os campos'); </script>";
   echo "<script language='javascript'>window.location='../views/perfil/exame_graduacao.php'; </script>";
   return false;
}

$nome = strip_tags(htmlspecialchars($_POST['nome']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$rg = strip_tags(htmlspecialchars($_POST['rg']));
$graduacao_atual = strip_tags(htmlspecialchars($_POST['graduacao_atual']));
$graduacao_pretendida = strip_tags(htmlspecialchars($_POST['graduacao_pretendida']));
$professor = strip_tags(htmlspecialchars($_POST['professor']));
$message = strip_tags(htmlspecialchars($_POST['message']));

// Crie o email e envie a mensagem
$to = 'kenshydokan@gmail.com'; // Adicione seu endereço de e-mail entre '' substituindo seunome@seudominio.com - É para aqui que o formulário enviará uma mensagem para.
$email_subject = "Inscrição no Exame de Graduação:  $nome";
$email_body = "Você recebeu uma nova inscrição para exame de Graduação no seu site.\n\n" . "Aqui estão os detalhes:\n\nNome: $nome\n\nEmail: $email_address\n\nrg: $rg\n\ngraduacao_atual: $graduacao_atual\n\nGraduacao_pretendida: $graduacao_pretendida\n\nProfessor: $professor\n\nMensagem:\n$message";
$headers = "From: $email_address\n"; // Este é o endereço de email a partir do qual a mensagem gerada será. Recomendamos o uso de algo como noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";
mail($to, $email_subject, $email_body, $headers);
echo "<script language='javascript'>window.alert('Mensagem Enviada com sucesso'); </script>";
echo "<script language='javascript'>window.location='../views/perfil/perfil.php'; </script>";
return true;