<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools\Exception;

use Kompakt\AudioTools\Exception as AudioToolsException;

class InvalidArgumentException extends \InvalidArgumentException implements AudioToolsException
{}