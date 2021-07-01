<?php

namespace App\Providers;

use App\Collection\OutputFilterCollection;
use App\Collection\OutputProcessorCollection;
use App\Contract\Mailer;
use App\Contract\WebCrawler;
use App\Generator\BasicFileNameGenerator;
use App\Swift\Adapter\MailerAdapter;
use App\Output\Filter\StatusCodeFilter;
use App\Output\Formatter\CsvFormatter;
use App\Output\Formatter\LogFormatter;
use App\Output\Processor\DotProcessor;
use App\Output\Processor\LogFileProcessor;
use App\Output\Processor\StdoutProcessor;
use App\Spatie\Adapter\SpatieCrawlerAdapter;
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
        $this->registerMailer();
        $this->registerOutputFilters();
        $this->registerOutputProcessors();
        $this->registerCrawler();
    }

    /**
     *
     */
    private function registerMailer(): void
    {
        $this->app->bind(Mailer::class, MailerAdapter::class);

        $smtpTransport = new \Swift_SmtpTransport(config('mail.host'), config('mail.port'), config('mail.encryption'));
        $smtpTransport->setUsername(config('mail.username'));
        $smtpTransport->setPassword(config('mail.password'));

        $this->app->instance(\Swift_Transport::class, $smtpTransport);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerOutputFilters(): void
    {
        $outputFilterCollection = new OutputFilterCollection();
        $outputFilterCollection->add('30x',
            $this->app->make(StatusCodeFilter::class, [
                    'expectedStatusCodes' => [301, 302, 307, 308],
                    'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]
                ]
            )
        );
        $outputFilterCollection->add('40x',
            $this->app->make(StatusCodeFilter::class, [
                    'expectedStatusCodes' => [403, 404, 405],
                    'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]
                ]
            )
        );
        $outputFilterCollection->add('50x',
            $this->app->make(StatusCodeFilter::class, [
                    'expectedStatusCodes' => [500, 502, 503, 504],
                    'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]
                ]
            )
        );
        /*
        $outputFilterCollection->add('OnlyMyDomains',
            $this->app->make(OnlyMyDomainsFilter::class, [
                'expectedDomains' => ['[a-z]+\.example\.com'],
                'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]
                ]
            )
        );*/

        $this->app->instance(OutputFilterCollection::class, $outputFilterCollection);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerOutputProcessors(): void
    {
        $outputProcessorCollection = new OutputProcessorCollection();
        $outputProcessorCollection->add('stdout', $this->app->make(StdoutProcessor::class, [
            'outputFormatter' => $this->app->make(LogFormatter::class)
        ]));
        $outputProcessorCollection->add('dot', $this->app->make(DotProcessor::class));
        $outputProcessorCollection->add('log', $this->app->make(LogFileProcessor::class,
            [
                'nameGenerator' => $this->app->make(BasicFileNameGenerator::class,
                    [
                        'directoryPath' => './storage/logs',
                        'baseName' => 'crawled-urls',
                        'fileExtension' => 'log'
                    ]
                ),
                'outputFormatter' => $this->app->make(LogFormatter::class)
            ])
        );

        $outputProcessorCollection->add('csv', $this->app->make(LogFileProcessor::class,
            [
                'nameGenerator' => $this->app->make(BasicFileNameGenerator::class,
                    [
                        'directoryPath' => './storage/logs',
                        'baseName' => 'crawled-urls',
                        'fileExtension' => 'csv'
                    ]
                ),
                'outputFormatter' => $this->app->make(CsvFormatter::class)
            ])
        );

        $this->app->instance(OutputProcessorCollection::class, $outputProcessorCollection);
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function registerCrawler(): void
    {
        $crawler = new Crawler(new Client());
        $crawler->addCrawlObserver($this->app->make(RecordWriterObserver::class));
        $this->app->instance(Crawler::class, $crawler);

        $this->app->bind(WebCrawler::class, SpatieCrawlerAdapter::class);
    }
}
