<?php

namespace App\Console\Commands;

use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Entity\Simple\TemplateData;
use App\Handler\CrawlHandler;
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
     * RunHttpSmokeTestCommand constructor.
     * @param CrawlHandler $crawlHandler
     */
    public function __construct(CrawlHandler $crawlHandler)
    {
        $this->crawlHandler = $crawlHandler;
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
        $userAgent = $this->option('userAgent');
        $maxCrawlCount = $this->option('maxCrawlCount');
        $maxCrawlDepth = $this->option('maxCrawlDepth');
        $maxResponseSize = $this->option('maxResponseSize');
        $filters = $this->option('filters');

        $this->crawlHandler
            ->crawl(
                $baseUrl,
                $this->createCrawlConfigurationFromOptions(
                    $this->option('delayBetweenRequests'),
                    (bool)$this->option('respectRobots'),
                    (bool)$this->option('rejectNoFollowLinks'),
                    $userAgent,
                    $maxCrawlCount,
                    $maxCrawlDepth,
                    $maxResponseSize
                ),
                new OutputConfiguration(
                    $this->option('output'),
                    $this->createTrimmedArray($filters)
                )
            );

        if (($emails = $this->option('emails')) !== null) {
            $this->crawlHandler->sendEmailReport(
                'crawler@eset.com',
                'Http Smoke Test Report (' . $baseUrl . ')',
                $this->createTrimmedArray($emails),
                new TemplateData('Email/CrawlingReport', [
                    'data' => [
                        'baseUrl' => $baseUrl,
                        'userAgent' => $userAgent ?? 'SmokeTestCrawler/1.0',
                        'filters' => $filters ?? 'no filters used',
                        'maxCrawlCount' => $maxCrawlCount ?? 'no limits',
                        'maxCrawlDepth' => $maxCrawlDepth ?? 'no limits',
                        'maxResponseSize' => $maxResponseSize ?? 'no limits'
                    ]
                ])
            );
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
