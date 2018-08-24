<?php
namespace NYPL\Refinery\Tests;

use NYPL\Refinery\HealthCheck\RequirementsCheck;
use NYPL\Refinery\Config\Config;

class RequirementsCheckTest // extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!defined('CONFIG_FILE_DIRECTORY')) {
            define('CONFIG_FILE_DIRECTORY', __DIR__ . '/../../../config/app');
        }
        if (!defined('ENVIRONMENT_VARIABLE_NAME')) {
            define('ENVIRONMENT_VARIABLE_NAME', 'SERVER_ENV');
        }

        Config::initialize(new \Configula\Config(CONFIG_FILE_DIRECTORY), getenv(ENVIRONMENT_VARIABLE_NAME));
    }
    
    public function testIfRequirementsChecksAreSucessful()
    {
        $reqsCheck = new RequirementsCheck();
        
        $success = $reqsCheck->runCheck();
        
        self::assertTrue($success);
    }
}
?>