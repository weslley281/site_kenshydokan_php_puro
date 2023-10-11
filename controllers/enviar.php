<?php
// Check for empty fields
if(empty($_POST['name'])      ||
   empty($_POST['email'])     ||
   empty($_POST['phone'])     ||
   empty($_POST['message'])   ||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
   echo "<script language='javascript'>window.alert('Preencha todos os campos'); </script>";
   echo "<script language='javascript'>window.location='../contato.php'; </script>";
   return false;
   }
   
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));
   
// Crie o email e envie a mensagem
$to = 'kenshydokan@gmail.com'; // Adicione seu endereço de e-mail entre '' substituindo seunome@seudominio.com - É para aqui que o formulário enviará uma mensagem para.
$email_subject = "Contato do Site:  $name";
$email_body = "Você recebeu uma nova mensagem do formulário de contato do seu site.\n\n"."Aqui estão os detalhes:\n\nNome: $name\n\nEmail: $email_address\n\nTelefone: $phone\n\nMensagem:\n$message";
$headers = "From: weslleyhenrique800@hotmail.com\n"; // Este é o endereço de email a partir do qual a mensagem gerada será. Recomendamos o uso de algo como noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";   
mail($to,$email_subject,$email_body,$headers);
echo "<script language='javascript'>window.alert('Mensagem Enviada com sucesso'); </script>";
echo "<script language='javascript'>window.location='../contato.php'; </script>";
return true;
?>
