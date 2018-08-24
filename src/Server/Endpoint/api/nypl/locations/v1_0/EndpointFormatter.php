<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0;

use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\Server\Endpoint;

/**
 * Class EndpointFormatter
 *
 * @package NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class EndpointFormatter
{
    /**
     * @param NDO\MediaGroup $mediaGroupNDO
     *
     * @return array
     */
    public static function getFormattedMediaEndpoint(NDO\MediaGroup $mediaGroupNDO)
    {
        $terms = array();

        /**
         * @var $mediaNDO NDO\Term\Media
         */
        foreach ($mediaGroupNDO->items as $mediaNDO) {
            $terms[] = array(
                'name' => $mediaNDO->getName(),
                'id' => (int) $mediaNDO->getNdoID()
            );
        }

        return array(
            'name' => 'Media',
            'id' => 41,
            'terms' => $terms
        );
    }

    /**
     * @param NDO\SubjectOtherGroup $subjectGroupNDO
     * @param bool                  $returnFlat
     *
     * @return array
     */
    public static function getFormattedSubjectsEndpoint(NDO\SubjectOtherGroup $subjectGroupNDO, $returnFlat = false)
    {
        $terms = array();

        if ($returnFlat) {
            foreach ($subjectGroupNDO->items as $subjectNDO) {
                $terms[] = array(
                    'name' => $subjectNDO->getName(),
                    'id' => (int) $subjectNDO->getNdoID()
                );
            }
        } else {
            $children = array();

            /**
             * @var $subjectNDO NDO\Term\SubjectOther
             */
            foreach ($subjectGroupNDO->items as $subjectNDO) {
                if ($subjectNDO->getParent()) {
                    $children[$subjectNDO->getParent()->getNdoID()][] = array(
                        'name' => $subjectNDO->getName(),
                        'id' => (int) $subjectNDO->getNdoID()
                    );
                }
            }
            foreach ($subjectGroupNDO->items as $subjectNDO) {
                if (!$subjectNDO->getParent()) {
                    $term = array(
                        'name' => $subjectNDO->getName(),
                        'id' => (int) $subjectNDO->getNdoID()
                    );

                    if (isset($children[$subjectNDO->getNdoID()])) {
                        $term['terms'] = $children[$subjectNDO->getNdoID()];
                    }

                    $terms[] = $term;
                }
            }

        }

        return array(
            'name' => 'Subjects',
            'id' => 42,
            'terms' => $terms
        );
    }

    /**
     * @param NDO\AlertGroup $alertGroupNDO
     * @param NDO\Location   $locationNDO
     * @param null           $locationTID
     *
     * @return array
     */
    public static function getFormattedAlertsEndpoint(NDO\AlertGroup $alertGroupNDO, NDO\Location $locationNDO = null, $locationTID = null)
    {
        $alerts = array();

        /**
         * @var $alertNDO NDO\Content\Alert
         */
        foreach ($alertGroupNDO->items as $alertNDO) {
            $scope = $alertNDO->getScope();

            if ($locationNDO && $scope == 'location') {
                if ($locationNDO instanceof NDO\Location\Division && !in_array($locationTID, $alertNDO->getParentAlertSetArray())) {
                    $scope = 'division';
                }
            }

            $alert = array(
                'id' => $alertNDO->getNdoID(),
                'scope' => $scope,
                '_links' => array(
                    'web' => array(
                        'href' => $alertNDO->getUri()->getFullURI()
                    )
                ),
                'msg' => $alertNDO->getMessage(),
                'display' => array(
                    'start' => $alertNDO->getDisplayDateStart()->getDateTime()->format('c'),
                    'end' => $alertNDO->getDisplayDateEnd()->getDateTime()->format('c')
                ),
            );

            if ($alertNDO->getClosingDateStart()) {
                $alert['closed_for'] = $alertNDO->getClosedMessage();

                $alert['applies'] = array(
                    'start' => $alertNDO->getClosingDateStart()->getDateTime()->format('c'),
                    'end' => $alertNDO->getClosingDateEnd()->getDateTime()->format('c')
                );
            }

            $alerts[] = $alert;
        }

        return $alerts;
    }

    /**
     * @param Endpoint         $endpoint
     * @param NDO\AmenityGroup $amenityGroupNDO
     *
     * @return array
     */
    public static function getFormattedAmenitiesEndpoint(Endpoint $endpoint, NDO\AmenityGroup $amenityGroupNDO)
    {
        $amenities = array();

        /**
         * @var $amenityNDO NDO\Content\Amenity
         */
        foreach ($amenityGroupNDO->items as $amenityNDO) {
            if ($amenityNDO->getParentName()) {
                $amenities[] = self::getFormattedAmenityEndpoint($endpoint, $amenityNDO);
            }
        }

        array_multisort(array_column($amenities, 'rank'), SORT_ASC, $amenities);

        return $amenities;
    }

    /**
     * @param Endpoint            $endpoint
     * @param NDO\Content\Amenity $amenityNDO
     * @param bool                $includeLocations
     *
     * @return array
     */
    public static function getFormattedAmenityEndpoint(Endpoint $endpoint, NDO\Content\Amenity $amenityNDO, $includeLocations = false)
    {
        $links = array();

        $links['self'] = array(
            'href' => $endpoint->getFullURL() . '/' . $amenityNDO->getAmenityID(),
        );

        if ($amenityNDO->getActionURI()) {
            $links['action'] = array(
                'name' => $amenityNDO->getActionName(),
                'href' => $amenityNDO->getActionURI()->getFullURI()
            );
        }

        if ($amenityNDO->getInfoURI()) {
            $links['info'] = array(
                'name' => $amenityNDO->getInfoLabel(),
                'href' => $amenityNDO->getInfoURI()->getFullURI()
            );
        }

        $amenity = array(
            '_links' => $links,
            'category' => $amenityNDO->getParentName(),
            'id' => $amenityNDO->getAmenityID(),
            'name' => $amenityNDO->getName(),
            'rank' => $amenityNDO->getSortOrder()
        );

        if ($includeLocations) {
            $amenity['_embedded']['locations'] = array();

            foreach ($amenityNDO->getAmenityLocationIDArray() as $locationTID) {
                $amenityLocationFilter = new NDOFilter($locationTID);
                $amenityLocationFilter->addInclude('none');

                $locationEndpoint = new Endpoint\api\nypl\locations\v1_0\locations\IndexEndpoint();
                $locationEndpoint->get($amenityLocationFilter);

                if ($locationEndpoint->getResponse()->getData()) {
                    $amenity['_embedded']['locations'][] = $locationEndpoint->getResponse()->getData();
                }
            }
        }

        return $amenity;
    }

    /**
     * @param NDO\LocationAmenityGroup $locationAmenityGroupNDO
     *
     * @return array
     */
    public static function getFormattedLocationsAmenitiesEndpoint(NDO\LocationAmenityGroup $locationAmenityGroupNDO)
    {
        $amenities = array();

        /**
         * @var $locationAmenityNDO NDO\Content\Amenity\LocationAmenity
         */
        foreach ($locationAmenityGroupNDO->items as $locationAmenityNDO) {
            if ($locationAmenityNDO->getParentName()) {
                $links = array();

                $links['self'] = array(
                    'href' => null
                );

                if ($locationAmenityNDO->getActionURI()) {
                    $links['action'] = array(
                        'name' => $locationAmenityNDO->getActionName(),
                        'href' => $locationAmenityNDO->getActionURI()->getFullURI()
                    );
                }

                if ($locationAmenityNDO->getInfoURI()) {
                    $links['info'] = array(
                        'name' => $locationAmenityNDO->getInfoLabel(),
                        'href' => $locationAmenityNDO->getInfoURI()->getFullURI()
                    );
                }

                $amenity = array(
                    'location_rank' => $locationAmenityNDO->getLocationSortOrder(),
                    'accessibility_note' => $locationAmenityNDO->getAccessibilityNote(),
                    'accessible' => $locationAmenityNDO->getAccessible(),
                    'staff_assistance' => $locationAmenityNDO->isStaffAssistanceRequired(),
                    'amenity' => array(
                        '_links' => $links,
                        'category' => $locationAmenityNDO->getParentName(),
                        'id' => $locationAmenityNDO->getAmenityID(),
                        'name' => $locationAmenityNDO->getName(),
                        'rank' => ((!$locationAmenityNDO->getSortOrder()) ? 99999 : $locationAmenityNDO->getSortOrder())
                    )
                );

                $amenities[] = $amenity;
            }
        }

        array_multisort(array_column($amenities, 'location_rank'), SORT_ASC, $amenities);

        $count = 0;
        foreach ($amenities as &$amenity) {
            $amenity['location_rank'] = ++$count;
        }

        array_multisort(array_column(array_column($amenities, 'amenity'), 'rank'), SORT_ASC, $amenities);

        return $amenities;
    }
}
