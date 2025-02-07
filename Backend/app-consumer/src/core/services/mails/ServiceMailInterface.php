<?php

namespace app_consumer\core\services\mails;

interface ServiceMailInterface
{
    public function sendMail(string $to, string $subject, string $content): void;
}
