<?php

namespace App\Illuminate\Adapter;

use App\Entity\Simple\TemplateData;
use Illuminate\Mail\Mailer;

class MailerAdapter implements \App\Contract\Mailer
{
    /**
     * @var string
     */
    private string $from;
    /**
     * @var array
     */
    private array $to = [];
    /**
     * @var array
     */
    private array $cc = [];
    /**
     * @var array
     */
    private array $bcc = [];
    /**
     * @var string
     */
    private string $subject;
    /**
     * @var string
     */
    private string $templateName;
    /**
     * @var array
     */
    private array $templateData;
    /**
     * @var array
     */
    private array $attachments = [];

    /**
     * @var Mailer
     */
    private Mailer $mailer;

    /**
     * MailerAdapter constructor.
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $from
     * @return $this
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param array $users
     * @return $this
     */
    public function setTo(array $users): self
    {
        $this->to = $users;

        return $this;
    }

    /**
     * @param array $users
     * @return $this
     */
    public function setCc(array $users): self
    {
        $this->cc = $users;

        return $this;
    }

    /**
     * @param array $users
     * @return $this
     */
    public function setBcc(array $users): self
    {
        $this->bcc = $users;

        return $this;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param TemplateData $templateData
     * @return $this
     */
    public function setBodyTemplate(TemplateData $templateData): self
    {
        $this->templateName = $templateData->getName();
        $this->templateData = $templateData->getData();

        return $this;
    }

    /**
     * @param array $attachmentFiles
     * @return $this
     */
    public function setAttachments(array $attachmentFiles): self
    {
        $this->attachments = $attachmentFiles;

        return $this;
    }

    /**
     *
     */
    public function sendHtml(): void
    {
        /** @var \Swift_Message $message */
        $message = $this->mailer->getSwiftMailer()->createMessage();
        $html = $this->mailer->render($this->templateName, $this->templateData);
        $message->setBody($html);
        $message->setContentType('text/html');

        $this->send($message);
    }

    /**
     * @param \Swift_Message $message
     */
    private function send(\Swift_Message $message): void
    {
        if(isset($this->from)) {
            $message->setFrom($this->from);
        }

        if(isset($this->subject)) {
            $message->setSubject($this->subject);
        }

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
