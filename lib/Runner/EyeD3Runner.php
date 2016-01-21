<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Runner;

use Kompakt\AudioTools\Runner\Exception\RuntimeException;

class EyeD3Runner
{
    protected $bin = null;
    protected $returnCode = 0;
    protected $output = array();

    public function __construct($bin = 'eyed3')
    {
        $this->bin = $bin;
    }

    public function execute($args)
    {
        $this->returnCode = 0;
        $this->output = array();
        $cmd = sprintf("%s %s", $this->bin, $args);
        $lastLine = exec($cmd, $this->output, $this->returnCode);

        if ($this->returnCode > 0)
        {
            throw new RuntimeException(sprintf('Eyed3 execution error: "%s". Cmd: "%s"', $lastLine, $cmd));
        }
        
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