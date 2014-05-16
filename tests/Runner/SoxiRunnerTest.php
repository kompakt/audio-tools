<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests\Runner;

use Kompakt\AudioTools\Runner\SoxiRunner;

class SoxiRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $inFile = sprintf('%s/_files/SoxiRunnerTest/05-seconds.wav', __DIR__);
        $soxiRunner = new SoxiRunner(TESTS_KOMPAKT_AUDIOTOOLS_SOXI);
        $type = $soxiRunner->execute(sprintf('-t %s', $inFile));
        $this->assertEquals('wav', $type);
    }

    /**
     * @expectedException Kompakt\AudioTools\Runner\Exception\RuntimeException
     */
    public function testCmdNotFound()
    {
        $soxiRunner = new SoxiRunner('xxx');
        $soxiRunner->execute('--help 2> /dev/null');
    }
}