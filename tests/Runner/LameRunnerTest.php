<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests\Runner;

use Kompakt\AudioTools\Runner\Exception\RuntimeException;
use Kompakt\AudioTools\Runner\LameRunner;
use PHPUnit\Framework\TestCase;

class LameRunnerTest extends TestCase
{
    public function testExec()
    {
        $lameRunner = new LameRunner(TESTS_KOMPAKT_AUDIOTOOLS_LAME);
        $lameRunner->execute('--help');
        $this->assertTrue((count($lameRunner->getOutput()) > 1));
    }

    public function testCmdNotFound()
    {
        $this->expectException(RuntimeException::class);

        $lameRunner = new LameRunner('xxx');
        $lameRunner->execute('--help 2> /dev/null');
    }
}