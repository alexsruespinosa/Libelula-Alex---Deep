<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $mensaje = htmlspecialchars($_POST['message']);

    // Destinatario
    $to = "hello@libelulacreations.com";

    // Asunto del correo
    $subject = "Nuevo mensaje desde el formulario: $subject";

    // Cuerpo del mensaje
    $body = "
    Has recibido un nuevo mensaje desde tu sitio web:
    
    Nombre: $nombre
    Correo: $email
    Asunto: $subject
    Mensaje:
    $mensaje
    ";

    // Cabeceras
    $headers = "From: $nombre <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Tu mensaje fue enviado correctamente. ¡Gracias por contactarnos!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Hubo un error al enviar tu mensaje. Intenta nuevamente.'); window.history.back();</script>";
    }
} else {
    echo "Método no permitido.";
}
?>