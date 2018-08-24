<?php
namespace NYPL\Refinery;

/**
 * Class used to manage New Relic.
 *
 * @package NYPL\Refinery
 */
class NewRelicClient
{
    /**
     * @return bool
     */
    protected static function isEnabled()
    {
        return extension_loaded('newrelic');
    }

    /**
     * @return void
     */
    public static function ignoreTransaction()
    {
        if (self::isEnabled()) {
            newrelic_ignore_transaction();
        }
    }

    /**
     * @param string $name
     * @param bool   $isURL
     */
    public static function nameTransaction($name = '', $isURL = false)
    {
        if (self::isEnabled()) {
            if ($isURL) {
                $name = explode('/', $name);

                $lastFragment = array_pop($name);

                if (is_numeric($lastFragment)) {
                    $lastFragment = 'id-' . $lastFragment;
                }

                $name[] = $lastFragment;

                $name = implode('/', $name);
            }

            newrelic_name_transaction($name);
        }
    }

    /**
     * @param string $name
     */
    public static function setAppName($name = '')
    {
        if (self::isEnabled()) {
            newrelic_set_appname($name);
        }
    }

    /**
     * @param string $key
     * @param null   $value
     */
    public static function addParameter($key = '', $value = null)
    {
        if (self::isEnabled()) {
            newrelic_add_custom_parameter($key, $value);
        }
    }

    /**
     * @param \Exception $exception
     */
    public static function reportError(\Exception $exception)
    {
        if (self::isEnabled()) {
            newrelic_notice_error($exception->getMessage(), $exception);
        }
    }
}
