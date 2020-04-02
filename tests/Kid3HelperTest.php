<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Tests;

use Kompakt\AudioTools\Exception\InvalidArgumentException;
use Kompakt\AudioTools\Kid3Helper;
use Kompakt\AudioTools\Runner\Kid3Runner;
use PHPUnit\Framework\TestCase;

class Kid3HelperTest extends TestCase
{
    public function testAddImage()
    {
        $outFile = $this->getAiffTestFile(__METHOD__);
        $artwork = sprintf('%s/_files/Kid3HelperTest/artwork.jpg', __DIR__);
        
        $kid3Helper = new Kid3Helper();
        $kid3Runner = new Kid3Runner(TESTS_KOMPAKT_AUDIOTOOLS_KID3);

        $kid3Helper->addImage($artwork);
        $kid3Helper->setTitle('My Title');
        $kid3Helper->setArtist('My Artist');
        $kid3Helper->setAlbum('My Album');
        $kid3Helper->setReleaseDate(new \DateTime('2019-12-21'));
        $kid3Helper->setComment('My Comment');
        $kid3Helper->setTrack(303);

        $kid3Runner->execute($kid3Helper->getCmd($outFile));
        $this->assertFileExists($outFile);
    }

    public function testInvalidArtwork()
    {
        $this->expectException(InvalidArgumentException::class);

        $kid3Helper = new Kid3Helper();
        $kid3Helper->addImage('xxx.jpg');
    }

    protected function getAiffTestFile($method)
    {
        $tmpDir = getTmpDir();
        $pathname = $tmpDir->makeSubDir($tmpDir->prepareSubDirPath($method));
        $inFile = sprintf('%s/_files/Kid3HelperTest/30-seconds.aiff', __DIR__);
        $outFile = sprintf('%s/30-seconds.aiff', $pathname);
        copy($inFile, $outFile);
        return $outFile;
    }
}