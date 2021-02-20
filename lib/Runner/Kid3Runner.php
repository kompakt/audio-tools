<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Runner;

use Kompakt\AudioTools\Runner\Exception\RuntimeException;

class Kid3Runner
{
    protected $bin = null;
    protected $tempWorkDir = null;
    protected $returnCode = 0;
    protected $output = [];

    public function __construct($bin = 'kid3', $tempWorkDir = '/')
    {
        $this->bin = $bin;
        $this->tempWorkDir = $tempWorkDir;
    }

    public function execute($args)
    {
        $this->returnCode = 0;
        $this->output = [];

        $cwd = getcwd(); // Save current working directory
        $cmd = sprintf("%s %s", $this->bin, $args);

        chdir($this->tempWorkDir); // Set working dir to guarantee that kid3 runs from a parent directory relative to the audio file - kid3 tagging doesn't seem to work when run from sibling or child directory relative to audio file


        $lastLine = exec($cmd, $this->output, $this->returnCode);

        chdir($cwd); // restore working dir

        /*if ($this->returnCode > 0)
        {
            throw new RuntimeException(sprintf('Kid3 execution error: "%s". Cmd: "%s"', $lastLine, $cmd));
        }*/

        return $lastLine;
    }

    public function getReturnCode()
    {
        return $this->returnCode;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function __clone()
    {}
}