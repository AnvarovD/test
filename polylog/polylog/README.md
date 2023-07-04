# Very short description of the package

This package allows you to log different things in your laravel application to Logstash/Mongo and other channels. 


## Installation
1. Add to your composer.json file this line:

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://gitlab.alifshop.uz/packages/backend/polylog"
        }
    ]
```

2. Require package

```bash
composer require alifuz/polylog
```

3. Publish config file
```bash
php artisan vendor:publish --tag=polylog-config
```
## Usage

**Specify environmental variables:**
```bash
#Turn on and off logging feature
POLYLOG_ROUTE_LOGGING=false # enable or disable log route
POLYLOG_HTTP_LOGGING=false # enable or disable log http request

#Mongo connection settings
POLYLOG_MONGO_DB_CONNECTION=mongodb://username:password@127.0.0.1:27017/database?authSource=admin
POLYLOG_MONGO_DB_NAME=my_db
POLYLOG_MONGO_DB_COLLECTION=reqresp_logs

#Driver for logging logstash or mongo
POLYLOG_ROUTE_LOG_DRIVER=logstash
#Logstash connection settings
POLYLOG_LOGSTASH_HOST=127.0.0.1
POLYLOG_LOGSTASH_USER=logstash
POLYLOG_LOGSTASH_PASSWORD=secret
POLYLOG_CONTEXT_KEY=context_json # key for json context
POLYLOG_DEBUG_HTTP=true # enable or disable log Exception info
POLYLOG_LOGSTASH_PORT=50000 
POLYLOG_ON_STATS=false // enable or disable log TransferStats class
```

**If you want to log requests and responses of your laravel routes, then you can use package like this:**
```php
Route::middleware([Alifuz\Polylog\Middlewares\RouteLogMiddleware::class])
    ->post('some/route', function () {
        return response()->json(['message' => 'Yay!']);
    });
```

**If you want to log your any Guzzle client then you can:**
```php
/**
 * Using middleware with Laravel Http Facade 
 **/
Http::baseUrl('https://google.com')
    ->withMiddleware(new Alifuz\Polylog\Middlewares\GuzzleLogMiddleware(
    'katm_logs', //channel_name
    'logstash', //logging_driver (mongo or logstash, is default logstash)
    ['reportBase64', 'pCode', 'pLogin', 'pPassword'] // keys to filter from request and response
    ));

/** 
 * Using middleware with Guzzle Client
 **/

$stack = GuzzleHttp\HandlerStack::create();
$stack->setHandler(new GuzzleHttp\HandlerStack());
$stack->after('prepare_body', new Alifuz\Polylog\Middlewares\GuzzleLogMiddleware(
    'katm_logs', //channel_name
    'logstash', //logging_driver (mongo or logstash, is default logstash)
    ['reportBase64', 'pCode', 'pLogin', 'pPassword'] // keys to filter from request and response
));

$httpClient = GuzzleHttp\Client([
    'handler' => $stack,
    'base_uri' => 'https://google.com',
]);
```





### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


### Security

If you discover any security related issues, please email ismoil.nosr@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
