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

    public function __construct($url, $lastMod = null, $changeFreq = null, $priority = null)
    {
        $this->url = $url;

        $this->setLastMod($lastMod);
        $this->setChangeFreq($changeFreq);
        $this->setPriority($priority);
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

    /**
     * @param string $changeFreq
     */
    private function setChangeFreq($changeFreq)
    {
        $changeFreq = strtolower($changeFreq);

        if (in_array($changeFreq, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
            $this->changeFreq = $changeFreq;
        }
    }

    /**
     * @param mixed $lastMod
     */
    private function setLastMod($lastMod)
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
     * @param string $priority
     */
    private function setPriority($priority)
    {
        if (true === is_numeric($priority)) {
            $priority = round(floatval($priority), 1);
            if (0 <= $priority && 1 >= $priority) {
                $this->priority = $priority;
            }
        }
    }
}
