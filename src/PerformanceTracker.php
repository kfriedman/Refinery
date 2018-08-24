<?php
namespace NYPL\Refinery;

use NYPL\Refinery\Exception\RefineryException;

/**
 * Class used for performance tracking within the Refinery.
 *
 * @package NYPL\Refinery
 */
class PerformanceTracker
{
    /**
     * The start timestamp of the entirety of performance tracking.
     *
     * @var float
     */
    public static $start = 0.0;

    /**
     * An array of timed events.
     *
     * @var array
     */
    public static $eventDurationArray = array();

    /**
     * Whether the PerformanceTracker is enabled or not.
     *
     * @var bool|null
     */
    protected static $enabled;

    /**
     * Whether the PerformanceTracker was initialized.
     *
     * @var bool
     */
    protected static $initialized = false;

    /**
     * An array of when timed events were started.
     *
     * @var array
     */
    protected static $eventStartArray = array();

    /**
     * The duration of the last timed event.
     *
     * @var float
     */
    protected static $lastEventDuration = 0.0;

    /**
     * Initialize the PerformanceTracker. If XHProf is configured and enabled,
     * initialize XHProf.
     */
    public static function initialize()
    {
        if (self::isEnabled() && !self::$initialized) {
            if (self::xhProfIsEnabled()) {
                /** @noinspection PhpIncludeInspection */
                include_once DIManager::getConfig()->getItem('XHProfInstallPath') . '/xhprof_lib/utils/xhprof_lib.php';
                /** @noinspection PhpIncludeInspection */
                include_once DIManager::getConfig()->getItem('XHProfInstallPath') . '/xhprof_lib/utils/xhprof_runs.php';
                xhprof_enable();
            }

            self::$start = self::getTime();

            self::$initialized = true;
        }
    }

    /**
     * Get the current Unix timestamp with microseconds on the server.
     *
     * @return float
     */
    protected static function getTime()
    {
        return microtime(true);
    }

    /**
     * Track how long an event (function) took to complete.
     *
     * @param string        $eventName The name of the event.
     * @param callable|null $event     The function to track time for this event.
     *
     * @return mixed              Any results returned by the function.
     * @throws RefineryException
     */
    public static function trackEvent($eventName, callable $event = null)
    {
        if (self::isEnabled()) {
            self::startEvent($eventName);
        }

        if ($event) {
            $eventReturn = $event();
            $trackTime = true;
        } else {
            $eventReturn = null;
            $trackTime = false;
        }

        if (self::isEnabled()) {
            self::endEvent($eventName, $trackTime);
        }

        return $eventReturn;
    }

    /**
     * Record the start of the timed event.
     *
     * @param string $eventName
     */
    protected static function startEvent($eventName = '')
    {
        self::$eventStartArray[$eventName] = self::getTime();
    }

    /**
     * Record the end of a timed event.
     *
     * @param string $eventName
     * @param bool   $trackTime
     *
     * @throws RefineryException
     */
    protected static function endEvent($eventName = '', $trackTime = true)
    {
        if (!isset(self::$eventStartArray[$eventName])) {
            throw new RefineryException('Event specified (' . $eventName . ') was never started');
        }

        if ($trackTime) {
            self::$eventDurationArray[$eventName] = self::getTime() - self::$eventStartArray[$eventName];
        } else {
            self::$eventDurationArray[$eventName] = false;
        }
    }

    /**
     * Return an array of the performance data that was collected.
     *
     * @return array
     * @throws RefineryException
     */
    public static function getPerformanceData()
    {
        if (!self::$initialized) {
            throw new RefineryException(get_class() . ' was not initialized');
        }

        $data = array();

        if (self::xhProfIsEnabled()) {
            $profilerNamespace = 'Refinery';
            $xhprofData = xhprof_disable();
            $xhprofRuns = new \XHProfRuns_Default();
            $runID = $xhprofRuns->save_run($xhprofData, $profilerNamespace);

            $data['xhprof_report_url'] = sprintf(DIManager::getConfig()->getItem('XHProfLinkPath') . '?run=%s&amp;source=%s&amp;sort=excl_wt', $runID, $profilerNamespace);
        }

        $output = array();
        $eventTotal = 0.0;

        foreach (self::$eventDurationArray as $name => $duration) {
            $addOutput = array();

            $addOutput['name'] = $name;

            if ($duration !== false) {
                $addOutput['duration'] = $duration;
            }

            $output[] = $addOutput;
            $eventTotal += $duration;
        }

        $total = self::getTime() - self::$start;

        $data += array(
            'total' => $total,
            'total_events' => $eventTotal,
            'total_other' => $total - $eventTotal,
            'events' => $output
        );

        return $data;
    }

    /**
     * Check either XHProf is enabled and throw an exception if the path is
     * invalid.
     *
     * @return bool
     * @throws RefineryException
     */
    protected static function xhProfIsEnabled()
    {
        if (extension_loaded('xhprof') && DIManager::getConfig()->getItem('XHProfLinkPath')) {
            $xhProfPath = DIManager::getConfig()->getItem('XHProfInstallPath') . '/xhprof_lib/utils/xhprof_lib.php';

            if (!file_exists($xhProfPath)) {
                throw new RefineryException('Unable to find XHProf on path specified (' . $xhProfPath . ')');
            }

            return true;
        }

        return false;
    }

    /**
     * Check either PerformanceTracker is enabled.
     *
     * @return boolean
     */
    public static function isEnabled()
    {
        if (self::$enabled === null) {
            self::setEnabled((bool) DIManager::getConfig()->getItem('Server.Performance'));
        }

        return self::$enabled;
    }

    /**
     * Set the enabled status for PerformanceTracker.
     *
     * @param boolean $enabled
     */
    public static function setEnabled($enabled)
    {
        self::$enabled = (bool) $enabled;
    }
}