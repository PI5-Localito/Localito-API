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

    public function sendMail(string $email): void
    {
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
            $mail->addAddress($email); // Agrega el destinatario
            $mail->Subject = 'Registro';
            $mail->Body = 'Bienvenido a Localito!!!';

            $mail->send();
        }catch (Exception $e) {
            dd($mail->ErrorInfo);
        }
    }
}
