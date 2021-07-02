<?php

namespace App\Console\Commands;

use App\Collection\InputItemCollection;
use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Handler\CrawlHandler;
use Illuminate\Console\Command;
use Illuminate\View\Factory;

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
     * @var Factory
     */
    private Factory $templateRenderer;

    /**
     * RunHttpSmokeTestCommand constructor.
     * @param CrawlHandler $crawlHandler
     * @param Factory $templateRenderer
     */
    public function __construct(CrawlHandler $crawlHandler, Factory $templateRenderer)
    {
        $this->crawlHandler = $crawlHandler;
        $this->templateRenderer = $templateRenderer;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $baseUrl = $this->argument('url');
        $filters = $this->option('filters');

        $crawlConfiguration = $this->createCrawlConfigurationFromOptions(
            $this->option('delayBetweenRequests'),
            (bool)$this->option('respectRobots'),
            (bool)$this->option('rejectNoFollowLinks'),
            $this->option('userAgent'),
            $this->option('maxCrawlCount'),
            $this->option('maxCrawlDepth'),
            $this->option('maxResponseSize')
        );

        $outputConfiguration = new OutputConfiguration(
            new InputItemCollection($this->option('output')),
            new InputItemCollection($filters)
        );

        $this->crawlHandler->crawl($baseUrl, $crawlConfiguration, $outputConfiguration);

        if ($this->option('emails') !== null) {
            $emailCollection = new InputItemCollection($this->option('emails'));
            $this->sendEmailReport($baseUrl, $emailCollection->all(), $filters, $crawlConfiguration);
        }
    }

    /**
     * @param string $baseUrl
     * @param array $emails
     * @param string $filters
     * @param CrawlerConfiguration $crawlerConfiguration
     */
    private function sendEmailReport(string $baseUrl, array $emails, string $filters, CrawlerConfiguration $crawlerConfiguration)
    {
        $emailBody = $this->templateRenderer->make('Email/CrawlingReport', [
            'data' => [
                'baseUrl' => $baseUrl,
                'userAgent' => $crawlerConfiguration->getUserAgent() ?? 'SmokeTestCrawler/1.0',
                'filters' => $filters ?? 'no filters used',
                'maxCrawlCount' => $crawlerConfiguration->getMaximumCrawlCount() ?? 'no limits',
                'maxCrawlDepth' => $crawlerConfiguration->getMaximumCrawlDepth() ?? 'no limits',
                'maxResponseSize' => $crawlerConfiguration->getMaximumResponseSize() ?? 'no limits'
            ]
        ])->render();

        $this->crawlHandler->sendEmailReport(
            'noreply@example',
            'Http Smoke Test Report (' . $baseUrl . ')',
            $emails,
            $emailBody
        );
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
}
