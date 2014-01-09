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
class RouteOptionGenerator implements GeneratorInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var boolean
     */
    protected $checkFormat;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param boolean $checkFormat
     */
    public function __construct(RouterInterface $router, $checkFormat)
    {
        $this->router = $router;
        $this->checkFormat = $checkFormat;
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

            if (true === $option || true === is_array($option)) {
                $options = array();

                if (true === is_array($option)) {
                    $options = $option;
                }

                $entry = new Entry();

                try {
                    $uri = $this->router->generate($name);
                } catch (MissingMandatoryParametersException $e) {
                    throw new \InvalidArgumentException(sprintf('The route "%s" cannot have the sitemap option because it requires parameters', $name));
                }

                $checkFormat = $this->checkFormat;
                if (true === isset($options['check_format'])) {
                    $checkFormat = $options['check_format'];
                }

                $entry->setUri($uri, $checkFormat);

                if (true === isset($options['priority'])) {
                    $entry->setPriority($options['priority']);
                }

                if (true === isset($options['changefreq'])) {
                    $entry->setChangeFreq($options['changefreq']);
                }

                if (true === isset($options['lastmod'])) {
                    $entry->setLastMod($options['lastmod']);
                }

                $entries[] = $entry;
            }
        }

        return $entries;
    }
}
