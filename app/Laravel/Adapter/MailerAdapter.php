<?php

namespace App\Laravel\Adapter;

use App\Contract\Mailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class MailerAdapter implements Mailer
{
    private string $from;
    private array $to = [];
    private array $cc = [];
    private array $bcc = [];
    private string $subject;
    private array $attachments = [];

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

    public function setAttachments(array $attachmentFiles): self
    {
        $this->attachments = $attachmentFiles;
        return $this;
    }

    public function sendHtml(string $body): void
    {
        Mail::html($body, function (Message $message) {
            if (isset($this->from)) {
                $message->from($this->from);
            }

            if (isset($this->subject)) {
                $message->subject($this->subject);
            }

            foreach ($this->to as $to) {
                $message->to($to);
            }

            foreach ($this->cc as $cc) {
                $message->cc($cc);
            }

            foreach ($this->bcc as $bcc) {
                $message->bcc($bcc);
            }

            foreach ($this->attachments as $attachment) {
                $message->attach($attachment);
            }
        });
    }
}