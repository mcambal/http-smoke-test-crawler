<?php

namespace App\Contract;

interface Mailer
{
    public function setFrom(string $from):self;
    public function setTo(array $users):self;
    public function setCc(array $users):self;
    public function setBcc(array $users):self;
    public function setSubject(string $subject):self;
    public function setAttachments(array $attachmentFiles):self;
    public function setBodyTemplate(string $templateName, array $templateData):self;
    public function sendHtml(): void;
}
