<?php

namespace App\Output\Formatter;

class CsvFormatter implements OutputFormatter
{
    /**
     * @param string $targetUrl
     * @param string|null $sourceUrl
     * @param int|null $statusCode
     * @return string
     */
    public function format(string $targetUrl, ?string $sourceUrl, ?int $statusCode): string
    {
        $dateTime = new \DateTime();
        $columns = [];
        $columns[] = $dateTime->format('Y-m-d H:i:s');
        $columns[] = $sourceUrl ? $sourceUrl : '';
        $columns[] = $targetUrl;
        $columns[] = $statusCode ?? '???';

        return implode(',', $columns) . PHP_EOL;
    }
}
