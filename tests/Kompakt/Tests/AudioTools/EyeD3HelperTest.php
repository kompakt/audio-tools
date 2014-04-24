<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\Tests\AudioTools;

use Kompakt\AudioTools\EyeD3Helper;
use Kompakt\AudioTools\Runner\EyeD3Runner;

class EyeD3HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testAddImage()
    {
        $outFile = $this->getMp3TestFile(__METHOD__);
        $artwork = sprintf('%s/_files/EyeD3HelperTest/artwork.jpg', __DIR__);
        
        $eyeD3Helper = new EyeD3Helper();
        $eyeD3Runner = new EyeD3Runner(TESTS_KOMPAKT_AUDIOTOOLS_EYED3);
        $eyeD3Helper->addImage($artwork);
        $eyeD3Runner->execute($eyeD3Helper->getCmd($outFile));
        $this->assertFileExists($outFile);
    }

    /**
     * @expectedException Kompakt\AudioTools\Exception\InvalidArgumentException
     */
    public function testInvalidArtwork()
    {
        $eyeD3Helper = new EyeD3Helper(TESTS_KOMPAKT_AUDIOTOOLS_EYED3);
        $eyeD3Helper->addImage('xxx.jpg');
    }

    public function testQuoting()
    {
        $outFile = $this->getMp3TestFile(__METHOD__);

        $eyeD3Helper = new EyeD3Helper(TESTS_KOMPAKT_AUDIOTOOLS_EYED3);
        $eyeD3Helper->setArtist("abc'abc");
        $this->assertRegExp("/.* --artist 'abc'\\\''abc' .*/", $eyeD3Helper->getCmd($outFile));
    }

    protected function getMp3TestFile($method)
    {
        $tmpDir = freshTmpSubDir($method);
        $inFile = sprintf('%s/_files/EyeD3HelperTest/30-seconds.mp3', __DIR__);
        $outFile = sprintf('%s/30-seconds.mp3', $tmpDir);
        copy($inFile, $outFile);
        return $outFile;
    }
}