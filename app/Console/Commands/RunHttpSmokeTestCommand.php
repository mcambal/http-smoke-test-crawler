<?php

namespace App\Console\Commands;

use App\Collection\OutputFilterCollection;
use App\Entity\Simple\CrawlerConfiguration;
use App\Entity\Simple\OutputConfiguration;
use App\Handler\CrawlHandler;
use Illuminate\Console\Command;

class RunHttpSmokeTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:smoke-test {url} {--output=stdout} {--respectRobots} {--delayBetweenRequests=} {--rejectNoFollowLinks} {--userAgent=} {--maxCrawlCount=} {--maxCrawlDepth=} {--maxResponseSize=} {--filters=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find easily broken links in your website';

    private CrawlHandler $crawlHandler;
    private OutputFilterCollection $outputValidators;

    public function __construct(CrawlHandler $crawlHandler, OutputFilterCollection $outputValidators)
    {
        $this->crawlHandler = $crawlHandler;
        $this->outputValidators = $outputValidators;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->crawlHandler
            ->crawl(
                $this->argument('url'),
                $this->createCrawlConfigurationFromOptions(),
                new OutputConfiguration(
                    $this->option('output'),
                    $this->option('filters')
                )
            );
    }

    private function createCrawlConfigurationFromOptions(): CrawlerConfiguration
    {
        $crawlConfiguration = new CrawlerConfiguration();

        $delayBetweenRequests = $this->option('delayBetweenRequests');
        if($delayBetweenRequests !== null) {
            $crawlConfiguration->setDelayBetweenRequests($delayBetweenRequests);
        }

        $crawlConfiguration->setRespectRobots((bool) $this->option('respectRobots'));
        $crawlConfiguration->setRejectNoFollowLinks($this->option('rejectNoFollowLinks'));


        $userAgent = $this->option('userAgent');
        if(!empty($userAgent)) {
            $crawlConfiguration->setUserAgent($userAgent);
        }

        $maxCrawlCount = $this->option('maxCrawlCount');
        if(!empty($maxCrawlCount)) {
            $crawlConfiguration->setMaximumCrawlCount($maxCrawlCount);
        }

        $maxCrawlDepth = $this->option('maxCrawlDepth');
        if(!empty($maxCrawlDepth)) {
            $crawlConfiguration->setMaximumCrawlDepth($maxCrawlDepth);
        }

        $maxResponseSize = $this->option('maxResponseSize');
        if(!empty($maxResponseSize)) {
            $crawlConfiguration->setMaximumResponseSize($maxResponseSize);
        }

        return $crawlConfiguration;
    }
}
