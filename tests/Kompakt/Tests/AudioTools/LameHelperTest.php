<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\AudioTools;

use Kompakt\AudioTools\LameHelper;
use Kompakt\AudioTools\Runner\LameRunner;
use Kompakt\TestHelper\Filesystem\TmpDir;

class LameHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testStandard()
    {
        $tmpDir = new TmpDir(TESTS_KOMPAKT_AUDIOTOOLS_TEMP_DIR);
        $pathname = $tmpDir->makeSubDir($tmpDir->prepareSubDirPath(__CLASS__));
        $inFile = sprintf('%s/_files/LameHelperTest/30-seconds.wav', __DIR__);
        $outFile = sprintf('%s/30-seconds.mp3', $pathname);

        $lameHelper = new LameHelper();
        $lameRunner = new LameRunner(TESTS_KOMPAKT_AUDIOTOOLS_LAME);
        $lameHelper->setPreset(112);
        $lameRunner->execute($lameHelper->getCmd($inFile, $outFile));
        $this->assertFileExists($outFile);
    }
}