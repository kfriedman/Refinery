<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Cache\CacheData\RawData;
use NYPL\Refinery\Config\Config;
use NYPL\Refinery\Exception\RefineryException;
use NYPL\Refinery\HealthCheck;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerCurrent;
use NYPL\Refinery\Provider\RESTAPI\D7RefineryServerNew;
use NYPL\Refinery\Server\ServerOutputter;

/**
 * Class used to manage the health of the Refinery.
 *
 * @package NYPL\Refinery
 */
class HealthChecker
{
    /**
     * HTTP status code to use when there is an error
     */
    const ERROR_STATUS_CODE = 500;

    /**
     * String to display when healthy
     */
    const HEALTHY_STRING = 'ALL_HEALTHY';

    /**
     * Whether the server is health or not.
     * @var bool
     */
    protected static $healthy = true;

    /**
     * An array of HealthCheck objects run
     * @var HealthCheck[]
     */
    protected static $checks = array();

    /**
     * An array of HealthCheck objects that succeeded
     * @var HealthCheck[]
     */
    protected static $checksSucceeded = array();

    /**
     * An array of HealthCheck objects that failed
     * @var HealthCheck[]
     */
    protected static $checksFailed = array();

    /**
     * Run the Refinery health checker.
     *
     * @param bool $requirementsOnly
     * @param bool $returnOutput
     * @param bool $showFatalOnly
     *
     * @return array|mixed
     * @throws Exception\RefineryException
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public static function run($requirementsOnly = false, $returnOutput = false, $showFatalOnly = false)
    {
        if (!$requirementsOnly) {
            if ($showFatalOnly) {
                // Check Cache server is up
                self::runCheck(new HealthCheck\CacheCheck());
            }

            if (!$showFatalOnly) {
                self::runCheck(new HealthCheck\RequirementsCheck());

                self::runCheck(new HealthCheck\MasterCacheCheck());

                // Check Queue server is up
                self::runCheck(new HealthCheck\QueueCheck());

                // Check Queue workers
                self::runCheck(new HealthCheck\QueueWorkersCheck());

                self::runCheck(new HealthCheck\SystemCheck());

                $provider = new D7RefineryServerCurrent();
                ProviderInitializer::initializeProvider($provider);

                self::runCheck(new HealthCheck\ProviderCheck($provider));

                $provider = new D7RefineryServerNew();
                ProviderInitializer::initializeProvider($provider);

                self::runCheck(new HealthCheck\ProviderCheck($provider));
            }
        }

        $output = self::getOutput();

        if ($returnOutput) {
            return $output;
        } else {
            self::outputResults($output);

            return true;
        }
    }

    /**
     * Output the output as JSON setting the HTTP status code if necessary
     *
     * @param array $output
     */
    protected static function outputResults(array $output)
    {
        if (Server::$slim) {
            if ($output['healthy']) {
                ServerOutputter::outputAsJSON($output, 0, true);
            } else {
                ServerOutputter::outputAsJSON($output, self::ERROR_STATUS_CODE, true);
            }
        } else {
            echo json_encode($output, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get the output from the health checker
     */
    protected static function getOutput()
    {
        $output = array();

        $output['healthy'] = self::isHealthy();

        $output['healthy-string'] = self::getHealthyString();

        $output['ran'] = date('c');

        $output['ip'] = $_SERVER['SERVER_ADDR'];

        $output['environment'] = Config::getEnvironment();

        $output['checks-failed'] = self::outputChecks(self::getChecksFailed(), true);
        $output['checks-succeeded'] = self::outputChecks(self::getChecksSucceeded());

        return $output;
    }

    /**
     * Output a list of the checks that failed or succeeded
     *
     * @param array $checks
     * @param bool  $isFailed
     *
     * @return array
     */
    protected static function outputChecks(array $checks, $isFailed = false)
    {
        $output = array();

        /**
         * @var HealthCheck $test
         */
        foreach ($checks as $test) {
            if ($isFailed) {
                $messages = $test->getMessagesFailed();
            } else {
                $messages = $test->getMessagesSucceeded();
            }

            $output[] = array(
                'type' => $test->getCheckName(),
                'messages' => $messages
            );
        }

        return $output;
    }

    /**
     * Run a check and record the results
     *
     * @param HealthCheck $test
     */
    protected static function runCheck(HealthCheck $test)
    {
        self::addCheck($test);

        try {
            if (!$test->runCheck()) {
                self::setHealthy(false);

                self::addCheckFailed($test);
            } else {
                self::addCheckSucceeded($test);
            }
        } catch (RefineryException $exception) {
            $test->setFailure($exception->getMessage());

            self::addCheckFailed($test);
        }
    }

    /**
     * Add a check to the checks array
     *
     * @param HealthCheck $test
     */
    protected static function addCheck(HealthCheck $test)
    {
        self::$checks[] = $test;
    }

    /**
     * Add a succeeded check to the succeeded checks array
     *
     * @param HealthCheck $test
     */
    protected static function addCheckSucceeded(HealthCheck $test)
    {
        self::$checksSucceeded[] = $test;
    }

    /**
     * Add a failed checked to the failed checks array
     *
     * @param HealthCheck $test
     */
    protected static function addCheckFailed(HealthCheck $test)
    {
        self::$checksFailed[] = $test;
    }

    /**
     * Getter for the server healthy status
     *
     * @return boolean
     */
    public static function isHealthy()
    {
        return self::$healthy;
    }

    /**
     * Getter for the server healthy string
     *
     * @return string
     */
    public static function getHealthyString()
    {
        if (self::isHealthy()) {
            return self::HEALTHY_STRING;
        } else {
            return false;
        }
    }

    /**
     * Setter for the server healthy status
     *
     * @param boolean $healthy
     */
    public static function setHealthy($healthy)
    {
        self::$healthy = $healthy;
    }

    /**
     * Getter for the checks run
     *
     * @return array
     */
    public static function getChecks()
    {
        return self::$checks;
    }

    /**
     * Setter for the checks run
     *
     * @param array $checks
     */
    public static function setChecks($checks)
    {
        self::$checks = $checks;
    }

    /**
     * Getter for the checks that ran and succeeded
     *
     * @return array
     */
    public static function getChecksSucceeded()
    {
        return self::$checksSucceeded;
    }

    /**
     * Setter for the checks that ran and succeeded
     *
     * @param array $checksSucceeded
     */
    public static function setChecksSucceeded($checksSucceeded)
    {
        self::$checksSucceeded = $checksSucceeded;
    }

    /**
     * Getter for the checks that ran and failed
     *
     * @return array
     */
    public static function getChecksFailed()
    {
        return self::$checksFailed;
    }

    /**
     * Getter for the checks that ran and failed
     *
     * @param array $checksFailed
     */
    public static function setChecksFailed($checksFailed)
    {
        self::$checksFailed = $checksFailed;
    }
}
