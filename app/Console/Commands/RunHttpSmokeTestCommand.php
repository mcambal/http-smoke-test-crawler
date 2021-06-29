<?php

namespace App\Console\Commands;

use App\Collection\OutputFilterCollection;
use App\Contract\Mailer;
use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Handler\CrawlHandler;
use App\Output\Processor\Generator\FileNameGenerator;
use Illuminate\Console\Command;

class RunHttpSmokeTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:smoke-test {url} {--output=stdout} {--respectRobots} {--delayBetweenRequests=}
    {--rejectNoFollowLinks} {--userAgent=} {--maxCrawlCount=} {--maxCrawlDepth=} {--maxResponseSize=} {--filters=}
    {--emails=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find easily broken links in your website';

    /**
     * @var CrawlHandler
     */
    private CrawlHandler $crawlHandler;
    /**
     * @var OutputFilterCollection
     */
    private OutputFilterCollection $outputValidators;

    private Mailer $mailer;

    private FileNameGenerator $nameGenerator;

    /**
     * RunHttpSmokeTestCommand constructor.
     * @param CrawlHandler $crawlHandler
     * @param OutputFilterCollection $outputValidators
     * @param Mailer $mailer
     * @param FileNameGenerator $nameGenerator
     */
    public function __construct(CrawlHandler $crawlHandler, OutputFilterCollection $outputValidators, Mailer $mailer, FileNameGenerator $nameGenerator)
    {
        $this->crawlHandler = $crawlHandler;
        $this->outputValidators = $outputValidators;
        $this->mailer = $mailer;
        $this->nameGenerator = $nameGenerator;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reportFilePath = $this->nameGenerator->getDirectoryPath() .
            DIRECTORY_SEPARATOR .
            $this->nameGenerator->getFileName();

        if(file_exists($reportFilePath)) {
            unlink($reportFilePath);
        }

        $this->crawlHandler
            ->crawl(
                $this->argument('url'),
                $this->createCrawlConfigurationFromOptions(
                    $this->option('delayBetweenRequests'),
                    (bool)$this->option('respectRobots'),
                    (bool)$this->option('rejectNoFollowLinks'),
                    $this->option('userAgent'),
                    $this->option('maxCrawlCount'),
                    $this->option('maxCrawlDepth'),
                    $this->option('maxResponseSize')
                ),
                new OutputConfiguration(
                    $this->option('output'),
                    $this->createTrimmedArray($this->option('filters'))
                )
            );

        if(($emails = $this->option('emails')) !== null) {
            $this->sendEmailReport($emails, $reportFilePath);
        }
    }

    private function sendEmailReport(string $emails, string $attachmentFilePath): void
    {
        if(file_exists($attachmentFilePath)) {
            $this->mailer
                ->setFrom('noreply@eset.com')
                ->setSubject('Crawling Report')
                ->setBodyTemplate('Email/CrawlingReport', ['data' => ['projectUrl' => $this->argument('url')]])
                ->setTo($this->createTrimmedArray($emails))
                ->setAttachments([$attachmentFilePath])
                ->sendHtml();
        }
    }

    /**
     * @param int|null $delayBetweenRequests
     * @param bool $respectRobots
     * @param bool $rejectNoFollowLinks
     * @param string|null $userAgent
     * @param int|null $maxCrawlCount
     * @param int|null $maxCrawlDepth
     * @param int|null $maxResponseSize
     * @return CrawlerConfiguration
     */
    private function createCrawlConfigurationFromOptions(
        ?int $delayBetweenRequests,
        bool $respectRobots,
        bool $rejectNoFollowLinks,
        ?string $userAgent,
        ?int $maxCrawlCount,
        ?int $maxCrawlDepth,
        ?int $maxResponseSize
    ): CrawlerConfiguration
    {
        $crawlConfiguration = new CrawlerConfiguration();

        if ($delayBetweenRequests !== null) {
            $crawlConfiguration->setDelayBetweenRequests($delayBetweenRequests);
        }

        $crawlConfiguration->setRespectRobots($respectRobots);
        $crawlConfiguration->setRejectNoFollowLinks($rejectNoFollowLinks);

        if (!empty($userAgent)) {
            $crawlConfiguration->setUserAgent($userAgent);
        }

        if (!empty($maxCrawlCount)) {
            $crawlConfiguration->setMaximumCrawlCount($maxCrawlCount);
        }

        if (!empty($maxCrawlDepth)) {
            $crawlConfiguration->setMaximumCrawlDepth($maxCrawlDepth);
        }

        if (!empty($maxResponseSize)) {
            $crawlConfiguration->setMaximumResponseSize($maxResponseSize);
        }

        return $crawlConfiguration;
    }

    /**
     * @param string|null $inputData
     * @return array
     */
    private function createTrimmedArray(?string $inputData): array
    {
        if ($inputData === null) {
            return [];
        }

        $arrayList = explode(',', $inputData);

        array_walk($arrayList, function (&$value) {
            $value = trim($value);
        });

        return $arrayList;
    }
}
