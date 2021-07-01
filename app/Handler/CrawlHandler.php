<?php

namespace App\Handler;

use App\Contract\Mailer;
use App\Contract\WebCrawler;
use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Output\Context\OutputContext;

class CrawlHandler
{
    /**
     * @var WebCrawler
     */
    private WebCrawler $crawler;

    /**
     * @var OutputContext
     */
    private OutputContext $outputContext;

    /**
     * @var Mailer
     */
    private Mailer $mailer;

    /**
     * CrawlHandler constructor.
     * @param WebCrawler $crawler
     * @param OutputContext $outputContext
     * @param Mailer $mailer
     */
    public function __construct(WebCrawler $crawler, OutputContext $outputContext, Mailer $mailer)
    {
        $this->crawler = $crawler;
        $this->outputContext = $outputContext;
        $this->mailer = $mailer;
    }

    /**
     * @param string $baseUrl
     * @param CrawlerConfiguration $crawlerConfiguration
     * @param OutputConfiguration $outputConfiguration
     * @throws \App\Exception\UnableToFindOutputTypeException
     * @throws \App\Exception\UnableToFindOutputFilterException
     */
    public function crawl(string $baseUrl, CrawlerConfiguration $crawlerConfiguration, OutputConfiguration $outputConfiguration): void
    {
        $this->outputContext->setOutputProcessorStrategy($outputConfiguration->getOutputProcessors());
        $this->outputContext->setOutputFilterStrategy($outputConfiguration->getOutputFilters());

        $this->resetLogs();

        $this->crawler
            ->setOutputContext($this->outputContext)
            ->setConfiguration($crawlerConfiguration)
            ->crawl($baseUrl);
    }

    /**
     * @param string $from
     * @param string $subject
     * @param array $emails
     * @param string $body
     */
    public function sendEmailReport(string $from, string $subject, array $emails, string $body): void
    {
        $fileCollection = $this->outputContext->getLogFiles();

        $this->mailer
            ->setFrom($from)
            ->setSubject($subject)
            ->setTo($emails)
            ->setAttachments($fileCollection->getNotEmptyFiles())
            ->sendHtml($body);
    }

    /**
     *
     */
    private function resetLogs(): void
    {
        $fileCollection = $this->outputContext->getLogFiles();

        foreach($fileCollection->getNotEmptyFiles() as $filePath) {
            unlink($filePath);
        }
    }
}
