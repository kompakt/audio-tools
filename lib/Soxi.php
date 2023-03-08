<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools;

use Kompakt\AudioTools\Exception\InvalidArgumentException;
use Kompakt\AudioTools\Runner\SoxiRunner;

class Soxi
{
    protected $soxiRunner = null;
    protected $pathname = null;

    public function __construct(SoxiRunner $soxiRunner, string $pathname)
    {
        $info = new \SplFileInfo($pathname);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('Audio file not found: %s', $pathname));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Audio file not readable: %s', $pathname));
        }
        
        $this->soxiRunner = $soxiRunner;
        $this->pathname = $pathname;
    }

    public function getBitsPerSample()
    {
        return $this->soxiRunner->execute(sprintf("-b '%s'", $this->pathname));
    }

    public function getAverageBitrate()
    {
        $lastLine = $this->soxiRunner->execute(sprintf("-B '%s'", $this->pathname));

        if (preg_match('/k$/i', $lastLine))
        {
            return (int) trim($lastLine, 'k');
        }
        else if (preg_match('/M$/i', $lastLine))
        {
            return trim($lastLine, 'M') * 1024;
        }

        return $lastLine;
    }

    public function getSampleRate()
    {
        return $this->soxiRunner->execute(sprintf("-r '%s'", $this->pathname));
    }

    public function getType()
    {
        return $this->soxiRunner->execute(sprintf("-t '%s'", $this->pathname));
    }

    function getDuration()
    {
        return $this->soxiRunner->execute(sprintf("-D '%s'", $this->pathname));
    }
}