<?php

namespace App\Output\Formatter;

interface OutputFormatter
{
    /**
     * @param string $targetUrl
     * @param string|null $sourceUrl
     * @param int|null $statusCode
     * @return string
     */
    public function format(string $targetUrl, ?string $sourceUrl, ?int $statusCode): string;
}
