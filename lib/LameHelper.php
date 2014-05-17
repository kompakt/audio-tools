<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools;

use Kompakt\AudioTools\Exception\InvalidArgumentException;

class LameHelper
{
    protected $preset = null;

    public function setPreset($preset)
    {
        $this->preset = (int) $preset;
        return $this;
    }

    public function getCmd($inFile, $outFile)
    {
        $info = new \SplFileInfo($inFile);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('Audio file not found: %s', $inFile));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Audio file not readable: %s', $inFile));
        }
        
        $info = new \SplFileInfo(dirname($outFile));

        if (!$info->isDir())
        {
            throw new InvalidArgumentException(sprintf('Output file dir not found: %s', dirname($outFile)));
        }

        if (!$info->isWritable())
        {
            throw new InvalidArgumentException(sprintf('Output file dir not writable: %s', dirname($outFile)));
        }

        $cmd = '';

        if ($this->preset !== null)
        {
            $cmd = sprintf("%s --preset %s", $cmd, $this->preset);
        }
        
        return sprintf("%s '%s' '%s'", $cmd, $inFile, $outFile);
    }

    public function __clone()
    {}
}