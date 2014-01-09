<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

/**
 * Sitemap manager class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SitemapManager
{
    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var integer
     */
    protected $maxPerSitemap;

    /**
     * @var \Dpn\XmlSitemapBundle\Sitemap\Entry[]|null
     */
    protected $entries;

    /**
     * @var GeneratorInterface[]
     */
    protected $generators = array();

    /**
     * Class constructor
     *
     * @param array $defaults
     * @param integer $maxPerSitemap
     */
    public function __construct(array $defaults, $maxPerSitemap)
    {
        $this->defaults = $defaults;
        $this->maxPerSitemap = intval($maxPerSitemap);
    }

    /**
     * @param GeneratorInterface $generator
     */
    public function addGenerator(GeneratorInterface $generator)
    {
        $this->generators[] = $generator;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @return \Dpn\XmlSitemapBundle\Sitemap\Entry[]
     */
    public function getSitemapEntries()
    {
        if (null !== $this->entries) {
            return $this->entries;
        }

        $entries = array();

        foreach ($this->generators as $generator) {
            $entries = array_merge($entries, $generator->generate());
        }

        return $this->entries = $entries;
    }

    /**
     * @return integer
     */
    public function countSitemapEntries()
    {
        return count($this->getSitemapEntries());
    }

    /**
     * @return integer
     */
    public function getNumberOfSitemaps()
    {
        $total = $this->countSitemapEntries();

        if ($total <= $this->maxPerSitemap) {
            return 1;
        }

        return intval(ceil($total / $this->maxPerSitemap));
    }

    /**
     * @param $number
     * @return \Dpn\XmlSitemapBundle\Sitemap\Entry[]
     * @throws \InvalidArgumentException
     */
    public function getEntriesForSitemap($number)
    {
        $numberOfSitemaps = $this->getNumberOfSitemaps();

        if ($number > $numberOfSitemaps) {
            throw new \InvalidArgumentException('Number exceeds total sitemap count.');
        }

        if (1 === $numberOfSitemaps) {
            return $this->getSitemapEntries();
        }

        $sitemaps = array_chunk($this->getSitemapEntries(), $this->maxPerSitemap);

        return $sitemaps[$number - 1];
    }
}
