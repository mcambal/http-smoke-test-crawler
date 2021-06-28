# Command options

```php
php artisan run:smoke-test <url> <options>
```

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
|--filters|Comma separated list of available filters. Currently possible value is **InvalidStatusCodes** |

Example commands:
```php
# Prints all crawled urls to stdout
php artisan run:smoke-test https://datasecurityguide.eset.com
# Prints all crawled urls to log file
php artisan run:smoke-test https://datasecurityguide.eset.com --output=file
# Prints all crawled urls to stdout and override user-agent string
php artisan run:smoke-test https://datasecurityguide.eset.com --userAgent="SpamBot 1.0"
# Prints only invalid requests to stdout
php artisan run:smoke-test https://datasecurityguide.eset.com --filters=InvalidStatusCodes
```
