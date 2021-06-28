<?php

namespace App\Entity\Simple;

class CrawlData
{
    /**
     * @var string
     */
    private string $targetUrl;
    /**
     * @var string|null
     */
    private ?string $body;
    /**
     * @var string|null
     */
    private ?string $sourceUrl;
    /**
     * @var int|null
     */
    private ?int $statusCode;

    /**
     * CrawlData constructor.
     * @param string $targetUrl
     * @param string|null $body
     * @param string|null $sourceUrl
     * @param int|null $statusCode
     */
    public function __construct(string $targetUrl, ?string $body, ?string $sourceUrl, ?int $statusCode)
    {
        $this->targetUrl = $targetUrl;
        $this->body = $body;
        $this->sourceUrl = $sourceUrl;
        $this->statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    /**
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }


    /**
     * @return string|null
     */
    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }
}
