<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
    }
}
