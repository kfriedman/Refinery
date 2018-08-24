<?php
namespace NYPL\Refinery\QueueMessage;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use NYPL\Refinery\Cache\CacheData\RawData;
use NYPL\Refinery\QueueMessage;
use NYPL\Refinery\Server;

/**
 * The RefreshCache QueueMessage used to process cache updates for the Refinery
 * Server.
 *
 * @package NYPL\Refinery
 */
class RefreshCache extends QueueMessage
{
    /**
     * The URI that should retrieved for the cache update.
     *
     * @var string
     */
    protected $refreshURI = '';

    /**
     * Constructor for the RefreshCache message. Sets the refresh URI property.
     *
     * @param string $message
     * @param string $refreshURI
     */
    public function __construct($message = '', $refreshURI = '')
    {
        if ($message) {
            $this->setMessageProperties(stripslashes($message));
        }

        if ($refreshURI) {
            $this->setRefreshURI($refreshURI);
        }
    }

    /**
     * Process the RefreshCache message by getting the refreshURI, using
     * Guzzle to GET the URI, and then saving the response as a RawData object.
     *
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function processMessage()
    {
        // Create a RawData object from the refreshURI (the message)
        $cacheData = new RawData($this->getRefreshURI(), $this->getRefreshURI(), true);

        $guzzleClient = new Client();

        // GET the refreshURI
        $request = $guzzleClient->createRequest('GET', $this->getRefreshURI());
        $request->addHeader('Accept-Encoding', 'gzip');

        try {
            $response = $guzzleClient->send($request);

            $rawData = json_decode((string) $response->getBody(), true);

            if (!$rawData) {
                $rawData = (string) $response->getBody();
            }

            // Decode the JSON response and save it cache
            $cacheData->save($rawData);
        } catch (RequestException $exception) {
            if ($exception->getCode() == 404) {
                $cacheData->save(null);
            } else {
                echo $exception->getMessage();

                $cacheData->delete();
            }
        } catch (\Exception $exception) {
            $cacheData->delete();
        }
    }

    /**
     * Getter for the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return addslashes(serialize(get_object_vars($this)));
    }

    /**
     * Getter for the URI used to refresh the cache.
     *
     * @return string
     */
    public function getRefreshURI()
    {
        return $this->refreshURI;
    }

    /**
     * Setter for the URI used to refresh the cache.
     *
     * @param string $refreshURI
     */
    public function setRefreshURI($refreshURI)
    {
        $this->refreshURI = $refreshURI;
    }
}
