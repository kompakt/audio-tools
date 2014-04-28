<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\AudioTools;

use Kompakt\AudioTools\Exception\InvalidArgumentException;

class EyeD3Helper
{
    const IMAGE_FRONT_COVER = 'FRONT_COVER';

    protected $removeAll = null;
    protected $removeAllImages = null;
    protected $toV24 = null;
    protected $encoding = null;
    protected $title = null;
    protected $artist = null;
    protected $releaseYear = null;
    protected $releaseDate = null;
    protected $album = null;
    protected $comment = null;
    protected $track = null;
    protected $trackTotal = null;
    protected $images = array();

    public function setRemoveAll($flag)
    {
        $this->removeAll = $flag;
        return $this;
    }

    public function setRemoveAllImages($flag)
    {
        $this->removeAllImages = $flag;
        return $this;
    }

    public function toV24($flag)
    {
        $this->toV24 = $flag;
        return $this;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $this->quote($title);
        return $this;
    }

    public function setArtist($artist)
    {
        $this->artist = $this->quote($artist);
        return $this;
    }

    public function setReleaseYear(\DateTime $releaseYear = null)
    {
        $this->releaseYear = $releaseYear;
        return $this;
    }

    public function setReleaseDate(\DateTime $releaseDate = null)
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function setAlbum($album)
    {
        $this->album = $this->quote($album);
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $this->quote($comment);
        return $this;
    }

    public function setTrack($track)
    {
        $this->track = $track;
        return $this;
    }

    public function setTrackTotal($trackTotal)
    {
        $this->trackTotal = $trackTotal;
        return $this;
    }

    public function addImage($pathname, $type = self::IMAGE_FRONT_COVER)
    {
        $info = new \SplFileInfo($pathname);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('Image not found: %s', $pathname));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('Image not readable: %s', $pathname));
        }

        if (!array_key_exists($pathname, $this->images))
        {
            $this->images[$pathname] = array();
        }

        if (!array_key_exists($type, $this->images[$pathname]))
        {
            $this->images[$pathname][$type] = $type;
        }

        return $this;
    }

    public function getCmd($inFile)
    {
        $info = new \SplFileInfo($inFile);

        if (!$info->isFile())
        {
            throw new InvalidArgumentException(sprintf('File not found: %s', $inFile));
        }

        if (!$info->isReadable())
        {
            throw new InvalidArgumentException(sprintf('File not readable: %s', $inFile));
        }
        
        if (!$info->isWritable())
        {
            throw new FileNotReadableException(sprintf('File not writable: %s', $inFile));
        }

        $cmd = '';

        if ($this->removeAll !== null)
        {
            $cmd = sprintf("%s --remove-all", $cmd);
        }

        if ($this->removeAllImages !== null)
        {
            $cmd = sprintf("%s --remove-all-images", $cmd);
        }

        if ($this->toV24 !== null)
        {
            $cmd = sprintf("%s --to-v2.4", $cmd);
        }

        if ($this->encoding !== null)
        {
            $cmd = sprintf("%s --encoding %s", $cmd, $this->encoding);
        }

        if ($this->title !== null)
        {
            $cmd = sprintf("%s --title '%s'", $cmd, $this->title);
        }

        if ($this->artist !== null)
        {
            $cmd = sprintf("%s --artist '%s'", $cmd, $this->artist);
        }

        if ($this->album !== null)
        {
            $cmd = sprintf("%s --album '%s'", $cmd, $this->album);
        }

        if ($this->releaseDate !== null)
        {
            $cmd = sprintf("%s --release-date %s", $cmd, $this->releaseDate->format('Y-m-d'));
        }

        if ($this->releaseYear !== null)
        {
            $cmd = sprintf("%s --release-year %s", $cmd, $this->releaseYear->format('Y'));
        }

        if ($this->track !== null)
        {
            $cmd = sprintf("%s --track '%s'", $cmd, $this->track);
        }

        if ($this->trackTotal !== null)
        {
            $cmd = sprintf("%s --track-total '%s'", $cmd, $this->trackTotal);
        }

        if ($this->comment !== null)
        {
            $cmd = sprintf("%s --comment '%s'", $cmd, $this->comment);
        }

        foreach ($this->images as $pathname => $types)
        {
            foreach ($types as $type)
            {
                 $cmd = sprintf("%s --add-image '%s:%s'", $cmd, $pathname, $type);
            }
        }

        return sprintf("%s '%s'", $cmd, $inFile);
    }

    protected function quote($s)
    {
        return preg_replace('/\'/', "'\''", $s);
    }
}