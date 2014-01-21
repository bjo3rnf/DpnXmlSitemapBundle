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
interface GeneratorInterface
{
    /**
     * @return \Dpn\XmlSitemapBundle\Sitemap\Entry[]
     */
    public function generate();

    /**
     * @param array $defaults
     */
    public function setDefaults(array $defaults);

    /**
     * @param mixed $lastMod
     * @return string|null
     */
    public function normalizeLastMod($lastMod);

    /**
     * @param mixed $priority
     * @return float|null
     */
    public static function normalizePriority($priority);


    /**
     * @param mixed $changeFreq
     * @return string|null
     */
    public static function normalizeChangeFreq($changeFreq);
}
