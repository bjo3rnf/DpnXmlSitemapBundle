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
     * @var UrlSet|null
     */
    protected $urlSet;

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
     * @return UrlSet
     */
    public function getUrlSet()
    {
        if (null !== $this->urlSet) {
            return $this->urlSet;
        }

        $urlSet = new UrlSet();

        foreach ($this->generators as $generator) {
            $urlSet->combine($generator->generate());
        }

        return $this->urlSet = $urlSet;
    }

    /**
     * @return int
     */
    public function countSitemapUrls()
    {
        return count($this->getUrlSet());
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
     * @return UrlSet
     *
     * @throws \InvalidArgumentException
     */
    public function getUrlSetForSitemap($number)
    {
        $numberOfSitemaps = $this->getNumberOfSitemaps();

        if (1 > $number) {
            throw new \InvalidArgumentException('Number must be positive.');
        }

        if ($number > $numberOfSitemaps) {
            throw new \InvalidArgumentException('Number exceeds total sitemap count.');
        }

        if (1 === $numberOfSitemaps) {
            return $this->getUrlSet();
        }

        return $this->getUrlSet()->slice(($number - 1) * $this->maxPerSitemap, $this->maxPerSitemap);
    }

    /**
     * @param int|null $number
     *
     * @return string
     */
    public function renderSitemap($number = null)
    {
        return $this->templating->render(
            'DpnXmlSitemapBundle::sitemap.xml.twig',
            array(
                'urls' => null === $number ? $this->getUrlSet() : $this->getUrlSetForSitemap($number),
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
