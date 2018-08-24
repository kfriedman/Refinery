<?php
namespace NYPL\Refinery\Server\Endpoint\api\nypl\locations\v1_0;

use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\NDO;
use NYPL\Refinery\NDOFilter;
use NYPL\Refinery\Provider;
use NYPL\Refinery\ProviderHandler\NDOReader;
use NYPL\Refinery\ProviderHandler\NDOUpdater;
use NYPL\Refinery\ProviderInitializer;
use NYPL\Refinery\Server\Endpoint;

/**
 * API Endpoint
 *
 * @package NYPL\Refinery\Server\Endpoint
 * @SuppressWarnings(PHPMD.LongVariable)
 */
abstract class LocationDivisionEndpoint extends Endpoint implements Endpoint\GetInterface
{
    /**
     * @param NDOFilter $filter
     *
     * @return NDO\Location\Library|NDO\Location\Division
     */
    abstract public function getLocationNDO(NDOFilter $filter);

    /**
     * @var Provider\RESTAPI\D7RefineryServerCurrent
     */
    private $provider;

    /**
     * @var null|bool
     */
    private $open = null;

    /**
     * @param NDOFilter $filter
     */
    public function get(NDOFilter $filter)
    {
        if (!$filter->getIncludeArray()) {
            $filter->addInclude('all');
        }

        $locationNDO = $this->getLocationNDO($filter);

        if ($locationNDO) {
            if (is_numeric($filter->getFilterID())) {
                $rawData = $this->getProvider()->getProviderRawData()->getRawDataArray();
            } else {
                $rawData = current($this->getProvider()->getProviderRawData()->getRawDataArray());
            }

            if ($this->isDebug()) {
                $this->getResponse()->setDebugArray($rawData);
            }

            $formattedLocation = $this->getFormattedEndpoint($locationNDO, $rawData, $filter);

            if ($locationNDO instanceof NDO\Location\Division) {
                $this->getResponse()->setDataKey('division');
            }
            if ($locationNDO instanceof NDO\Location\Library) {
                $this->getResponse()->setDataKey('location');
            }

            $this->getResponse()->setData($formattedLocation);
        }
    }

    /**
     * @param Provider  $provider
     * @param NDOFilter $filter
     *
     * @return mixed
     */
    public function put(Provider $provider, NDOFilter $filter)
    {
        $ndo = NDOUpdater::updateNDO($provider, new NDO\Location(), $filter, $this->getRawData());

        if ($ndo && $this->isDebug()) {
            $this->getResponse()->setDebugArray($provider->getProviderRawData()->getRawDataArray());
        }

        $this->getResponse()->setData($ndo);
    }

    /**
     * @return Provider|Provider\RESTAPI\D7RefineryServerCurrent
     */
    public function getProvider()
    {
        if (!$this->provider) {
            $this->provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());
        }

