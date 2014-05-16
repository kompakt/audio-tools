<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests\Runner;

use Kompakt\AudioTools\Runner\EyeD3Runner;

class EyeD3RunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testExec()
    {
        $eyeD3Runner = new EyeD3Runner(TESTS_KOMPAKT_AUDIOTOOLS_EYED3);
        $eyeD3Runner->execute('--help');
        $this->assertTrue((count($eyeD3Runner->getOutput()) > 1));
    }

    /**
     * @expectedException Kompakt\AudioTools\Runner\Exception\RuntimeException
     */
    public function testCmdNotFound()
    {
        $eyeD3Runner = new EyeD3Runner('xxx');
        $eyeD3Runner->execute('--help 2> /dev/null');
    }
}