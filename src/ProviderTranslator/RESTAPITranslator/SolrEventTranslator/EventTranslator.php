<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Provider\RESTAPI;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator;
use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\SolrEventTranslator;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
class EventTranslator extends SolrEventTranslator implements RESTAPITranslator\ReadInterface
{
    /**
     * @param RESTAPI   $provider
     * @param NDOFilter $ndoFilter
     * @param bool      $allowEmptyResults
     *
     * @return string
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function read(RESTAPI $provider, NDOFilter $ndoFilter, $allowEmptyResults = false)
    {
        // The ID must be specified
        $event_id = $ndoFilter->getFilterID();
        if (empty($event_id)) {
            throw new RefineryException('event_id parameter must be specified');
        }

        // Setting the Event ID
        $ndoFilter->addQueryParameter('filter', ['q' => "event_id:$event_id"]);

        // Removing unnecessary parameters
        $ndoFilter->addQueryParameter(
            'filter',
            array(
                'q' => "event_id:$event_id",
                'fq' => array('date_time_start:*'),
                'rows' => 1
            )
        );

        return $provider->clientGet($this->getUrlFromFilter($ndoFilter), null, null, $allowEmptyResults);
    }

    /**
     * @param ProviderRawData $providerRawData
     *
     * @return NDO\SolrEvent\Event
     */
    public function translate(ProviderRawData $providerRawData)
    {
        $rawDataArray = $providerRawData->getRawDataArray();

        if ($this->isSolrCompleteResponse($rawDataArray)) {

            // Checking if there is results
            if (empty($rawDataArray['response']['numFound'])) {

                // Getting the Event ID
                $ndoFilter = $providerRawData->getNdoFilter();
                $event_id = $ndoFilter->getFilterID();

                // Throwing the exception
                throw new RefineryException("No results for event ID $event_id");
            }

            $rawDataArray = $rawDataArray['response']['docs'][0];
        }

        $ndo = new NDO\SolrEvent\Event();
        $ndo->setRead(true);

        // Setting the ID
        $ndo->setNdoID($rawDataArray['event_id']);

        // Setting the UUID
        $ndo->setUuid($rawDataArray['uuid']);

        // Setting the language
        $this->setLanguage($ndo, $rawDataArray);

        // Setting the title
        if (isset($rawDataArray['title'])) {
            $ndo->setTitle($rawDataArray['title']);
        }

        // Setting the body
        if (isset($rawDataArray['body'])) {
            $ndo->setBodyFull($rawDataArray['body']);
        }

        // Setting the programImageUrl
        $this->setProgramImageUrl($ndo, $rawDataArray);

        // Setting the URI
        if (!empty($rawDataArray['uri'])) {
            $ndo->setUri($rawDataArray['uri']);
        }

        // Setting the Registration data
        $this->setRegistrationData($ndo, $rawDataArray);

        // Setting the dates
        $ndo->setStartDate(
            new NDO\LocalDateTime($rawDataArray['date_time_start'])
        );
        $ndo->setEndDate(
            new NDO\LocalDateTime($rawDataArray['date_time_end'])
        );
        $ndo->setDateCreated(
            new NDO\LocalDateTime('@'.$rawDataArray['created'])
        );
        $ndo->setDateModified(
            new NDO\LocalDateTime('@'.$rawDataArray['changed'])
        );

        // Sets the Locations
        $this->setLocation($ndo, $rawDataArray);

        // Sets the Audience
        $this->setAudience($ndo, $rawDataArray);

        // Sets the Series
        $this->setSeries($ndo, $rawDataArray);

        // Sets the Event topic
        $this->setEventTopic($ndo, $rawDataArray);

        // Sets the Event type
        $this->setEventType($ndo, $rawDataArray);

        // Sets the sponsor
        $this->setSponsor($ndo, $rawDataArray);

        // Sets the funding
        $this->setFunding($ndo, $rawDataArray);

        // Sets the cost
        $this->setCost($ndo, $rawDataArray);

        // Sets if the ticket is required
        $this->setTicketRequired($ndo, $rawDataArray);

        // Sets the ticket url
        $this->setTicketUrl($ndo, $rawDataArray);

        // Sets the ticket details
        $this->setTicketDetails($ndo, $rawDataArray);

        // Sets the age
        $this->setAge($ndo, $rawDataArray);

        // Sets the prerequisite
        $this->setPrerequisite($ndo, $rawDataArray);

        // Sets the format
        $this->setFormat($ndo, $rawDataArray);

        // Sets if it's a listening device
        $this->setListeningDevice($ndo, $rawDataArray);

        return $ndo;
    }

