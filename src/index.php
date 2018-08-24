<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use NYPL\Refinery\Server;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NewRelicClient;

/**
 * Define the file system directory for configuration files
 */
define('CONFIG_FILE_DIRECTORY', __DIR__ . '/../config/app');

/**
 * Initialize the Refinery Configuration Manager
 */
Config::initialize(new Configula\Config(CONFIG_FILE_DIRECTORY));

set_error_handler('\NYPL\Refinery\ErrorHandler::errorHandler');
register_shutdown_function('\NYPL\Refinery\ErrorHandler::shutdownFunction');

/**
 * Determine if you are running the Refinery from the command-line
 */
if (php_sapi_name() == 'cli') {
    NewRelicClient::ignoreTransaction();

    if (!isset($argv[1])) {
        throw new RefineryException('Command line parameter not specified');
    } else {
        switch ($argv[1]) {
            case 'initialize':
                \NYPL\Refinery\CacheManager::initialize();
                break;
            case 'queue-worker':
                \NYPL\Refinery\QueueManager::processQueue();
                break;
            default;
                throw new RefineryException('Command line parameter (' . $argv[1] . ') does not match any parameter');
                break;
        }
    }
} else {
    set_time_limit(Config::getItem('Server.MaximumTime'));

    NewRelicClient::setAppName('Refinery (' . Config::getItem('Environment', null, true) . ')');

    /**
     * If you are not running the Refinery from the command-line, run the
     * Refinery server.
     */
    Server::run(Config::getItem('Server.BasePath'));
}
