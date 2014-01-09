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
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $lastMod;

    /**
     * @var string
     */
    protected $changeFreq;

    /**
     * @var string
     */
    protected $priority;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var array
     */
    protected $defaults = array(
        'scheme'     => 'http',
        'priority'   => '0.5',
        'changefreq' => 'weekly'
    );

    /**
     * @param array $defaults
     */
    public function setDefaults(array $defaults = array())
    {
        $defaults = array_merge($this->defaults, $defaults);

        if (null === $this->getChangeFreq()) {
            $this->setChangeFreq($defaults['changefreq']);
        }

        if (null === $this->getPriority()) {
            $this->setPriority($defaults['priority']);
        }

        if (null === $this->getScheme()) {
            $this->setScheme($defaults['scheme']);
        }
    }

    /**
     * @param string $changeFreq
     */
    public function setChangeFreq($changeFreq)
    {
        $changeFreq = strtolower($changeFreq);

        if (in_array($changeFreq, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
            $this->changeFreq = $changeFreq;
        }
    }

    /**
     * @return null|string
     */
    public function getChangeFreq()
    {
        return $this->changeFreq;
    }

    /**
     * @param mixed $lastMod
     */
    public function setLastMod($lastMod)
    {
        if ($lastMod instanceof \DateTime) {
            $this->lastMod = $lastMod->format('Y-m-d');
            return;
        }

        if (1 === preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}[\+|\-]\d{2}:\d{2}$/', $lastMod) ||
            1 === preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $lastMod)
        ) {
            $this->lastMod = $lastMod;
        }
    }

    /**
     * @return null|string
     */
    public function getLastMod()
    {
        return $this->lastMod;
    }

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        if (true === is_numeric($priority)) {
            $priority = round(floatval($priority), 1);
            if (0 <= $priority && 1 >= $priority) {
                $this->priority = (string) $priority;
            }
        }
    }

    /**
     * @return null|string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $uri
     * @param boolean $checkFormat
     */
    public function setUri($uri, $checkFormat = true)
    {
        if (true === $checkFormat) {
            $this->uri = '/' . trim($uri, '/');
        } else {
            $this->uri = $uri;
        }
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return null|string
     */
    public function getScheme()
    {
        return $this->scheme;
    }
}
