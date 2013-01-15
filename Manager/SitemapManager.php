<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Manager;

use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;

/**
 * Sitemap manager class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SitemapManager
{
    protected $defaults;

    protected $maxPerSitemap;

    protected $entries;

    /**
     * @var GeneratorInterface[]
     */
    protected $generators = array();

    /**
     * Class constructor
     *
     * @param array $defaults
     */
    public function __construct(array $defaults, $maxPerSitemap)
    {
        $this->defaults = $defaults;
        $this->maxPerSitemap = $maxPerSitemap;
    }

    public function addGenerator(GeneratorInterface $generator)
    {
        $this->generators[] = $generator;
    }

    /**
     * @return \Dpn\XmlSitemapBundle\Sitemap\Entry[]
     */
    public function getSitemapEntries()
    {
        $this->buildSitemapEntries();

        return $this->entries;
    }

    public function countSitemapEntries()
    {
        $this->buildSitemapEntries();

        return count($this->entries);
    }

    public function getNumberOfSitemaps()
    {
        $total = $this->countSitemapEntries();

        if ($total <= $this->maxPerSitemap) {
            return 1;
        }

        return (int) ceil($total / $this->maxPerSitemap);
    }

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

    protected function buildSitemapEntries()
    {
        if ($this->entries) {
            return;
        }

        /** @var $entries \Dpn\XmlSitemapBundle\Sitemap\Entry[] */
        $entries = array();

        foreach ($this->generators as $generator) {
            $entries = array_merge($entries, $generator->generate());
        }

        // set defaults
        foreach ($entries as $entry) {
            $entry->setDefaults($this->defaults);
        }

        $this->entries = $entries;
    }
}