        return $this->provider;
    }


    /**
     * @param NDO\Location $locationNDO
     * @param array        $rawData
     * @param NDOFilter    $filter
     *
     * @return array
     * @throws RefineryException
     */
    public function getFormattedEndpoint(NDO\Location $locationNDO, array $rawData, NDOFilter $filter)
    {
        $formattedLocation = array();

        $formattedLocation['_links'] = array();
        $formattedLocation['_links']['self'] = array(
            'href' => $this->getFullURL() . '/' . $locationNDO->getSlug(),
            'about' => $locationNDO->getAboutURI()->getFullURI()
        );
        $formattedLocation['_links']['blogs'] = array(
            'href' => $this->getFullURL() . '/' . $locationNDO->getSlug() . '/blogs',
            'all' => $locationNDO->getBlogURI()->getFullURI()
        );
        $formattedLocation['_links']['events'] = array(
            'href' => $this->getFullURL() . '/' . $locationNDO->getSlug() . '/events',
            'all' => $locationNDO->getEventsURI()->getFullURI()
        );
        $formattedLocation['_links']['exhibitions'] = array(
            'href' => $this->getFullURL() . '/' . $locationNDO->getSlug() . '/exhibitions'
        );
        $formattedLocation['_links']['alerts'] = array(
            'href' => $this->getFullURL() . '/' . $locationNDO->getSlug() . '/alerts'
        );
        if ($locationNDO->getCatalogURI()) {
            $formattedLocation['_links']['on_shelves'] = array(
                'href' => $locationNDO->getCatalogURI()->getFullURI()
            );
        }
        $formattedLocation['_links']['amenities'] = array(
            'href' => $this->getFullURL() . '/' . $locationNDO->getSlug() . '/amenities'
        );
        if ($locationNDO->getContactURI()) {
            $formattedLocation['_links']['contact'] = array(
                'href' => $locationNDO->getContactURI()->getFullURI()
            );
        }
        if ($locationNDO->getConciergeURI()) {
            $formattedLocation['_links']['concierge'] = array(
                'href' => $locationNDO->getConciergeURI()->getFullURI()
            );
        }

        if (!$locationNDO->getAboutURI()->getURIWithoutHost()) {
            throw new RefineryException('About URL for location (' . $locationNDO->getFullName() . ') was not specified');
        }

        $aboutPageFilter = new NDOFilter();
        $aboutPageFilter->addQueryParameter('filter[_enhanced][uri_relative]', $locationNDO->getAboutURI()->getURIWithoutHost());
        $aboutPageGroupNDO = $this->getPageGroupNDO($aboutPageFilter);

        if ($aboutPageGroupNDO->items->valid()) {
            /**
             * @var $aboutPageNDO NDO\Content\Page\BasicPage
             */
            $aboutPageNDO = $aboutPageGroupNDO->items->current();

            $formattedLocation['about'] = $aboutPageNDO->getBodyShort();
        } else {
            $formattedLocation['about'] = null;
        }

        $formattedLocation['access'] = $locationNDO->getAccessibility();

        $formattedLocation['accessibility_note'] = $locationNDO->getAccessibilityNote();

        $formattedLocation['contacts'] = array();
        $formattedLocation['contacts']['phone'] = $locationNDO->getPhone();
        if ($locationNDO->getFax()) {
            $formattedLocation['contacts']['fax'] = $locationNDO->getFax();
        }
        if ($locationNDO->getTTY()) {
            $formattedLocation['contacts']['tty'] = $locationNDO->getTTY();
        }

        $managerFilter = new NDOFilter();
        $managerFilter->addQueryParameter('filter[profile_symbol]', $locationNDO->getSymbol());
        $managerFilter->addQueryParameter('filter[profile_site_contact]', 1);
        $staffGroupNDO = $this->getStaffGroupNDO($managerFilter);

        if ($staffGroupNDO) {
            if ($staffGroupNDO->itemsExist()) {
                /**
                 * @var $staffNDO NDO\Person\Staff
                 */
                $staffNDO = $staffGroupNDO->items->current();

                $formattedLocation['contacts']['manager'] = array(
                    'fn' => $staffNDO->getFullName(),
                    'title' => $staffNDO->getTitle()
                );
            }
        }

        $formattedLocation['cross_street'] = $locationNDO->getCrossStreet();

        $formattedLocation['floor'] = $locationNDO->getAddress()->getFloor();

        $appealFilter = new NDOFilter();
        $appealFilter->addQueryParameter('filter[field_appeal_location]', $rawData['tid']);
        $appealGroupNDO = $this->getAppealGroupNDO($appealFilter);

        if ($appealGroupNDO) {
            if ($appealGroupNDO->itemsExist()) {
                /**
                 * @var $appealNDO NDO\Content\Appeal
                 */
                $appealNDO = $appealGroupNDO->items->current();

                $formattedLocation['fundraising'] = array(
                    'id' => $appealNDO->getAppealID(),
                    'statement' => $appealNDO->getStatement(),
                    'appeal' => $appealNDO->getTitle(),
                    'button_label' => $appealNDO->getButtonTitle(),
                    'link' => $appealNDO->getButtonLink()->getFullURI()
                );
            }
        }

        $formattedLocation['geolocation'] = array(
            'type' => 'Point',
            'coordinates' => array($locationNDO->address->getLongitude(), $locationNDO->address->getLatitude())
        );

        $formattedLocation['hours'] = array();
        $formattedLocation['hours']['regular'] = array();

        $locationHoursFilter = new NDOFilter();
        $locationHoursFilter->addQueryParameter('filter[tid]', $rawData['tid']);
        $formattedLocation['hours']['regular'] = $this->getLocationHours($locationHoursFilter);

        $formattedLocation['id'] = $locationNDO->getSymbol();

        $formattedLocation['images'] = array();

        if ($locationNDO instanceof NDO\Location\Division) {
            if ($locationNDO->getInteriorImage()) {
                $formattedLocation['images']['interior'] = $locationNDO->getInteriorImage()->getUri()->getFullURI();
            }

            if ($locationNDO->getCollectionsImage()) {
                $formattedLocation['images']['collection_item'] = $locationNDO->getCollectionsImage()->getUri()->getFullURI();
            }
        }
        if ($locationNDO instanceof NDO\Location\Library) {
            if ($locationNDO->getExteriorImage()) {
                $formattedLocation['images']['exterior'] = $locationNDO->getExteriorImage()->getUri()->getFullURI();
            }

            if ($locationNDO->getInteriorImage()) {
                $formattedLocation['images']['interior'] = $locationNDO->getInteriorImage()->getUri()->getFullURI();
            }
        }

        //$formattedLocation['images'] = $this->getImageArray($locationNDO);

        $formattedLocation['locality'] = $locationNDO->address->getCity();

        if ($locationNDO instanceof NDO\Location\Division) {
            $formattedLocation['location_id'] = $locationNDO->getParentLocationSymbol();
        }

        $formattedLocation['name'] = $locationNDO->getFullName();

        $formattedLocation['open'] = $this->getOpen();

        $planYourVisitFilter = new NDOFilter();
        $planYourVisitFilter->addQueryParameter('filter[field_feature_section]', $rawData['tid']);
        $planYourVisitFilter->addQueryParameter('filter[field_pyv_published]', 1);
        $planYourVisitGroupNDO = $this->getPlanYourVisitGroupNDO($planYourVisitFilter);

        if ($planYourVisitGroupNDO) {
            $formattedLocation['plan_your_visit'] = array();

            /**
             * @var $planYourVisitNDO NDO\Content\PlanYourVisit
             */
            foreach ($planYourVisitGroupNDO->items as $planYourVisitNDO) {
                $formattedLocation['plan_your_visit'][] = array(
                    'label' => $planYourVisitNDO->getLabel(),
                    'url' => $planYourVisitNDO->getURI()->getFullURI()
                );
            }
        }

        $formattedLocation['postal_code'] = $locationNDO->address->getPostalCode();

        if ($locationNDO instanceof NDO\Location\Division) {
            $formattedLocation['rank'] = $locationNDO->getSortOrder();
        }

        $formattedLocation['region'] = $locationNDO->address->getRegion();

        if ($locationNDO->getAddress()->getRoom()) {
            $formattedLocation['room'] = $locationNDO->getAddress()->getRoom();
        }

        $formattedLocation['slug'] = $locationNDO->getSlug();

        $formattedLocation['social_media'] = array();

        if ($locationNDO->getRelatedLinks()) {
            /**
             * @var $link NDO\URI
             */
            foreach ($locationNDO->getRelatedLinks()->items as $link) {
                $formattedLocation['social_media'][] = array(
                    'site' => $link->getDescription(),
                    'href' => $link->getFullURI()
                );
            }
        }

        $formattedLocation['street_address'] = $locationNDO->address->getAddress1();

        $formattedLocation['synonyms'] = $locationNDO->getSynonyms();

        $formattedLocation['terms'] = array();

        $termFilter = new NDOFilter();
        $termFilter->addQueryParameter('filter[_enhanced][location][lid]', $rawData['lid']);

        $mediaGroupNDO = $this->getMediaGroupNDO($termFilter);

        if ($mediaGroupNDO) {
            $formattedLocation['terms'][] = EndpointFormatter::getFormattedMediaEndpoint($mediaGroupNDO);
        }

        $subjectGroupNDO = $this->getSubjectGroupNDO($termFilter);

        if ($subjectGroupNDO) {
            $formattedLocation['terms'][] = EndpointFormatter::getFormattedSubjectsEndpoint($subjectGroupNDO, true);
        }

        $formattedLocation['type'] = $locationNDO->getLocationType();

        $formattedLocation['_embedded'] = array();

        if ($filter->checkInclude('all') || $filter->checkInclude('amenities')) {
            $amenitiesFilter = new NDOFilter();
            $amenitiesFilter->setPerPage(null);
            $amenitiesFilter->addQueryParameter('filter[_enhanced][amenities_locations][tid]', $rawData['tid']);
            $amenitiesNDO = $this->getAmenities($amenitiesFilter);

            if ($amenitiesNDO) {
                $formattedLocation['_embedded']['amenities'] = EndpointFormatter::getFormattedLocationsAmenitiesEndpoint($amenitiesNDO);
            }
        }

        if ($filter->checkInclude('all') || $filter->checkInclude('events')) {
            $eventFilter = new NDOFilter();
            $eventFilter->addQueryParameter('filter[field_group_program_location]', $rawData['tid']);
            $eventFilter->setPerPage(6);
            $eventGroupNDO = $this->getEventGroup($eventFilter);

            if ($eventGroupNDO) {
                $formattedLocation['_embedded']['events'] = array();

                /**
                 * @var $eventNDO NDO\Event
                 */
                foreach ($eventGroupNDO->items as $eventNDO) {
                    $event = array();

                    $event += array(
                        '_links' => array(
                            'self' => array(
                                'href' => $eventNDO->getUri()->getFullURI()
                            )
                        ),
                        'body' => $eventNDO->getDescriptionShort(),
                        'end' => (($eventNDO->getEndDate()) ? $eventNDO->getEndDate()->getDateTime()->format('c') : null),
                        'id' => $eventNDO->getEventID(),
                        'image' => (($eventNDO->getImage()) ? $eventNDO->getImage()->getUri()->getFullURI() : null)
                    );

                    if ($eventNDO->getRegistrationType()) {
                        $event += array(
                            'registration' => array(
                                'type' => $eventNDO->getRegistrationType(),
                                'start' => (($eventNDO->getRegistrationOpen()) ? $eventNDO->getRegistrationOpen()->getDateTime()->format('c') : null)
                            )
                        );
                    }

                    $event += array(
                        'start' => (($eventNDO->getStartDate()) ? $eventNDO->getStartDate()->getDateTime()->format('c') : null),
                        'status' => array(
                            'status' => (($eventNDO->getEventStatus() == 'Canceled' || $eventNDO->getEventStatus() == 'Postponed') ? false : true),
                            'label' => $eventNDO->getEventStatus()
                        ),
                        'title' => $eventNDO->getName()
                    );

                    $formattedLocation['_embedded']['events'][] = $event;
                }
            }
        }

        if ($filter->checkInclude('all') || $filter->checkInclude('exhibitions')) {
            $exhibitionFilter = new NDOFilter();
            $exhibitionFilter->addQueryParameter('filter[field_exhibition_location]', $rawData['tid']);
            $exhibitionFilter->setPerPage(6);
            $exhibitionGroupNDO = $this->getExhibitionGroup($exhibitionFilter);

            if ($exhibitionGroupNDO) {
                $formattedLocation['_embedded']['exhibitions'] = array();

                /**
                 * @var $exhibitionNDO NDO\Event\Exhibition
                 */
                foreach ($exhibitionGroupNDO->items as $exhibitionNDO) {
                    $exhibition = array(
                        '_links' => array(
                            'self' => array(
                                'href' => $exhibitionNDO->getUri()->getFullURI()
                            )
                        ),
                        'body' => $exhibitionNDO->getDescriptionShort(),
                        'end' => (($exhibitionNDO->getEndDate()) ? $exhibitionNDO->getEndDate()->getDateTime()->format('c') : null),
                        'id' => $exhibitionNDO->getEventID(),
                        'image' => (($exhibitionNDO->getImage()) ? $exhibitionNDO->getImage()->getUri()->getFullURI() : null),
                        'start' => (($exhibitionNDO->getStartDate()) ? $exhibitionNDO->getStartDate()->getDateTime()->format('c') : null),
                        'title' => $exhibitionNDO->getName()
                    );

                    $formattedLocation['_embedded']['exhibitions'][] = $exhibition;
                }
            }
        }

        if ($filter->checkInclude('all') || $filter->checkInclude('blogs')) {
            $blogPostFilter = new NDOFilter();
            if ($locationNDO instanceof NDO\Location\Division) {
                if (isset($rawData['_enhanced']['division_node_id'])) {
                    $blogPostFilter->addQueryParameter('filter[field_related_divisions]', $rawData['_enhanced']['division_node_id']);
                }
            }
            if ($locationNDO instanceof NDO\Location\Library) {
                if (isset($rawData['tid'])) {
                    $blogPostFilter->addQueryParameter('filter[field_locations_libraries]', $rawData['tid']);
                }
            }

            if ($blogPostFilter->getQueryParameterArray()) {
                $blogPostFilter->setPerPage(6);
                $blogPostGroup = $this->getBlogPostGroupNDO($blogPostFilter);

                if ($blogPostGroup) {
                    $formattedLocation['_embedded']['blogs'] = array();

                    /**
                     * @var $blogNDO NDO\Content\Page\BlogPost
                     */
                    foreach ($blogPostGroup->items as $blogNDO) {
                        if ($blogNDO->getAuthors()) {
                            /**
                             * @var $author NDO\Person\Author
                             */
                            $authorNDO = $blogNDO->getAuthors()->items->current();

                            $author = array(
                                'name' => $authorNDO->getDisplayName(),
                                'position' => $authorNDO->getTitle(),
                                'location' => $authorNDO->getLocation()
                            );
                        } else {
                            $author = array();
                        }

                        if ($blogNDO->getHighlightImage()) {
                            $imageURI = $blogNDO->getHighlightImage()->getUri()->getFullURI();
                        } else {
                            $imageURI = '';
                        }

                        $formattedLocation['_embedded']['blogs'][] = array(
                            'id' => $blogNDO->getNdoID(),
                            'title' => $blogNDO->getTitle(),
                            'body' => $blogNDO->getBodyShort(),
                            'author' => $author,
                            'pubdate' => $blogNDO->getDateCreated()->getDateTime()->format('c'),
                            'image' => $imageURI,
                            '_links' => array(
                                'self' => array(
                                    'href' => $blogNDO->getURI()->getFullURI()
                                )
                            )
                        );
                    }
                }
            }
        }

        if ($filter->checkInclude('all') || $filter->checkInclude('features')) {
            $featureFilter = new NDOFilter();
            $featureFilter->addQueryParameter('filter[field_feature_section]', $rawData['tid']);
            $featureFilter->addQueryParameter('filter[field_featured_checkbox]', 1);
            $featureFilter->setPerPage(6);
            $featureGroup = $this->getFeatureGroupNDO($featureFilter);

            if ($featureGroup) {
                $formattedLocation['_embedded']['features'] = array();

                /**
                 * @var $featureNDO NDO\Content\Feature
                 */
                foreach ($featureGroup->items as $featureNDO) {
                    if ($featureNDO->getImage()) {
                        $imageURI = $featureNDO->getImage()->getUri()->getFullURI();
                    } else {
                        $imageURI = '';
                    }

                    $formattedLocation['_embedded']['features'][] = array(
                        'id' => $featureNDO->getNdoID(),
                        'title' => $featureNDO->getTitle(),
                        'body' => $featureNDO->getBody(),
                        'image' => $imageURI,
                        'weight' => $featureNDO->getSortOrder(),
                        '_links' => array(
                            'self' => array(
                                'href' => (($featureNDO->getUri()) ? $featureNDO->getUri()->getFullURI() : '')
                            )
                        )
                    );
                }
            }
        }

        if ($filter->checkInclude('all') || $filter->checkInclude('alerts') || $filter->checkInclude('alertsForEmbedded')) {
            $alertFilter = new NDOFilter();
            $alertFilter->setPerPage(null);
            $alertFilter->addFilter('tid', $rawData['tid']);
            if ($filter->checkInclude('alertsForEmbedded')) {
                $alertFilter->addQueryParameter('filter[field_alert_type]', 'not:home&not:all');
            }
            $alertGroup = $this->getAlertGroupNDO($alertFilter);

            if ($alertGroup) {
                $formattedLocation['_embedded']['alerts'] = EndpointFormatter::getFormattedAlertsEndpoint($alertGroup, $locationNDO, $rawData['tid']);
            }
        }

        if ($filter->checkInclude('all') || $filter->checkInclude('divisions')) {
            if (isset($rawData['_enhanced']['children_division_lids'])) {
                $formattedLocation['_embedded']['divisions'] = array();

                foreach ($rawData['_enhanced']['children_division_lids'] as $childLID) {
                    $childDivisionFilter = new NDOFilter($childLID);

                    $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

                    /**
                     * @var NDO\Location\Division $ndo
                     */
                    $ndo = NDOReader::readNDO($provider, new NDO\Location\Division(), $childDivisionFilter);

                    $embeddedDivisionFilter = new NDOFilter();
                    $embeddedDivisionFilter->addInclude('alertsForEmbedded');

                    $this->setBaseURL('api/nypl/locations/v1.0/divisions');

                    $formattedLocation['_embedded']['divisions'][] = $this->getFormattedEndpoint($ndo, $provider->getProviderRawData()->getRawDataArray(), $embeddedDivisionFilter);
                }

                array_multisort(array_column($formattedLocation['_embedded']['divisions'], 'rank'), SORT_ASC, array_column($formattedLocation['_embedded']['divisions'], 'name'), SORT_ASC, $formattedLocation['_embedded']['divisions']);
            }
        }

        if ($locationNDO instanceof NDO\Location\Division) {
            if ($filter->checkInclude('all') || $filter->checkInclude('parent')) {
                if (isset($rawData['_enhanced']['parent_division_tid'])) {
                    $parentDivisionFilter = new NDOFilter($rawData['_enhanced']['parent_division_tid']);

                    $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

                    /**
                     * @var NDO\Location\Division $ndo
                     */
                    $ndo = NDOReader::readNDO($provider, new NDO\Location\Division(), $parentDivisionFilter);

                    $embeddedDivisionFilter = new NDOFilter();
                    $embeddedDivisionFilter->addInclude('alertsForEmbedded');

                    $this->setBaseURL('api/nypl/locations/v1.0/divisions');

                    $formattedLocation['_embedded']['parent'] = $this->getFormattedEndpoint($ndo, $provider->getProviderRawData()->getRawDataArray(), $embeddedDivisionFilter);
                }
            }

            if ($filter->checkInclude('all') || $filter->checkInclude('location')) {
                $parentLocationFilter = new NDOFilter($locationNDO->getParentLocationID());

                $provider = ProviderInitializer::initializeProvider(new Provider\RESTAPI\D7RefineryServerCurrent());

                /**
                 * @var NDO\Location\Library $ndo
                 */
                $ndo = NDOReader::readNDO($provider, new NDO\Location\Library(), $parentLocationFilter);

                $embeddedDivisionFilter = new NDOFilter();
                $embeddedDivisionFilter->addInclude('alertsForEmbedded');

                $this->setBaseURL('api/nypl/locations/v1.0/locations');

                $formattedLocation['_embedded']['location'] = $this->getFormattedEndpoint($ndo, $provider->getProviderRawData()->getRawDataArray(), $embeddedDivisionFilter);
            }
        }

        return $formattedLocation;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\LocationHoursGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getLocationHoursGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\LocationHoursGroup(), $filter, null, true);

        if ($ndo) {
            $rawData = current($this->getProvider()->getProviderRawData()->getRawDataArray());

            if ($rawData['closed']) {
                $this->open = false;
            } else {
                $this->open = true;
            }
        } else {
            $this->open = false;
        }

        return $ndo;
    }


    /**
     * @param NDOFilter $filter
     *
     * @return array
     */
    public function getLocationHours(NDOFilter $filter)
    {
        $ndo = $this->getLocationHoursGroupNDO($filter);

        $hours = array();

        if ($ndo) {
            /**
             * @var $locationHours NDO\Content\LocationHours
             */
            foreach ($ndo->items as $locationHours) {
                $hours[$locationHours->getStartDay()] = array(
                    'day' => $locationHours->getStartDateTime()->getDateTime()->format('D.'),
                    'open' => $locationHours->getStartDateTime()->getDateTime()->format('H:i'),
                    'close' => $locationHours->getEndDateTime()->getDateTime()->format('H:i')
                );
            }

            for ($day = 0; $day <= 6; ++$day) {
                if (!isset($hours[$day])) {
                    $locationHour = new NDO\Content\LocationHours();

                    $hours[$day] = array(
                        'day' => $locationHour->dayOfWeekToDayText($day),
                        'open' => null,
                        'close' => null
                    );
                }
            }

            ksort($hours);
        }

        return $hours;
    }

    private function getOpen()
    {
        if ($this->open === null) {
            throw new RefineryException('Open was not set and is currently null');
        }

        return $this->open;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\AppealGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getAppealGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\AppealGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\PlanYourVisitGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getPlanYourVisitGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\PlanYourVisitGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\MediaGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getMediaGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\MediaGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\SubjectOtherGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getSubjectGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\SubjectOtherGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\ExhibitionGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getExhibitionGroup(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\ExhibitionGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\EventGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getEventGroup(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\EventGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\LocationAmenityGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getAmenities(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\LocationAmenityGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\BlogPostGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getBlogPostGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\BlogPostGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\FeatureGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getFeatureGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\FeatureGroup(), $filter, null, true);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\AlertGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getAlertGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\AlertGroup(), $filter);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\BasicPageGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getPageGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\BasicPageGroup(), $filter);

        return $ndo;
    }

    /**
     * @param NDOFilter $filter
     *
     * @return NDO\StaffGroup
     * @throws \NYPL\Refinery\Exception\RefineryException
     */
    private function getStaffGroupNDO(NDOFilter $filter)
    {
        $ndo = NDOReader::readNDO($this->getProvider(), new NDO\StaffGroup(), $filter, null, true);

        return $ndo;
    }
}
