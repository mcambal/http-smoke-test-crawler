<?php

namespace App\Providers;

use App\Collection\OutputFilterCollection;
use App\Collection\OutputProcessorCollection;
use App\Contract\Mailer;
use App\Illuminate\Adapter\MailerAdapter;
use App\Output\Filter\OnlyMyDomainsFilter;
use App\Output\Formatter\CsvFormatter;
use App\Output\Processor\Generator\BasicFileNameGenerator;
use App\Output\Processor\Generator\FileNameGenerator;
use App\Output\Processor\DotProcessor;
use App\Output\Filter\StatusCodeFilter;
use App\Output\Formatter\LogFormatter;
use App\Output\Formatter\OutputFormatter;
use App\Output\Processor\LogFileProcessor;
use App\Output\Processor\StdoutProcessor;
use App\Spatie\Adapter\SpatieCrawlerAdapter;
use App\Contract\WebCrawler;
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
        $this->app->bind(FileNameGenerator::class, BasicFileNameGenerator::class);

        /*$smtpTransport = new \Swift_SmtpTransport(config('mail.host'), config('mail.port'), config('mail.encryption'));
        $smtpTransport->setUsername(config('mail.username'));
        $smtpTransport->setPassword(config('mail.password'));

        $this->app->instance(\Swift_Transport::class, $smtpTransport);

        $mailer = $this->app->make(\Illuminate\Mail\Mailer::class, ['swift' => $this->app->make(\Swift_Mailer::class)]);

        $this->app->instance(MailerAdapter::class, $this->app->make(MailerAdapter::class, ['mailer' => $mailer]));
*/
        $this->app->bind(Mailer::class, MailerAdapter::class);

        $this->app->instance(BasicFileNameGenerator::class,
            $this->app->make(BasicFileNameGenerator::class,
                [
                    'directoryPath' => './storage/logs',
                    'baseName' => 'urls',
                    'fileExtension' => 'log'
                ]
            )
        );

        $outputFilterCollection = new OutputFilterCollection();
        $outputFilterCollection->add('InvalidStatusCodes',
            $this->app->make(StatusCodeFilter::class, [
                'expectedStatusCodes' => [301,302,307,308,404,403,500,502,503,504],
                'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]
                ]
            )
        );
        $outputFilterCollection->add('OnlyMyDomains',
            $this->app->make(OnlyMyDomainsFilter::class, [
                'expectedDomains' => ['[a-z]+\.eset\.com'],
                'supportedProcessors' => [StdoutProcessor::class, LogFileProcessor::class]
                ]
            )
        );

        $this->app->instance(OutputFilterCollection::class, $outputFilterCollection);

        $outputProcessorCollection = new OutputProcessorCollection();
        $outputProcessorCollection->add('stdout', $this->app->make(StdoutProcessor::class));
        $outputProcessorCollection->add('logFile', $this->app->make(DotProcessor::class));
        $outputProcessorCollection->add('logFile', $this->app->make(LogFileProcessor::class));
        $outputProcessorCollection->add('csvFile', $this->app->make(DotProcessor::class));
        $outputProcessorCollection->add('csvFile', $this->app->make(LogFileProcessor::class,
                [
                    'nameGenerator' => $this->app->make(BasicFileNameGenerator::class, [
                        'directoryPath' => './storage/logs',
                        'baseName' => 'urls',
                        'fileExtension' => 'csv'
                    ]),
                    'outputFormatter' => $this->app->make(CsvFormatter::class)
                ]
            )
        );

        $this->app->instance(OutputProcessorCollection::class, $outputProcessorCollection);

        $crawler = new Crawler(new Client());
        $crawler->addCrawlObserver($this->app->make(RecordWriterObserver::class));
        $this->app->instance(Crawler::class, $crawler);

        $this->app->bind(WebCrawler::class, SpatieCrawlerAdapter::class);
    }
}
