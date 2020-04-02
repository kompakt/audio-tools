<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests\Runner;

use Kompakt\AudioTools\Runner\Exception\RuntimeException;
use Kompakt\AudioTools\Runner\EyeD3Runner;
use PHPUnit\Framework\TestCase;

class EyeD3RunnerTest extends TestCase
{
    public function testExec()
    {
        $eyeD3Runner = new EyeD3Runner(TESTS_KOMPAKT_AUDIOTOOLS_EYED3);
        $eyeD3Runner->execute('');
        $this->assertTrue((count($eyeD3Runner->getOutput()) > 0));
    }

    public function testCmdNotFound()
    {
        $this->expectException(RuntimeException::class);

        $eyeD3Runner = new EyeD3Runner('xxx');
        $eyeD3Runner->execute('--help 2> /dev/null');
    }
}