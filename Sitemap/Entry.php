<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class Entry
{
    protected $uri;

    protected $lastMod;

    protected $changeFreq;

    protected $priority;

    protected $scheme;

    protected $defaults = array(
        'priority' => '0.5',
        'changefreq' => 'weekly'
    );

    public function setDefaults(array $defaults = array())
    {
        $defaults = array_merge($this->defaults, $defaults);

        if (!$this->getChangeFreq()) {
            $this->setChangeFreq($defaults['changefreq']);
        }

        if (!$this->getPriority()) {
            $this->setPriority($defaults['priority']);
        }
    }

    public function setChangeFreq($changeFreq)
    {
        $changeFreq = strtolower($changeFreq);

        if (in_array($changeFreq, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
            $this->changeFreq = $changeFreq;
        }
    }

    public function getChangeFreq()
    {
        return $this->changeFreq;
    }

    public function setLastMod($lastMod)
    {
        if ($lastMod instanceof \DateTime) {
            $this->lastMod = $lastMod->format('Y-m-d');

            return;
        }

        if (preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}[\+|\-]\d{2}:\d{2}$/', $lastMod) === 1 ||
            preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $lastMod) === 1
        ) {
            $this->lastMod = $lastMod;
        }
    }

    public function getLastMod()
    {
        return $this->lastMod;
    }

    public function setPriority($priority)
    {
        if (is_numeric($priority) && $priority >= 0 && $priority <= 1) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setUri($uri, $check_format = true)
    {
        if ($check_format) {
          $this->uri = '/' . trim($uri, '/');
        } else {
          $this->uri = $uri;
        }
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    public function getScheme()
    {
        return $this->scheme;
    }
}
