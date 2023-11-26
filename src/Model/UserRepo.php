<?php

namespace App\Model;

use App\Entity\User;
use Lib\Storage\AbstractModel;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class UserRepo extends AbstractModel
{
    public function getByEmail(string $email): ?User
    {
        $entity = new $this->entity();
        $entity->email = $email;

        $data = $this->queryBind(
            "SELECT * FROM {$this->getTable()} WHERE email = :email",
            $entity,
            $entity->includeMapping(['email']),
        );

        return $data[0] ?? null;
    }

    public function sendMail(string $email, string $name): void
    {
        $img = 'https://media.discordapp.net/attachments/1145467363974709268/1145470025717784636/imagen.png?ex=656cf317&is=655a7e17&hm=056f773d9c5f8150799f049043066f94005510493d3051978a640b5eb2fc9685&';
        $localitoMail = "localitosoporte@hotmail.com";
        $mail = new PHPMailer(true);
        
        try{
            $mail->isSMTP();
            $mail->Host = "smtp.office365.com";
            $mail->SMTPAuth = true;
            $mail->Username = $localitoMail;
            $mail->Password = '123Tamarindo@';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($localitoMail, 'Localito');
            $mail->addAddress($email);
            $mail->Subject = 'Registro';
            $mail->Body = '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <title>Bienvenid@ a Localito</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f5f5f5;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #fff;
                            padding: 30px;
                            border-radius: 5px;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        }
                        h1 {
                            color: #333;
                        }
                        p {
                            color: #555;
                        }
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            background-color: #ff6f61;
                            color: #fff;
                            text-decoration: none;
                            border-radius: 4px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <img style="border-radius: 50%; max-width: 20dp" src=' . $img . '>
                        <h1>Bienvenid@ a Localito</h1>
                        <p>¡Hola ' . $name . '!</p>
                        <p>Gracias por unirte a nuestra plataforma. Estamos emocionados de tenerte como parte de nuestra comunidad.</p>
                        <p>Con nuestra aplicación, podrás:</p>
                        <ul>
                            <li>Explorar una amplia variedad de productos</li>
                            <li>Publicar tus propios artículos para la venta</li>
                            <li>Interactuar con otros usuarios</li>
                            <li>¡Y mucho más!</li>
                        </ul>
                        <p>¡Esperamos que disfrutes de la experiencia!</p>
                        <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>
                        <p>¡Gracias de nuevo por unirte a nuestra comunidad!</p>
                        <p>El equipo de Localito</p>
                    </div>
                </body>
                </html>
            ';
            $mail->isHTML(true);
            $mail->send();
        }catch (Exception $e) {
            dd($mail->ErrorInfo);
        }
    }
}
