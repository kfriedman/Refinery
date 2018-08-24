<?php
namespace NYPL\Refinery\Helpers;

/**
 * Class SystemHelper
 *
 * @package NYPL\Refinery\Helpers
 */
class SystemHelper
{
    /**
     * @return string
     */
    public static function getLocalIPAddresses()
    {
        $ipAddressArray = array();

        $ipAddressArray[] = '127.0.0.1';
        $ipAddressArray[] = 'localhost';
        $ipAddressArray[] = exec("/sbin/ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'");

        return $ipAddressArray;
    }

    /**
     * @param string $ipAddress
     *
     * @return bool
     */
    public static function isLocalIPAddress($ipAddress = '')
    {
        if (in_array($ipAddress, self::getLocalIPAddresses())) {
            return true;
        } else {
            return false;
        }
    }
}
