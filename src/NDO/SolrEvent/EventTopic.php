<?php
namespace NYPL\Refinery\NDO\SolrEvent;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class EventTopic extends NDO
{
    /**
     * @var string
     */
    public $topic;

    /**
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }
}
