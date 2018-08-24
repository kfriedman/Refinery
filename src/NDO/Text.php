<?php
namespace NYPL\Refinery\NDO;

use NYPL\Refinery\DIManager;
use NYPL\Refinery\NDO;

/**
 * Class to create a NDO
 *
 * @package NYPL\Refinery\NDO
 */
abstract class Text extends NDO
{
    /**
     * @var string
     */
    protected $languageCode = '';

    /**
     * @param string $languageCode
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function __construct($languageCode = '')
    {
        if ($languageCode) {
            $this->setLanguageCode($languageCode);
        } else {
            $this->setLanguageCode(DIManager::getConfig()->getItem('DefaultLanguageCode', null, true));
        }

        parent::__construct();
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     */
    public function setLanguageCode($languageCode)
    {
        $this->languageCode = $languageCode;
    }
}