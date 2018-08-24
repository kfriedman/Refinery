<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Static class to create ProviderTranslator objects
 *
 * @package NYPL\Refinery
 */
class ProviderTranslatorFactory
{
    /**
     * Create a corresponding ProviderTranslator object given the specified
     * Provider and NDO. If a HTTP method is specified, ensure that the method
     * exists on the instantiated ProviderTranslator object.
     *
     *
     * @param Provider $provider
     * @param NDO      $ndo
     * @param string   $method
     *
     * @return ProviderTranslatorInterface
     * @throws RefineryException
     */
    public static function createProviderTranslator(Provider $provider, NDO $ndo, $method = '')
    {
        $name = self::getName($provider, $ndo);

        self::checkTranslatorExists($name);

        $providerTranslator = new $name;

        if ($method) {
            self::checkMethodSupported($providerTranslator, $method);
        }

        return $providerTranslator;
    }

    /**
     * Generate the name of the ProviderTranslator object given the Provider
     * and NDO that you are working with.
     *
     * @param Provider  $provider
     * @param NDO       $ndo
     *
     * @return string
     */
    protected static function getName(Provider $provider, NDO $ndo)
    {
        $name = $provider->getName();
        $name = str_replace('RESTAPI', 'RESTAPITranslator', $name);
        $name = str_replace('RemoteJSON', 'RemoteJSONTranslator', $name);
        $name = str_replace('Provider', 'ProviderTranslator', $name . 'Translator\\' . $ndo->getNdoType() . 'Translator');

        return $name;
    }

    /**
     * Ensure that the name of the ProviderTranslator actually corresponds to
     * a real class in the codebase.
     *
     * @param string $name
     *
     * @return bool
     * @throws RefineryException
     */
    protected static function checkTranslatorExists($name = '')
    {
        if (!class_exists($name)) {
            throw new RefineryException('ProviderTranslator (' . $name . ') does not exist.');
        }

        return true;
    }

    /**
     * Check that a HTTP method is supported/implemented by the ProviderTranslator.
     *
     * @param ProviderTranslatorInterface   $providerTranslator
     * @param string                        $method
     *
     * @throws RefineryException
     */
    protected static function checkMethodSupported(ProviderTranslatorInterface $providerTranslator, $method = '')
    {
        if (!method_exists($providerTranslator, $method)) {
            throw new RefineryException('Method (' . $method . ') does not exist for ProviderTranslator (' . get_class($providerTranslator) . ').');
        }
    }
}