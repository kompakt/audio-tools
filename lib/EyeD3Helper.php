<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

/*
 * Eved3 Doc: https://eyed3.readthedocs.io/en/latest/plugins/classic_plugin.html
 *
 */
namespace Kompakt\AudioTools;

use Kompakt\AudioTools\Exception\InvalidArgumentException;

class EyeD3Helper
{
    const IMAGE_FRONT_COVER = 'FRONT_COVER';

    protected $removeAll = null;
    protected $removeAllImages = null;
    protected $toV23 = null;
    protected $toV24 = null;
    protected $encoding = null;
    protected $compilation = null;
    protected $publisher = null;
    protected $title = null;
    protected $artist = null;
    protected $albumArtist = null;
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

    public function toV23($flag)
    {
        $this->toV23 = $flag;
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

    public function setCompilation($flag)
    {
        $this->compilation = $this->flag($flag);
        return $this;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $this->quote($publisher);
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

    public function setAlbumArtist($albumArtist)
    {
        $this->albumArtist = $this->quote($albumArtist);
        return $this;
    }

    public function setReleaseDate(\DateTime $releaseDate)
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

        if ($this->toV23 !== null)
        {
            $cmd = sprintf("%s --to-v2.3", $cmd);
        }

        if ($this->toV24 !== null)
        {
            $cmd = sprintf("%s --to-v2.4", $cmd);
        }

        if ($this->encoding !== null)
        {
            $cmd = sprintf("%s --encoding %s", $cmd, $this->encoding);
        }

        if ($this->compilation !== null)
        {
            $cmd = sprintf("%s --text-frame TCMP:%s", $cmd, $this->compilation);
        }

        if ($this->publisher !== null)
        {
            $cmd = sprintf("%s --publisher '%s'", $cmd, $this->publisher);
        }

        if ($this->title !== null)
        {
            $cmd = sprintf("%s --title '%s'", $cmd, $this->title);
        }

        if ($this->artist !== null)
        {
            $cmd = sprintf("%s --artist '%s'", $cmd, $this->artist);
        }

        if ($this->albumArtist !== null)
        {
            $cmd = sprintf("%s --album-artist '%s'", $cmd, $this->albumArtist);
        }

        if ($this->album !== null)
        {
            $cmd = sprintf("%s --album '%s'", $cmd, $this->album);
        }

        if ($this->releaseDate !== null)
        {
            $cmd = sprintf("%s --release-date '%s'", $cmd, $this->releaseDate->format('Y-m-d'));
            $cmd = sprintf("%s --orig-release-date '%s'", $cmd, $this->releaseDate->format('Y-m-d'));
            $cmd = sprintf("%s --recording-date '%s'", $cmd, $this->releaseDate->format('Y-m-d'));
            $cmd = sprintf("%s --release-year '%s'", $cmd, $this->releaseDate->format('Y'));
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

    public function __clone()
    {}

    protected function quote($s)
    {
        $s = preg_replace('/\'/', "'\''", $s);
        $s = preg_replace('/^-/', " -", $s); // don't confuse eyed3 with values looking like arguments
        return $s;
    }

    protected function flag($s)
    {
        return ($s) ? '1' : '0';
    }
}