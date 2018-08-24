<?php
namespace NYPL\Refinery\NDO\Text;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class TextMulti extends NDO\Text
{
    /**
     * @var string
     */
    public $fullText = '';

    /**
     * @var string
     */
    public $shortText = '';

    /**
     * @param string $fullText
     * @param string $shortText
     * @param string $languageCode
     */
    public function __construct($fullText = '', $shortText = '', $languageCode = '')
    {
        if ($fullText) {
            $this->setFullText($fullText);
        }

        if ($shortText) {
            $this->setShortText($shortText);
        }

        parent::__construct($languageCode);
    }

    /**
     * @return string
     */
    public function getFullText()
    {
        return $this->fullText;
    }

    /**
     * @param string $fullText
     */
    public function setFullText($fullText)
    {
        $this->fullText = $fullText;
    }

    /**
     * @return string
     */
    public function getShortText()
    {
        return $this->shortText;
    }

    /**
     * @param string $shortText
     */
    public function setShortText($shortText)
    {
        $this->shortText = $shortText;
    }
}