<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2015 Vanilla Forums Inc.
 * @license MIT
 */

namespace Garden\Cli\Tests\Logger\Formatter;

use Garden\Cli\Tests\CliTestCase;
use Garden\Cli\Logger\Formatter\ColorStreamFormatter;

/**
 * Includes tests for the {@link \Garden\Cli\Logger\Logger} class.
 */
class ColorStreamFormatterTest extends CliTestCase {

    /**
     * @var ColorStreamFormatter An instantiated formatter.
     */
    protected $formatter;

    protected function setUp() {
        parent::setUp();
        $this->formatter = new ColorStreamFormatter;
    }

    public function testSetGetDateFormat() {
        $this->assertEquals('[%F %T]', $this->formatter->getDateFormat()); // default value
        $this->formatter->setDateFormat('[%F %T %H:%m:%s]');
        $this->assertEquals('[%F %T %H:%m:%s]', $this->formatter->getDateFormat());
    }

    public function testSetGetShowDurations() {
        $this->assertEquals(true, $this->formatter->getShowDurations());
        $this->formatter->setShowDurations(false);
        $this->assertEquals(false, $this->formatter->getShowDurations());
        $this->formatter->setShowDurations(true);
        $this->assertEquals(true, $this->formatter->getShowDurations());
    }

    public function testFormatDurationMicro() {
        $result = $this->formatter->formatDuration(0.00005);
        $this->assertEquals('5μs', $result);
    }

    public function testFormatDurationMilli() {
        $result = $this->formatter->formatDuration(0.06);
        $this->assertEquals('6ms', $result);
    }

    public function testFormatDurationSeconds() {
        $result = $this->formatter->formatDuration(7);
        $this->assertEquals('7s', $result);
    }

    public function testFormatDurationMinutes() {
        $result = $this->formatter->formatDuration(90);
        $this->assertEquals('1.5m', $result);
    }

    public function testFormatDurationHours() {
        $result = $this->formatter->formatDuration(7290);
        $this->assertEquals('2h', $result);
    }

    public function testFormatDurationDays() {
        $result = $this->formatter->formatDuration(259200);
        $this->assertEquals('3d', $result);
    }

    public function testFormatSuccess() {
        $timestamp = time();
        $date = strftime($this->formatter->getDateFormat(), $timestamp);
        $result = $this->formatter->format($timestamp, 'success', 0, 'abc', 0.00004);
        $this->assertEquals($date . '  [1;32mabc[0m [1;34m4μs[0m', $result);
    }

    public function testFormatWarning() {
        $timestamp = time();
        $date = strftime($this->formatter->getDateFormat(), $timestamp);
        $result = $this->formatter->format($timestamp, 'warning', 1, 'abc', null);
        $this->assertEquals($date . '   -  [1;33mabc[0m', $result);
    }

    public function testFormatError() {
        $timestamp = time();
        $date = strftime($this->formatter->getDateFormat(), $timestamp);
        $result = $this->formatter->format($timestamp, 'error', 2, 'abc', 42);
        $this->assertEquals($date . '     -  [1;31mabc[0m [1;34m42s[0m', $result);
    }

    public function testFormatInfo() {
        $timestamp = time();
        $date = strftime($this->formatter->getDateFormat(), $timestamp);
        $result = $this->formatter->format($timestamp, 'info', 3, 'abc', null);
        $this->assertEquals($date . '       -  abc[0m', $result);
    }
}
