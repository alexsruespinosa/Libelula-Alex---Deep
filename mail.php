<?php


use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // Carga Composer

// Cargar .env
// --- Cargar variables del archivo .env manualmente --
function loadEnv($path)
{
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}
loadEnv(__DIR__ . '/.env');

// Variables (En archivo .env   en la raíz del proyecto)
$smtp_host = $_ENV['SMTP_HOST'];
$smtp_user = $_ENV['SMTP_USER'];
$smtp_pass = $_ENV['SMTP_PASS'];
$smtp_port = 465; // puedes probar también 587 si este falla
$smtp_secure = 'ssl'; // usa 'tls' si pruebas con 587

// Función limpiar
function limpiar($dato) {
  return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

$nombre = limpiar($_POST['nombre'] ?? '');
$email = limpiar($_POST['email'] ?? '');
$subject = limpiar($_POST['subject'] ?? '');
$message = limpiar($_POST['message'] ?? '');

if (!$nombre || !$email || !$subject || !$message) {
  die('Veuillez remplir tous les champs.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  die('Adresse courriel invalide.');
}

$mail = new PHPMailer(true);

try {
  // --- CONFIGURACIÓN ---
  // Usa SMTP si el puerto está definido, si no usa sendmail (modo automático)
  if ($smtp_host && $smtp_port) {
    $mail->isSMTP();
    $mail->Host = $smtp_host;
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    $mail->Port = $smtp_port;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
  } else {
    $mail->isSendmail();
  }

  // Opciones SSL (útil en servidores compartidos)
  $mail->SMTPOptions = [
    'ssl' => [
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true,
    ],
  ];

  // --- CONTENIDO ---
  $mail->setFrom($smtp_user ?: $smtp_user, 'Formulaire Web - Libélula');
  $mail->addAddress($smtp_user, 'Libélula Créations');
  $mail->addReplyTo($email, $nombre);
  $mail->isHTML(true);
  $mail->Subject = "[Libelula] " . $subject;
  $mail->Body = "
    <h2>Nouveau message depuis le site web</h2>
    <p><strong>Nom:</strong> {$nombre}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Message:</strong><br>{$message}</p>
  ";
  $mail->AltBody = "Nom: {$nombre}\nEmail: {$email}\nMessage:\n{$message}";

  $mail->send();

  echo "<script>alert('Message envoyé avec succès!'); window.location='index.html';</script>";
  header('Location: mailsuccess.html');
  exit;
  echo "Erreur: le message n'a pas pu être envoyé. (" . $mail->ErrorInfo . ")";
} catch (Exception $e) {
    echo '❌ Error: no se pudo enviar el correo.<br>';
    echo 'PHPMailer dice: ' . $mail->ErrorInfo;
}
?>
