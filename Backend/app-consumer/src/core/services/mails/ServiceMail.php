<?php

namespace app_consumer\core\services\mails;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class ServiceMail implements ServiceMailInterface
{
    private Mailer $mailer;
    private string $from;

    public function __construct(Mailer $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function sendMail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }

    public function notifyCreateGame(string $to, array $gameData): void
    {
        $subject = "🎮 Nouvelle partie GeoQuizz créée !";

        $content = "
        <html>
        <body style='font-family: Arial, sans-serif; color: #333;'>
            <h2 style='color: #2563eb;'>Bonjour {$gameData['user']['nickname']} !</h2>
            
            <p>Votre nouvelle partie GeoQuizz a été créée avec succès.</p>
            
            <div style='background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <h3 style='margin-top: 0;'>Détails de la partie :</h3>
                <ul style='list-style: none; padding-left: 0;'>
                    <li>🎲 ID de la partie : {$gameData['game']['id']}</li>
                    <li>📸 Nombre de photos : " . count($gameData['game']['photoIds']) . "</li>
                    <li>🎯 Score actuel : {$gameData['game']['score']}</li>
                    <li>📍 État : {$gameData['game']['state']}</li>
                </ul>
            </div>

            <p>Pour commencer à jouer, cliquez sur le lien ci-dessous :</p>
            <a href='http://localhost:5173/game/{$gameData['game']['id']}' 
               style='background: #2563eb; color: white; padding: 10px 20px; 
                      text-decoration: none; border-radius: 5px; display: inline-block;'>
                Démarrer la partie
            </a>

            <p style='margin-top: 20px; color: #666; font-size: 0.9em;'>
                Bonne chance !<br>
                L'équipe GeoQuizz
            </p>
        </body>
        </html>
    ";

        $this->sendMail($to, $subject, $content);
    }
}
