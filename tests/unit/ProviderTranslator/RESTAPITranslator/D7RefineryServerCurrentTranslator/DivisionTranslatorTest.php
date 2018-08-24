<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator\DivisionTranslator;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\NDOFilter;

class DivisionTranslatorTest extends \PHPUnit_Framework_TestCase
{
    protected $division;
    protected $rawData;

    public function setUp()
    {
        $this->division = new DivisionTranslator();
        $this->rawData = '{"lid":"400","rid":"29","fid":"21","sid":"35","name":"Manuscripts and Archives Division","description":"","symbol":"MSS","contact_id":null,"contact":null,"phone":"2129300801","fax":"","tty":"","email":"manuscripts@nypl.org","handicapped_access":"1","unit":"library","activities":{"event":0,"wall_display":0,"book_sale":0},"created":"1231258329","modified":null,"amenities":{"collections":[{"62468":0,"212":0,"213":0},{"240150":0},{"62378":0,"62508":0,"62509":0,"65887":0,"219":0,"214":0},{"61719":0,"62678":0,"248":0,"7801":0,"7810":0,"7811":0,"7812":0,"7813":0,"8339":0,"8340":0,"8342":0,"8343":0,"8344":0,"8345":0,"8346":0,"8341":0},{"220":0},{"218":0,"217":0,"4939":0},{"62377":0,"62671":0,"222":0,"224":0,"250":0,"4938":0,"8178":0,"8301":0,"8337":0,"8338":0},{"211":0,"226":0,"229":0,"251":0}],"services":[{"62528":0,"62595":0,"62596":0},{"62183":0,"62184":0,"62186":0,"62193":0},{"216":0},{"62187":0,"65910":0,"215":0},{"62195":0,"62642":0},{"230":0},{"21":0,"38":0,"5":0,"22":0,"39":0,"6":0,"23":0,"7":0,"24":0,"8":0,"25":0,"9":0,"26":0,"10":0,"27":0,"11":0,"28":0,"12":0,"29":0,"13":0,"30":0,"14":0,"15":0,"31":0,"16":0,"32":0,"17":0,"34":0,"18":0,"35":0,"19":0,"36":0,"20":0,"37":0,"4":0}],"list":[{"31380":0}]},"type":"3","uid":"0","socialmedia":{"facebook":"","twitter":"http:\/\/twitter.com\/NYPL_Archives","foursquare":"","youtube":"","flickr":"","tumblr":"","instagram":""},"floorplan":null,"division_referral":{"bibliocommons":"","catalog":"","archives":"","contact_url":" http:\/\/archives.nypl.org\/mss\/request_access","concierge_url":" http:\/\/archives.nypl.org\/mss\/request_access"},"format":null,"synonyms":"","visits":null,"accessibility_note":"","address":"Fifth Avenue at 42nd Street","xstreet":"","city":"New York","zipcode":"10018-2788","longitude":"-73.9822","latitude":"40.7532","weight":"2","tid":"443","vid":"2","uuid":"af5150c1-8014-49b1-8aa3-427dfcc0585c","parent":"36","name_locations":"Manuscripts and Archives Division","name_space":"Stephen A. Schwarzman Building","floor":"Third Floor","room":"328","region":"NY","_enhanced":{"path_blog":"blog\/division\/5216","access":"Partially Accessible","name_locations":"Manuscripts and Archives Division","name_space":"Stephen A. Schwarzman Building","zipcode_short":"10018","path_events":"events\/calendar?location=443","path_catalog":"http:\/\/nypl.bibliocommons.com\/search?custom_query=available%3A%22Manuscripts+and+Archives+Division%22&circ=CIRC|NON%20CIRC","phone_formatted":"(212) 930-0801","fax_formatted":"","tty_formatted":"","path_contact":" http:\/\/archives.nypl.org\/mss\/request_access","path_concierge":" http:\/\/archives.nypl.org\/mss\/request_access","parent_location_tid":"36","parent_location_symbol":"SASB","division_node_id":"5216","images":[{"lfid":"233","lid":"400","fid":"277715","name":"collection-item","alt":"","title":"","delta":"0","uid":"1304","filename":"dc_sasb_manuscripts2.jpg","uri":"http:\/\/cdn-dev.www.aws.nypl.org\/sites\/default\/files\/images\/locations\/443\/dc_sasb_manuscripts2.jpg","filemime":"image\/jpeg","filesize":"102825","status":"1","timestamp":"1424790085","type":"image","uuid":"304995d2-ca14-48fb-b583-dba386731892"},{"lfid":"234","lid":"400","fid":"275363","name":"interior","alt":"","title":"","delta":"0","uid":"1304","filename":"research_interior_2014_09_18_sasb_manuscripts_8019.jpg","uri":"http:\/\/cdn-dev.www.aws.nypl.org\/sites\/default\/files\/images\/locations\/443\/research_interior_2014_09_18_sasb_manuscripts_8019.jpg","filemime":"image\/jpeg","filesize":"400363","status":"1","timestamp":"1424790085","type":"image","uuid":"8dd22424-b55c-43c5-9d20-8003c95f7561"}],"is_division":true,"slug":"manuscripts-division","path_about":"about\/divisions\/manuscripts-division","location_type":"research"}}';
    }

    public function testLocationRead()
    {
        $provider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI');
        $provider->shouldReceive('clientGet')->andReturn($this->rawData);
        $filter = \Mockery::mock('\NYPL\Refinery\NDOFilter');
        $filter->shouldReceive('getFilterID')->andReturn('9999999');

        $response = $this->division->read($provider, $filter);

        self::assertJson($response);
    }

    public function testDivisionTranslate()
    {
        $providerRawData = new ProviderRawData($this->rawData, '', array('host' => 'localhost'), false, new NDOFilter());

        $dataArray = $providerRawData->getRawDataArray();

        self::assertArrayHasKey('_enhanced', $dataArray);

        $ndo = $this->division->translate($providerRawData);

        self::assertInstanceOf('\\NYPL\\Refinery\\NDO', $ndo);
    }
}
?>