<?php
namespace NYPL\Refinery\Server\Endpoint\api;

use NYPL\Refinery\Helpers\TextHelper;
use NYPL\Refinery\JSONAPI\JSONAPIBuilder;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Server\Endpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 */
class IndexEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function get(NDOFilter $filter)
    {
        $data = array();

        $root = __DIR__ . '/../../../NDO';

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST,
            \RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        /**
         * @var \SplFileInfo $file
         */
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                if (substr($file->getBasename('.php'), -5) != 'Group') {
                    $namespace = substr($file->getRealPath(), 0, -4);
                    $namespace = explode('src/', $namespace);
                    $namespace = array_pop($namespace);
                    $namespace = str_replace('/', '\\', $namespace);
                    $namespace = 'NYPL\Refinery\\' . $namespace;

                    $reflectionClass = new \ReflectionClass($namespace);

                    if ($reflectionClass->isInstantiable()) {
                        /**
                         * @var $ndo NDO
                         */
                        $ndo = new $namespace;

                        if ($ndo->getSupportedReadProviders()) {
                            $path = explode('NDO/', $file->getRealPath());
                            $path = array_pop($path);
                            $path = explode('/', $path);
                            array_pop($path);

                            foreach ($path as &$directory) {
                                if (!in_array($directory, JSONAPIBuilder::$pluralizeExceptions)) {
                                    $directory = TextHelper::pluralize($directory);
                                }

                                $directory = JSONAPIBuilder::propertyToName($directory);
                            }

                            $path = implode('/', $path);

                            if ($path) {
                                $path .= '/';
                            }

                            $baseName = $file->getBasename('.php');

                            if (!in_array($baseName, JSONAPIBuilder::$pluralizeExceptions)) {
                                $baseName = TextHelper::pluralize($baseName);
                            }

                            $url = '/api/nypl/ndo/v0.1/' . $path . JSONAPIBuilder::propertyToName($baseName);

                            $data['url'][] = $url;
                        }

                    }
                }
            }
        }

        ob_start();
        require '../templates/api.tpl.php';
        $this->getResponse()->setHtml(ob_get_clean());
    }
}
