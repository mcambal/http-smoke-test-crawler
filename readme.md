# Installation

1. Install composer dependencies (if you have composer installed)
```
composer install --ignore-platform-reqs 
```
or (if you don't have composer installed)
```
docker-compose run composer install --ignore-platform-reqs 
```
2. Copy .env.example to .env
```
cp .env.example .env
```
# Run application
```
docker-compose run application bash
php artisan run:smoke-test <url> <options>
```
or
```
docker-compose run application php artisan run:smoke-test <url> <options>
```
# Command options
|Option|Values|
|---|---|
|--output| Possible values are **stdout**, **log**, **csv** or **dot**. You can combine multiple outputs as comma separated list. Default is **stdout**|
|--respectRobots| - |
|--rejectNoFollowLinks| - |
|--delayBetweenRequests| any integer greater than or equal to 0 |
|--userAgent| any text string |
|--maxCrawlCount| any integer greater than 0 |
|--maxCrawlDepth| any integer greater than 0 |
|--maxResponseSize| any integer greater than 0 |
|--filters| Possible values are **30x**, **40x** or **50x**. You can combine multiple filters as comma separated list. If no filters are selected, all pages will be printed out|

Example commands:
```php
# Prints all crawled urls to stdout
php artisan run:smoke-test https://example.com
# Prints all crawled urls to log file
php artisan run:smoke-test https://example.com --output=file
# Prints all crawled urls to stdout and override user-agent string
php artisan run:smoke-test https://example.com --userAgent="MyBot 1.0"
# Prints only invalid requests to stdout
php artisan run:smoke-test https://example.com --filters=30x,40x,50x
```
# Vocabulary

- ***Filter*** filters crawled pages and determine which pages should be processed by processor
- ***Processor*** processes crawled pages and print/store them to desired locations (stdout, log file, database or queue)
- ***Formatter*** formats crawling information (url, status code, parent page, datetime) into desired log format (csv, tsv, json, yaml)
