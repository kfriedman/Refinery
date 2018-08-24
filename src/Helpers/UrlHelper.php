<?php
namespace NYPL\Refinery\Helpers;

/**
 * Class UrlHelper
 *
 * @package NYPL\Refinery\Helpers
 */
class UrlHelper
{
    protected static $rewriteUrls = array(
        'https://d140u095r09w96.cloudfront.net/' => 'http://cdn-prod.www.aws.nypl.org/',
        'https://contentcafe2.btol.com/' => 'http://contentcafe2.btol.com/',
        'https://www.nypl.org/' => 'http://www.nypl.org/',
        'https://browse.nypl.org/' => 'http://browse.nypl.org/',
        'https://catalog.nypl.org/' => 'http://catalog.nypl.org/',
        'https://images.btol.com/' => array(
            'http://images.btol.com/',
            'http://imagesa.btol.com/',
            'http://imagesb.btol.com/',
            'http://imagesc.btol.com/'
        ),
        'https://d140u095r09w96.cloudfront.net/' => array(
            'https://cdn-prod.www.aws.nypl.org/',
            'http://cdn-prod.www.aws.nypl.org/'
        )
    );

    /**
     * Builds the URL query removing the index numbers
     *
     * @param  array $params Parameters
     * @link   http://php.net/manual/es/function.http-build-query.php#111819
     * @return string
     */
    public static function buildQueryWithNoIndex(array $params)
    {
        $query = http_build_query($params);
        return preg_replace('/%5B[0-9]+%5D/simU', '', $query);
    }

    /**
     * @param string $fullURL
     *
     * @return string
     */
    public static function rewriteMixedUrl($fullURL = '')
    {
        $fullURL = trim($fullURL);

        foreach (self::getRewriteUrls() as $newUrl => $oldUrl) {
            if (is_array($oldUrl)) {
                $oldUrlArray = $oldUrl;
            } else {
                $oldUrlArray = array($oldUrl);
            }

            foreach ($oldUrlArray as $oldUrl) {
                if (strpos($fullURL, $oldUrl) !== false) {
                    $fullURL = str_replace($oldUrl, $newUrl, $fullURL);
                }
            }
        }

        return $fullURL;
    }

    /**
     * @return array
     */
    public static function getRewriteUrls()
    {
        return self::$rewriteUrls;
    }
}
