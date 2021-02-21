<?php

/*
 * This file is part of the kompakt/audio-tools package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

/*
 * kid3-cli setup on osx and setting artwork:
 * https://apple.stackexchange.com/questions/259826/how-do-i-add-cover-art-to-an-aiff-on-osx-from-the-commandline/259841
 *
 * kid3-cli examples:
 * https://kid3.sourceforge.io/kid3_en.html#kid3-cli-examples
 *
 * Mapping of Unified Frame Types to Various Formats:
 * https://kid3.sourceforge.io/kid3_en.html#table-frame-list
 *
 */
namespace Kompakt\AudioTools;

use Kompakt\AudioTools\Exception\InvalidArgumentException;

class Kid3Helper
{
    protected $title = null;
    protected $artist = null;
    protected $releaseDate = null;
    protected $album = null;
    protected $comment = null;
    protected $track = null;
    protected $images = [];

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function setAlbum($album)
    {
        $this->album = $album;
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function setTrack($track)
    {
        $this->track = $track;
        return $this;
    }

    public function addImage($pathname, $title = 'Album Cover')
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
            $this->images[$pathname] = [];
        }

        if (!array_key_exists($title, $this->images[$pathname]))
        {
            $this->images[$pathname][$title] = $title;
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

        if ($this->title)
        {
            $cmd = sprintf(
                '%s -c \'set title "%s"\'',
                $cmd,
                $this->quote($this->title)
            );
        }

        if ($this->artist)
        {
            $cmd = sprintf(
                '%s -c \'set artist "%s"\'',
                $cmd,
                $this->quote($this->artist)
            );
        }

        if ($this->album)
        {
            $cmd = sprintf(
                '%s -c \'set album "%s"\'',
                $cmd,
                $this->quote($this->album)
            );
        }

        if ($this->releaseDate)
        {
            $cmd = sprintf(
                '%s -c \'set date "%s"\'',
                $cmd,
                $this->releaseDate->format('Y')
            );
        }

        if ($this->track)
        {
            $cmd = sprintf(
                '%s -c \'set tracknumber "%s"\'',
                $cmd,
                $this->quote($this->track)
            );
        }

        if ($this->comment)
        {
            $cmd = sprintf(
                '%s -c \'set comment "%s"\'',
                $cmd,
                $this->quote($this->comment)
            );
        }

        foreach ($this->images as $pathname => $titles)
        {
            foreach ($titles as $title)
            {
                $cmd = sprintf(
                    '%s -c \'set picture:"%s" "%s"\'',
                    $cmd,
                    $pathname,
                    $title
                );
            }
        }

        return sprintf(
            '-c \'select "%s"\' %s -c \'save\'',
            $this->quote($inFile),
            $cmd
        );
    }

    protected function quote($s)
    {
        $s = preg_replace('/\'/', "'\''", $s);
        return $s;
    }

    public function __clone()
    {}
}