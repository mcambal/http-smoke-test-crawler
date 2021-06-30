<?php

namespace App\Contract;

use App\Entity\Simple\TemplateData;

interface Mailer
{
    /**
     * @param string $from
     * @return $this
     */
    public function setFrom(string $from):self;

    /**
     * @param array $users
     * @return $this
     */
    public function setTo(array $users):self;

    /**
     * @param array $users
     * @return $this
     */
    public function setCc(array $users):self;

    /**
     * @param array $users
     * @return $this
     */
    public function setBcc(array $users):self;

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject):self;

    /**
     * @param array $attachmentFiles
     * @return $this
     */
    public function setAttachments(array $attachmentFiles):self;

    /**
     * @param TemplateData $templateData
     * @return $this
     */
    public function setBodyTemplate(TemplateData $templateData):self;

    /**
     *
     */
    public function sendHtml(): void;
}
