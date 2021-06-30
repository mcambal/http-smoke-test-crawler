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
|--output| Possible values are **stdout** or **file**. Default is **stdout**|
|--respectRobots| - |
|--rejectNoFollowLinks| - |
|--delayBetweenRequests| any integer greater than or equal to 0 |
|--userAgent| any text string |
|--maxCrawlCount| any integer greater than 0 |
|--maxCrawlDepth| any integer greater than 0 |
|--maxResponseSize| any integer greater than 0 |
|--filters|Comma separated list of available filters. Possible values are **InvalidStatusCodes** or **OnlyMyDomains** |

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
