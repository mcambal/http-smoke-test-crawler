<?php

namespace App\Providers;

use App\Collection\OutputFilterCollection;
use App\Collection\OutputProcessorCollection;
use App\Output\Processor\DotProcessor;
use App\Output\Filter\StatusCodeFilter;
use App\Output\Formatter\LogFormatter;
use App\Output\Formatter\OutputFormatter;
use App\Output\Processor\LogFileProcessor;
use App\Output\Processor\StdoutProcessor;
use App\Spatie\Adapter\SpatieCrawlerAdapter;
use App\Contract\WebCrawler;
use App\Spatie\Collection\CrawlObserverCollection;
use App\Spatie\Observer\RecordWriterObserver;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Spatie\Crawler\Crawler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(OutputFormatter::class, LogFormatter::class);

        $outputFilterCollection = new OutputFilterCollection();
        $outputFilterCollection->add('InvalidStatusCodes',
            $this->app->make(StatusCodeFilter::class, [
                'expectedStatusCodes' => [301,302,307,308,404,403,500,502,503,504],
                'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]])
        );

        $this->app->instance(OutputFilterCollection::class, $outputFilterCollection);

        $datetime = new \DateTime();
        $outputProcessorCollection = new OutputProcessorCollection();

        $outputProcessorCollection->add('stdout', $this->app->make(StdoutProcessor::class));
        $outputProcessorCollection->add('file', $this->app->make(DotProcessor::class));
        $outputProcessorCollection->add('file', $this->app->make(LogFileProcessor::class, ['filePath' => './storage/logs/urls' . $datetime->format('-Y-m-d_H:00:00') . '.log']));

        $this->app->instance(OutputProcessorCollection::class, $outputProcessorCollection);

        $crawler = new Crawler(new Client());
        $crawler->addCrawlObserver($this->app->make(RecordWriterObserver::class));
        $this->app->instance(Crawler::class, $crawler);

        $this->app->bind(WebCrawler::class, SpatieCrawlerAdapter::class);
    }
}