    /**
     * Sets the Locations
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    public function setLocation(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['location_id'])) {

            // Setting the ID
            $location = array(
                'id' => $rawDataArray['location_id'][0]
            );

            // Setting the location fields
            $fields = array('zipcode', 'address', 'city', 'latitude', 'longitude', 'library_name', 'xstreet');
            foreach ($fields as $field) {
                if (isset($rawDataArray[$field])) {
                    $location[$field] = $rawDataArray[$field];
                }
            }

            $ndo->setLocation($location);
        }
    }

    /**
     * Sets the Audience
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    public function setAudience(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['audience_id'])) {
            $audiences = array();

            $count = count($rawDataArray['audience_id']);
            for ($i = 0; $i < $count; $i++) {
                $audiences[] = array(
                    'id'   => $rawDataArray['audience_id'][$i],
                    'name' => $rawDataArray['audience'][$i],
                );
            }

            $ndo->setAudience($audiences);
        }
    }

    /**
     * Sets the series
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    public function setSeries(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['series_id'])) {
            $series = array();

            $count = count($rawDataArray['series_id']);
            for ($i = 0; $i < $count; $i++) {
                $series[] = array(
                    'id'   => $rawDataArray['series_id'][$i],
                    'name' => $rawDataArray['series'][$i],
                );
            }

            $ndo->setSeries($series);
        }
    }

    /**
     * Sets the Event topic
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    public function setEventTopic(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['event_topic_id'])) {
            $eventTopic = array();

            // Setting the ID
            $eventTopic['id'] = $rawDataArray['event_topic_id'];

            // Setting the name
            if (isset($rawDataArray['event_topic'])) {
                $eventTopic['name'] = $rawDataArray['event_topic'];
            }

            $ndo->setEventTopic($eventTopic);
        }
    }

    /**
     * Sets the Event type
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    public function setEventType(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['event_type_id'])) {
            $eventType = array();

            // Setting the ID
            $eventType['id'] = $rawDataArray['event_type_id'];

            // Setting the name
            if (isset($rawDataArray['event_type'])) {
                $eventType['name'] = $rawDataArray['event_type'];
            }

            $ndo->setEventType($eventType);
        }
    }

    /**
     * Sets the Event language
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setLanguage(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['language'])) {

            $language = $rawDataArray['language'];
            if ($rawDataArray['language'] == SolrEventTranslator::UNDEFINED_LANGUAGE_CODE) {
                $language = SolrEventTranslator::DEFAULT_LANGUAGE_CODE;
            }

            $ndo->setLanguage($language);
        }
    }

    /**
     * Sets the Program Image URLs
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setProgramImageUrl(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['program_image_url']) && is_array($rawDataArray['program_image_url'])) {
            $ndo->setProgramImageUrl($rawDataArray['program_image_url']);
        }
    }

    /**
     * Sets the Registration data
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setRegistrationData(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        // Setting the registration type
        if (!empty($rawDataArray['registration_method'])) {
            $ndo->setRegistrationType($rawDataArray['registration_method']);
        }

        // Setting the registration status
        if (isset($rawDataArray['registration_status'])) {
            $ndo->setRegistrationStatus($rawDataArray['registration_status']);
        }

        // Setting the Registration URI
        if (!empty($rawDataArray['registration_uri'])) {
            $ndo->setRegistrationUri($rawDataArray['registration_uri']);
        }

        // Setting the Registration Open
        if (isset($rawDataArray['registration_open'])) {
            $ndo->setRegistrationOpen(
                new NDO\LocalDateTime($rawDataArray['registration_open'])
            );
        }

        // Setting the Registration Close
        if (isset($rawDataArray['registration_close'])) {
            $ndo->setRegistrationClose(
                new NDO\LocalDateTime($rawDataArray['registration_close'])
            );
        }

        // Setting the Registration Capacity
        if (isset($rawDataArray['registration_capacity'])) {
            $ndo->setRegistrationCapacity($rawDataArray['registration_capacity']);
        }

        // Setting the Registration State
        if (isset($rawDataArray['registration_state'])) {
            $ndo->setRegistrationState($rawDataArray['registration_state']);
        }

        // Setting the Registration Count
        if (isset($rawDataArray['registration_count'])) {
            $ndo->setRegistrationCount($rawDataArray['registration_count']);
        }
    }

    /**
     * @param  array $rawDataArray
     * @return bool
     */
    protected function isSolrCompleteResponse($rawDataArray)
    {
        return (isset($rawDataArray['responseHeader']) && isset($rawDataArray['response']));
    }

    /**
     * Sets the sponsor
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setSponsor(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['sponsor'])) {
            $ndo->setSponsor($rawDataArray['sponsor'][0]);
        }
    }

    /**
     * Sets the funding
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setFunding(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['grant_funder'])) {
            $ndo->setFunding($rawDataArray['grant_funder'][0]);
        }
    }

    /**
     * Sets the cost
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setCost(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['cost'])) {
            $ndo->setCost((string) $rawDataArray['cost']);
        }
    }

    /**
     * Sets if the ticket is required
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setTicketRequired(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['tickets_required'])) {
            $ndo->setTicketRequired(
                !empty($rawDataArray['tickets_required'])
            );
        }
    }

    /**
     * Sets the ticket url
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setTicketUrl(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['ticket_link_url'])) {
            $ndo->setTicketUrl($rawDataArray['ticket_link_url'][0]);
        }
    }

    /**
     * Sets the ticket details
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setTicketDetails(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['ticket_details'])) {
            $ndo->setTicketDetails($rawDataArray['ticket_details']);
        }
    }

    /**
     * Sets the age
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setAge(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (!empty($rawDataArray['age'])) {
            $ndo->setAge($rawDataArray['age']);
        }
    }

    /**
     * Sets the prerequisite
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setPrerequisite(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['class_prerequisite'])) {
            $ndo->setPrerequisite($rawDataArray['class_prerequisite']);
        }
    }

    /**
     * Sets the format
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setFormat(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['class_format']) && strlen($rawDataArray['class_format']) > 0) {
            $ndo->setFormat($rawDataArray['class_format']);
        }
    }

    /**
     * Sets if it's a listening device
     *
     * @param NDO\SolrEvent\Event $ndo
     * @param array               $rawDataArray
     */
    protected function setListeningDevice(NDO\SolrEvent\Event $ndo, array $rawDataArray)
    {
        if (isset($rawDataArray['listening_device'])) {
            $ndo->setListeningDevice(
                !empty($rawDataArray['listening_device'])
            );
        }
    }
}
