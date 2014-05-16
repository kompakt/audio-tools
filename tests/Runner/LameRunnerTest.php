<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests\Runner;

use Kompakt\AudioTools\Runner\LameRunner;

class LameRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $lameRunner = new LameRunner(TESTS_KOMPAKT_AUDIOTOOLS_LAME);
        $lameRunner->execute('--help');
        $this->assertTrue((count($lameRunner->getOutput()) > 1));
    }

    /**
     * @expectedException Kompakt\AudioTools\Runner\Exception\RuntimeException
     */
    public function testCmdNotFound()
    {
        $lameRunner = new LameRunner('xxx');
        $lameRunner->execute('--help 2> /dev/null');
    }
}