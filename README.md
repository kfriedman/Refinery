# Refinery

The Refinery is a data platform. It reads data from *Providers*, *translates* the data into standardized *Data Objects* (NDOs), and serves the NDOs - generally via RESTful API endpoints utilizing the [JSON API](http://jsonapi.org/) standard.

The Refinery adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) (using the [Composer](https://getcomposer.org/) autoloader).

## Requirements

* PHP >=5.5.0
* PHP Extensions
    + [PHP AMQP](https://github.com/pdezwart/php-amqp)
    + [PhpRedis](https://github.com/phpredis/phpredis)
    + [MongoDB](https://pecl.php.net/package/mongo)

To check compatibility with requirements, run:
~~~~
$ bin/check_requirements
~~~~

## Usage

### HTTP/API Server

Configure your web server to point to the `/src` directory. The `index.php` file should be loaded on all requests.

See the `/samples` directory for sample configuration files for an Apache `.htaccess` or Nginx `nginx.conf` installation.

To check that your server is serving requests properly, load ``/system/requirements`` in your web browser. Upon success, API endpoints should be accessible.

### Queue Consumers

To **start** queue consumers and initialize the queue, run the script: 
~~~~
$ scripts/server_start.sh
~~~~

To **stop** queue consumers, run the script: 
~~~~
$ scripts/server_stop.sh
~~~~

## Documentation

Documentation is available in the the [Website](https://nypltech.atlassian.net/wiki/display/WEB/Refinery) space in Confluence.

Main components of the Refinery include:

* ``Server``: The Refinery's RESTful server utilizing the [Slim](http://www.slimframework.com/) framework. It serves various ``Endpoint`` classes.
* ``Provider``: Provides the connection to data providers using the [Guzzle](https://github.com/guzzle/guzzle) client.
* ``ProviderTranslator``: Reads the data from data providers and translates their raw data into NDOs.
* ``NDO``: Standardized data objects across the Refinery.

## Testing

Run unit tests by using PHPUnit:
~~~~
$ phpunit
~~~~
