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
    protected $url;

    /**
     * @var string|null
     */
    protected $lastMod;

    /**
     * @var string|null
     */
    protected $changeFreq;

    /**
     * @var float|null
     */
    protected $priority;

    /**
     * @param string $url
     * @param string|null $lastMod
     * @param string|null $changeFreq
     * @param float|null  $priority
     */
    public function __construct($url, $lastMod = null, $changeFreq = null, $priority = null)
    {
        $components = parse_url($url);

        if (!isset($components['scheme']) || !isset($components['host'])) {
            throw new \InvalidArgumentException(sprintf('The url "%s" is not absolute.', $url));
        }

        $this->url = $url;

        $this->lastMod = $lastMod;
        $this->changeFreq = $changeFreq;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function getLastMod()
    {
        return $this->lastMod;
    }

    /**
     * @return null|string
     */
    public function getChangeFreq()
    {
        return $this->changeFreq;
    }

    /**
     * @return null|float
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
