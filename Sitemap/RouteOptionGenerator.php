<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RouteOptionGenerator implements GeneratorInterface
{
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array|Entry[]
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $entries = array();
        $collection = $this->router->getRouteCollection();

        foreach ($collection->all() as $name => $route) {
            /** @var $route \Symfony\Component\Routing\Route */
            $option = $route->getOption('sitemap');

            if ($option && (true === $option) || is_array($option)) {
                $options = array();

                if (is_array($option)) {
                    $options = $option;
                }

                $entry = new Entry();

                try {
                    $uri = $this->router->generate($name);
                } catch (MissingMandatoryParametersException $e) {
                    throw new \InvalidArgumentException(sprintf('The route "%s" cannot have the sitemap option because it requires parameters', $name));
                }

                $entry->setUri($uri);

                if (isset($options['priority'])) {
                    $entry->setPriority($options['priority']);
                }

                if (isset($options['changefreq'])) {
                    $entry->setChangeFreq($options['changefreq']);
                }

                if (isset($options['lastmod'])) {
                    $entry->setLastMod($options['lastmod']);
                }

                $entries[] = $entry;
            }
        }

        return $entries;
    }
}
