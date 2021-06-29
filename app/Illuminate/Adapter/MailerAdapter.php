<?php

namespace App\Illuminate\Adapter;

use Illuminate\Mail\Mailer;

class MailerAdapter implements \App\Contract\Mailer
{
    private string $from;
    private array $to = [];
    private array $cc = [];
    private array $bcc = [];
    private string $subject;
    private string $templateName;
    private array $templateData;
    private array $attachments = [];

    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function setTo(array $users): self
    {
        $this->to = $users;

        return $this;
    }

    public function setCc(array $users): self
    {
        $this->cc = $users;

        return $this;
    }

    public function setBcc(array $users): self
    {
        $this->bcc = $users;

        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function setBodyTemplate(string $templateName, array $templateData): self
    {
        $this->templateName = $templateName;
        $this->templateData = $templateData;

        return $this;
    }

    public function setAttachments(array $attachmentFiles): self
    {
        $this->attachments = $attachmentFiles;

        return $this;
    }

    public function sendHtml(): void
    {
        /** @var \Swift_Message $message */
        $message = $this->mailer->getSwiftMailer()->createMessage();
        $html = $this->mailer->render($this->templateName, $this->templateData);
        $message->setBody($html);
        $message->setContentType('text/html');

        $this->send($message);
    }

    private function send(\Swift_Message $message): void
    {
        $message->setFrom($this->from);
        $message->setSubject($this->subject);

        foreach($this->to as $to) {
            $message->addTo($to);
        }

        foreach($this->cc as $cc) {
            $message->addCc($cc);
        }

        foreach($this->bcc as $bcc) {
            $message->addBcc($bcc);
        }

        foreach($this->attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        $this->mailer->getSwiftMailer()->send($message);
    }
}
