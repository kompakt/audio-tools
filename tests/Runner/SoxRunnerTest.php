<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests\Runner;

use Kompakt\AudioTools\Runner\Exception\RuntimeException;
use Kompakt\AudioTools\Runner\SoxRunner;
use PHPUnit\Framework\TestCase;

class SoxRunnerTest extends TestCase
{
    public function testExec()
    {
        $soxRunner = new SoxRunner(TESTS_KOMPAKT_AUDIOTOOLS_SOX);
        $soxRunner->execute('--help');
        $this->assertTrue((count($soxRunner->getOutput()) > 1));
    }

    public function testCmdNotFound()
    {
        $this->expectException(RuntimeException::class);

        $soxRunner = new SoxRunner('xxx');
        $soxRunner->execute('--help 2> /dev/null');
    }
}