<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use NYPL\Refinery\Config\Config;
use NYPL\Refinery\QueueManager;

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

$messageType = $argv[1];
$messageBody = $argv[2];

QueueManager::outputMessage('Processing "' . $messageType . '" with "' .
    $messageBody . '" (' . date('r') . ")");

$queueMessage = QueueManager::getQueueMessageClass($messageType, $messageBody);

$queueMessage->processMessage();

$queueMessage->getQueueRecord()->delete();
