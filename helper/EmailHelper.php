<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
    class EmailHelper {
        private $mail;

        public function __construct() {
            $this->mail = new PHPMailer(true); // True para habilitar excepciones
        }

        public function enviarCorreo($email, $token) {
            try {
                $this->mail->isSMTP();
                $this->mail->Host = 'in-v3.mailjet.com'; // Cambiar por el servidor SMTP que estás utilizando
                $this->mail->SMTPAuth = true;
                $this->mail->Username = 'e293e30ca05f90de9a1b30d727172378'; // Cambiar por tu dirección de correo
                $this->mail->Password = '69f4dbef4fa45d63ddd1846f9bb8b38f';
                $this->mail->SMTPSecure = 'tls';
                $this->mail->Port = 587;


                $this->mail->setFrom('ignaciofrancoinfo@gmail.com', 'Administracion Preguntados');
                $this->mail->addAddress($email);

                $this->mail->isHTML(true);
                $this->mail->Subject = 'Confirmacion de registro';
                $this->mail->Body = "Hola, gracias por registrarte en nuestro sitio. Haz clic en el siguiente enlace para activar tu cuenta: <a href='http://localhost:8080/registro/validar?token=$token'>Activar cuenta</a>";

                $this->mail->send();
                return true;


            }
            catch (Exception $e) {

                error_log('Error al enviar correo: ' .$e->getMessage());
                return false;
            }

        }


    }