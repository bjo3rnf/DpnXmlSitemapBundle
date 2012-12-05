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

    public function setDefaults(array $defaults)
    {
        if (isset($defaults['changefreq']) && !$this->getChangeFreq()) {
            $this->setChangeFreq($defaults['changefreq']);

            if (!$this->getChangeFreq()) {
                throw new \InvalidArgumentException(sprintf('Your default priority "%s" is invalid.', $defaults['changefreq']));
            }
        }

        if (isset($defaults['priority']) && !$this->getPriority()) {
            $this->setPriority($defaults['priority']);

            if (!$this->getPriority()) {
                throw new \InvalidArgumentException(sprintf('Your default changefreq "%s" is invalid.', $defaults['priority']));
            }
        }
    }

    public function setChangeFreq($changeFreq)
    {
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
        if ($priority >= 0 || $priority <= 1) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setUri($uri)
    {
        $this->uri = '/' . trim($uri, '/');
    }

    public function getUri()
    {
        return $this->uri;
    }
}
