<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\ProviderTranslator\RESTAPITranslator\D7RefineryServerCurrentTranslator\LibraryTranslator;
use NYPL\Refinery\ProviderRawData;
use NYPL\Refinery\NDOFilter;

class LibraryTranslatorTest extends \PHPUnit_Framework_TestCase
{
    protected $library;
    protected $rawData;

    public function setUp()
    {
        $this->library = new LibraryTranslator();
        $this->rawData = '{"lid":"11","rid":null,"fid":null,"sid":null,"name":"58th Street","description":"","symbol":"FE","contact_id":null,"contact":"","phone":"2127597358","fax":"","tty":"","email":"58st_branch@nypl.org","handicapped_access":"1","unit":"library","activities":{"event":"event","wall_display":0,"book_sale":0},"created":null,"modified":null,"amenities":{"collections":[{"212":0,"213":0,"62468":0},{"214":0,"219":0,"62378":0,"62508":0,"62509":0,"65887":0},{"248":0,"7801":0,"7810":0,"7811":0,"7812":0,"7813":0,"8339":0,"8340":0,"8341":0,"8342":0,"8343":0,"8344":0,"8345":0,"8346":0,"61719":0,"62678":0},{"220":0},{"217":0,"218":0,"4939":0},{"222":0,"224":0,"250":0,"4938":0,"8178":0,"8301":0,"8337":0,"8338":0,"62377":0,"62671":0},{"211":0,"226":0,"229":0,"251":0}],"list":[{"31380":0}],"services":[{"18":0,"19":0,"20":0,"21":0,"22":0,"23":0,"24":0,"25":0,"26":0,"27":0},{"16":0,"17":0,"28":0,"29":0,"30":0,"31":0,"32":0,"62528":0,"62595":0,"62596":0},{"62183":0,"62184":0,"62186":0,"62193":0},{"4":4,"5":0,"6":0,"7":0,"8":0,"9":0,"216":0},{"36":0,"37":0,"38":0,"39":0,"215":0,"62187":0,"65910":0},{"10":0,"11":0,"12":0,"13":0,"14":0,"15":0,"62195":0,"62642":0},{"34":0,"35":0,"230":0}]},"type":"0","uid":null,"socialmedia":{"facebook":"http:\/\/www.facebook.com\/pages\/58th-Street-Branch-Library-NYPL\/269845179098","twitter":"http:\/\/twitter.com\/58stlibrary","foursquare":"http:\/\/foursquare.com\/venue\/897294","youtube":"http:\/\/www.youtube.com\/NewYorkPublicLibrary","flickr":"","tumblr":"","instagram":""},"floorplan":null,"division_referral":{"bibliocommons":"","catalog":"","archives":"","contact_url":"http:\/\/www.questionpoint.org\/crs\/servlet\/org.oclc.admin.BuildForm?&institution=10208&type=1&language=1","concierge_url":""},"format":null,"synonyms":"","visits":null,"accessibility_note":null,"address":"127 East 58th Street","xstreet":"between Park &amp; Lexington Aves.","city":"New York","zipcode":"10022-1211","longitude":"-73.9691","latitude":"40.7619","weight":"1","tid":"3","vid":"2","uuid":"113bc990-4b28-4328-8541-fa790a4047f4","parent":"0","name_locations":"<main>","name_space":"58th Street Library","floor":null,"room":null,"region":"NY","_enhanced":{"path_blog":"blog\/library\/3","access":"Partially Accessible","name_locations":"<main>","name_space":"58th Street Library","zipcode_short":"10022","path_events":"events\/calendar?location=3","path_catalog":"http:\/\/nypl.bibliocommons.com\/search?custom_query=available%3A%2258th+Street%22&circ=CIRC|NON%20CIRC","phone_formatted":"(212) 759-7358","fax_formatted":"","tty_formatted":"","path_contact":"http:\/\/www.questionpoint.org\/crs\/servlet\/org.oclc.admin.BuildForm?&institution=10208&type=1&language=1","images":[{"lfid":"20","lid":"11","fid":"274178","name":"exterior","alt":"","title":"","delta":"0","uid":"1304","filename":"exterior_58th-7253.jpg","uri":"http:\/\/cdn-dev.www.aws.nypl.org\/sites\/default\/files\/images\/locations\/3\/exterior_58th-7253_0.jpg","filemime":"image\/jpeg","filesize":"158569","status":"1","timestamp":"1411139062","type":"image","uuid":"9d823675-91e7-44ef-be67-ae817786a1b3"},{"lfid":"21","lid":"11","fid":"274177","name":"interior","alt":"","title":"","delta":"0","uid":"1304","filename":"interior_58th-7250.jpg","uri":"http:\/\/cdn-dev.www.aws.nypl.org\/sites\/default\/files\/images\/locations\/3\/interior_58th-7250.jpg","filemime":"image\/jpeg","filesize":"265263","status":"1","timestamp":"1411139062","type":"image","uuid":"fcd61791-2699-42f1-b513-ade590e0ba7f"}],"is_division":false,"slug":"58th-street","path_about":"about\/locations\/58th-street","location_type":"circulating"}}';
    }

    public function testLocationRead()
    {
        $provider = \Mockery::mock('\NYPL\Refinery\Provider\RESTAPI');
        $provider->shouldReceive('clientGet')->andReturn($this->rawData);
        $filter = \Mockery::mock('\NYPL\Refinery\NDOFilter');
        $filter->shouldReceive('getFilterID')->andReturn('9999999');

        $response = $this->library->read($provider, $filter);

        self::assertJson($response);
    }

    public function testLocationTranslate()
    {
        $providerRawData = new ProviderRawData($this->rawData, '', array('host' => 'localhost'), false, new NDOFilter());

        $ndo = $this->library->translate($providerRawData);

        self::assertInstanceOf('\\NYPL\\Refinery\\NDO', $ndo);
    }
}
?>