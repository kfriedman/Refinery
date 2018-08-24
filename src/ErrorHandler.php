<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Class ErrorHandler
 */
class ErrorHandler
{
    /**
     * @param int    $errorNumber
     * @param string $errorString
     * @param string $errorFile
     * @param string $errorLine
     */
    public static function errorHandler($errorNumber = 0, $errorString = '', $errorFile = '', $errorLine = '')
    {
        new RefineryException('PHP Error: ' . $errorString . ' (' . $errorNumber . ') in ' . $errorFile . ' on line ' . $errorLine);
    }

    /**
     * Check if an error was generated
     */
    public static function shutdownFunction()
    {
        $error = error_get_last();

        if ($error !== null) {
            self::errorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}
