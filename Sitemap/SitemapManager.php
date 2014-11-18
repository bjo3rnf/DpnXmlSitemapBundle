<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

use Symfony\Component\Templating\EngineInterface;

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
     * @var int
     */
    protected $maxPerSitemap;

    /**
     * @var Url[]|null
     */
    protected $urls;

    /**
     * @var GeneratorInterface[]
     */
    protected $generators = array();

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @param array           $defaults
     * @param int             $maxPerSitemap
     * @param EngineInterface $templating
     */
    public function __construct(array $defaults, $maxPerSitemap, EngineInterface $templating)
    {
        $this->defaults = array_merge(
            array(
                'priority' => null,
                'changefreq' => null,
            ),
            $defaults
        );

        $this->maxPerSitemap = intval($maxPerSitemap);
        $this->templating = $templating;
    }

    /**
     * @param GeneratorInterface $generator
     */
    public function addGenerator(GeneratorInterface $generator)
    {
        $this->generators[] = $generator;
    }

    /**
     * @return Url[]
     */
    public function getSitemapUrls()
    {
        if (null !== $this->urls) {
            return $this->urls;
        }

        $urls = array();

        foreach ($this->generators as $generator) {
            $urls = array_merge($urls, $generator->generate());
        }

        return $this->urls = $urls;
    }

    /**
     * @return int
     */
    public function countSitemapUrls()
    {
        return count($this->getSitemapUrls());
    }

    /**
     * @return int
     */
    public function getNumberOfSitemaps()
    {
        $total = $this->countSitemapUrls();

        if ($total <= $this->maxPerSitemap) {
            return 1;
        }

        return intval(ceil($total / $this->maxPerSitemap));
    }

    /**
     * @param int $number
     *
     * @return Url[]
     *
     * @throws \InvalidArgumentException
     */
    public function getUrlsForSitemap($number)
    {
        $numberOfSitemaps = $this->getNumberOfSitemaps();

        if ($number > $numberOfSitemaps) {
            throw new \InvalidArgumentException('Number exceeds total sitemap count.');
        }

        if (1 === $numberOfSitemaps) {
            return $this->getSitemapUrls();
        }

        $sitemaps = array_chunk($this->getSitemapUrls(), $this->maxPerSitemap);

        return $sitemaps[$number - 1];
    }

    /**
     * @param int|null $number
     *
     * @return string
     */
    public function renderSitemap($number = null)
    {
        $urls = null === $number ? $this->getSitemapUrls() : $this->getUrlsForSitemap($number);

        return $this->templating->render(
            'DpnXmlSitemapBundle::sitemap.xml.twig',
            array(
                'urls' => $urls,
                'default_priority' => Url::normalizePriority($this->defaults['priority']),
                'default_changefreq' => Url::normalizeChangeFreq($this->defaults['changefreq']),
            )
        );
    }

    /**
     * @param string $host
     *
     * @return string
     */
    public function renderSitemapIndex($host)
    {
        return $this->templating->render(
            'DpnXmlSitemapBundle::sitemap_index.xml.twig',
            array(
                'num_sitemaps' => $this->getNumberOfSitemaps(),
                'host' => $host,
            )
        );
    }
}
