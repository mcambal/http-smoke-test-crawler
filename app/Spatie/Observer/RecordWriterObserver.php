<?php

namespace App\Spatie\Observer;

use App\Entity\Simple\CrawlData;
use App\Output\Context\OutputContext;
use App\Spatie\Strategy\ObserverWithOutputContext;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class RecordWriterObserver extends CrawlObserver implements ObserverWithOutputContext
{
    /**
     * @var OutputContext
     */
    private OutputContext $outputContext;

    /**
     * @param OutputContext $outputContext
     */
    public function setOutputContext(OutputContext $outputContext): void
    {
        $this->outputContext = $outputContext;
    }

    /**
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $this->outputContext->write(new CrawlData($url, $response->getBody(), $foundOnUrl, $response->getStatusCode()));
    }

    /**
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     */
    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        $response = $requestException->getResponse();

        $statusCode = null;
        if ($response !== null) {
            $statusCode = $response->getStatusCode();
        }

        $this->outputContext->write(new CrawlData($url, null, $foundOnUrl, $statusCode));
    }
}
