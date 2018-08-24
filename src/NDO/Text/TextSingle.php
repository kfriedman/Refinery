<?php
namespace NYPL\Refinery\NDO\Text;

use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
class TextSingle extends NDO\Text
{
    /**
     * @var string
     */
    public $text = '';

    /**
     * @param string $text
     * @param string $languageCode
     */
    public function __construct($text = '', $languageCode = '')
    {
        if ($text) {
            $this->setText($text);
        }

        parent::__construct($languageCode);
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}