<?php

namespace App\Output\Formatter;

class LogFormatter implements OutputFormatter
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
        return '[' . ($dateTime->format('Y-m-d H:i:s')) . '] '
            . ($sourceUrl ? $sourceUrl . ' -> ' : '') . $targetUrl
            . ' (' . ($statusCode ?? '???') . ')'
            . PHP_EOL;
    }
}
