<?php
namespace NYPL\Refinery\Helpers;

use ICanBoogie\Inflector;
use NYPL\Refinery\StaticCache\StaticPluralizedCache;
use NYPL\Refinery\StaticCache\StaticSingularlizedCache;

/**
 * Class TextHelper
 *
 * @package NYPL\Refinery\Helpers
 */
class TextHelper
{
    /**
     * @param string $text
     *
     * @return mixed|string
     */
    public static function slugify($text = '')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        $encoding = mb_detect_encoding($text);

        if ($encoding != 'UTF-8') {
            // transliterate
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~u', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function pluralize($text = '')
    {
        if ($staticCache = StaticPluralizedCache::read($text)) {
            return $staticCache;
        } else {
            $pluralizedText = Inflector::get()->pluralize($text);

            StaticPluralizedCache::save($text, $pluralizedText);

            return $pluralizedText;
        }
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public static function singularize($text = '')
    {
        if ($staticCache = StaticSingularlizedCache::read($text)) {
            return $staticCache;
        } else {
            $singularizedText = Inflector::get()->singularize($text);

            StaticSingularlizedCache::save($text, $singularizedText);

            return $singularizedText;
        }
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public static function arrayFlatten(array $array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::arrayFlatten($value));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
