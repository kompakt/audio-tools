<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\AudioTools\Runner;

use Kompakt\AudioTools\Runner\SoxRunner;

class SoxRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $soxRunner = new SoxRunner(TESTS_KOMPAKT_AUDIOTOOLS_SOX);
        $soxRunner->execute('--help');
        $this->assertTrue((count($soxRunner->getOutput()) > 1));
    }

    /**
     * @expectedException Kompakt\AudioTools\Runner\Exception\RuntimeException
     */
    public function testCmdNotFound()
    {
        $soxRunner = new SoxRunner('xxx');
        $soxRunner->execute('--help 2> /dev/null');
    }
}