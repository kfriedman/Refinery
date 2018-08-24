<?php
namespace NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderRawData;

/**
 * Translator for a NDO
 *
 * @package NYPL\Refinery\ProviderTranslator
 */
trait BlogTraitTranslator
{
    /**
     * @param ProviderRawData                                                      $providerRawData
     * @param NDO\Blog\BlogSubject|NDO\Blog\BloggerProfile|NDO\Blog\BlogSeries|NDO $ndo
     * @param string                                                               $relationshipName
     *
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    public function setBlogPosts(ProviderRawData $providerRawData, NDO $ndo, $relationshipName = '')
    {
        $blogFilter = new NDOFilter();
        $blogFilter->addQueryParameter('filter', array('relationships' => array($relationshipName => $ndo->getNdoID())));

        /**
         * @var NDO\Content\Node\BlogGroup $blogGroup
         */
        if ($blogGroup = NDOReader::readNDO(clone $providerRawData->getProvider(), new NDO\Content\Node\BlogGroup(), $blogFilter, null, true)) {
            $ndo->setBlogPosts($blogGroup);
        }
    }

    /**
     * @param string $ndoId
     * @param string $type
     *
     * @return string
     */
    protected function translateId($ndoId = '', $type = '')
    {
        if (strpos($ndoId, '/') === false) {
            if ($this instanceof BloggerProfileGroupTranslator || $this instanceof BloggerProfileTranslator || $type == 'blog-profiles') {
                $ndoId = '/person/' . $ndoId;
            }
            if ($this instanceof BlogSubjectGroupTranslator || $this instanceof BlogSubjectTranslator || $type == 'blog-subjects') {
                $ndoId = '/subject/' . $ndoId;
            }
            if ($this instanceof BlogSeriesGroupTranslator || $this instanceof BlogSeriesTranslator || $type == 'blog-series') {
                $ndoId = '/voices/blogs/blog-channels/' . $ndoId;
            }

            $ndoId = str_replace('/', '@', $ndoId);

            return $ndoId;
        } else {
            return $ndoId;
        }
    }

    /**
     * @param string|array $ndoId
     * @param string $type
     *
     * @return string
     */
    protected function translateFromIdToUri($ndoId, $type = '')
    {
        if (is_string($ndoId)) {
            return $this->translateId($ndoId, $type);
        } else {
            foreach ($ndoId as &$value) {
                $value = $this->translateId($value, $type);
            }

            return $ndoId;
        }
    }
}
