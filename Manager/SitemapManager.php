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
 */
class SitemapManager
{
    protected $defaults;

    /**
     * @var GeneratorInterface[]
     */
    protected $generators = array();

    /**
     * Class constructor
     *
     * @param array $defaults
     */
    public function __construct(array $defaults)
    {
        $this->defaults = $defaults;
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
        /** @var $entries \Dpn\XmlSitemapBundle\Sitemap\Entry[] */
        $entries = array();

        foreach ($this->generators as $generator) {
            $entries = array_merge($entries, $generator->generate());
        }

        // set defaults
        foreach ($entries as $entry) {
            $entry->setDefaults($this->defaults);
        }

        return $entries;
    }
}