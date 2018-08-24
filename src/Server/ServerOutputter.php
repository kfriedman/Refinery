<?php
namespace NYPL\Refinery\Server;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\PerformanceTracker;
use NYPL\Refinery\Server;
use NYPL\Refinery\Server\Endpoint\Response;

/**
 * Handles output from the Refinery Server.
 *
 * @package NYPL\Refinery
 */
class ServerOutputter
{
    /**
     * The default HTTP code that should be returned for errors.
     */
    const DEFAULT_HTTP_ERROR_CODE = 400;

    /**
     * Host for Access-Control-Allow-Origin header
     */
    const ACCESS_CONTROL_HOST = '*';

    /**
     * Number of seconds for browser caching headers
     */
    const BROWSE_CACHE_SECONDS = 3600;

    /**
     * Output an array as JSON and set appropriate headers.
     *
     * @param array $output
     * @param int   $statusCode
     * @param bool  $prettyPrint
     */
    public static function outputAsJSON(array $output, $statusCode = 0, $prettyPrint = false)
    {
        if ($statusCode) {
            Server::$slim->response->setStatus($statusCode);
        }

        Server::$slim->response->headers->set('Content-Type', 'application/json');

        $jsonEncodeOptions = JSON_UNESCAPED_SLASHES;

        if ($prettyPrint) {
            $jsonEncodeOptions |= JSON_PRETTY_PRINT;
        }

        Server::$slim->response->setBody(json_encode($output, $jsonEncodeOptions));
    }

    /**
     * Output an error as a JSON callback and set appropriate headers.
     *
     * @param string $callbackName
     * @param array  $output
     */
    public static function outputAsJavascriptCallback($callbackName, array $output)
    {
        Server::$slim->response->setBody($callbackName . '(' . json_encode($output, JSON_UNESCAPED_SLASHES) . ')');

        Server::$slim->response->headers->set('Content-Type', 'application/javascript');
    }

    /**
     * Output an error. Sets appropriate errors and adds debugging information
     * if enabled by the configuration.
     *
     * @param RefineryException $exception
     * @param Response          $response
     */
    public static function outputError(RefineryException $exception, Response $response = null)
    {
        if ($exception->getStatusCode()) {
            Server::$slim->response->setStatus($exception->getStatusCode());
        } else {
            Server::$slim->response->setStatus(self::DEFAULT_HTTP_ERROR_CODE);
        }

        $error = array();

        $error['title'] = $exception->getMessage();
        $error['status'] = Server::$slim->response->getStatus();
        $error['detail'] = self::getDetailedError($exception);

        if (Server::isDebug()) {
            $error['detail'] = self::getDetailedError($exception);
            $error['debug'] = self::outputDebug($response);
        }
        if (Server::isPerformance()) {
            $error['performance'] = PerformanceTracker::getPerformanceData();
        }

        self::outputAsJSON(array('errors' => array($error)));
    }

    /**
     * Get detailed error information including a backtrace.
     *
     * @param RefineryException $exception
     *
     * @return array
     */
    protected static function getDetailedError(RefineryException $exception)
    {
        $detail = array();

        if ($exception->getAddedMessage()) {
            $detail[] = $exception->getAddedMessage();
        }

        $detail += explode("\n", $exception->getTraceAsString());

        return $detail;
    }

    /**
     * Get debugging information including performance tracking data if
     * enabled.
     *
     * @param Response $response
     *
     * @return array
     * @throws RefineryException
     */
    protected static function outputDebug(Response $response = null)
    {
        $debug = array();

        if (Server::isDebug() && $response) {
            $debug['data'] = $response->getDebugArray();
        }
        if (Server::isPerformance()) {
            $debug['performance'] = PerformanceTracker::getPerformanceData();
        }

        return $debug;
    }

    /**
     * Build and output the full server response.
     *
     * @param Response $response
     */
    public static function outputFullResponse(Response $response)
    {
        self::sendExpiresHeaders();

        if ($response->getHtml()) {
            self::outputAsHTML($response->getHtml());
        } else {
            header('Access-Control-Allow-Origin: ' . self::ACCESS_CONTROL_HOST);

            $output = array();

            $output[$response->getDataKey()] = $response->getData();

            $output['meta'] = array();

            if ($response->getMetaNotices()) {
                $output['meta']['notices'] = $response->getMetaNotices();
            }

            if ($response->getCount()) {
                $output['meta']['count'] = $response->getCount();
            }

            if ($response->getPerPage() && $response->getCount() > $response->getPerPage()) {
                $output['meta']['page'] = array(
                    'size' => $response->getPerPage(),
                    'number' => $response->getPage(),
                    'count' => $response->getTotalPages()
                );
            }

            if (Server::isDebug() || Server::isPerformance()) {
                $output['meta']['debug'] = self::outputDebug($response);
            }

            if ($response->getOtherTopLevel()) {
                $output += $response->getOtherTopLevel();
            }

            if ($response->getIncluded()) {
                $output[$response::DEFAULT_INCLUDED_KEY] = $response->getIncluded();
            }

            if (Server::$slim->request->get('callback')) {
                self::outputAsJavascriptCallback(Server::$slim->request->get('callback'), $output);
            } else {
                self::outputAsJSON($output);
            }
        }
    }

    /**
     * Send cache headers to browser
     */
    public static function sendExpiresHeaders()
    {
        header('Cache-Control: max-age=' . self::BROWSE_CACHE_SECONDS);
        header('Expires: '. gmdate('D, d M Y H:i:s', time() + self::BROWSE_CACHE_SECONDS) . ' GMT');
    }

    /**
     * @param string $html
     */
    public static function outputAsHTML($html = '')
    {
        echo $html;
    }
}
