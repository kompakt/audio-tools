<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Factory;

use Kompakt\AudioTools\Runner\SoxiRunner;
use Kompakt\AudioTools\Soxi;

class SoxiFactory
{
    protected $soxiRunner = null;

    public function __construct(SoxiRunner $soxiRunner)
    {
        $this->soxiRunner = $soxiRunner;
    }

    public function getInstance($pathname)
    {
        return new Soxi($this->soxiRunner, $pathname);
    }
}