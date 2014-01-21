<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RouteOptionGenerator extends AbstractGenerator
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return Entry[]
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $entries = array();
        $collection = $this->router->getRouteCollection();

        foreach ($collection->all() as $name => $route) {
            /** @var \Symfony\Component\Routing\Route $route */
            $option = $route->getOption('sitemap');

            if (true !== $option && true !== is_array($option)) {
                continue;
            }

            $options = array();

            if (true === is_array($option)) {
                $options = $option;
            }

            try {
                $url = $this->router->generate($name, array(), true);
            } catch (MissingMandatoryParametersException $e) {
                throw new \InvalidArgumentException(sprintf('The route "%s" cannot have the sitemap option because it requires parameters', $name));
            }

            if (true === isset($options['lastmod'])) {
                $lastmod = $this->normalizeLastMod($options['lastmod']);
            } else {
                $lastmod = $this->defaults['lastmod'];
            }

            if (true === isset($options['changefreq'])) {
                $changefreq = $this->normalizeChangeFreq($options['changefreq']);
            } else {
                $changefreq = $this->defaults['changefreq'];
            }

            if (true === isset($options['priority'])) {
                $priority = $this->normalizePriority($options['priority']);
            } else {
                $priority = $this->defaults['priority'];
            }

            $entries[] = new Entry($url, $lastmod, $changefreq, $priority);
        }

        return $entries;
    }
}
