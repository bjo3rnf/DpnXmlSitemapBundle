<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Manager;

use Symfony\Component\Routing\RouterInterface;

/**
 * Sitemap manager class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 */
class SitemapManager
{
    /**
     * @var Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * Class constructor
     *
     * @param RouterInterface $router
     * @param array $defaults
     */
    public function __construct(RouterInterface $router, array $defaults)
    {
        $this->router   = $router;
        $this->defaults = $defaults;
    }

    /**
     * Gets routes with sitemap options from router in an array keyed
     * by route name
     *
     * @return array
     */
    public function getRoutes()
    {
        $routes = array();
        $collection = $this->router->getRouteCollection();

        foreach ($collection->all() as $name => $route)
        {
            // Look for sitemap options in route
            if ($route->getOption('sitemap')
                && (true === $route->getOption('sitemap') || is_array($route->getOption('sitemap')))) {

                $routes[$name] = array();
                $options       = array();

                if (is_array($route->getOption('sitemap'))) {
                    $options = $route->getOption('sitemap');
                }

                // Make sure priority option is valid and set default of not provided
                if (isset($options['priority']) && $this->isValidPriority($options['priority'])) {
                    $routes[$name]['priority'] = $options['priority'];
                } else {
                    $routes[$name]['priority'] = $this->defaults['priority'];
                }

                // Set default for changefreq if not provided
                if (isset($options['changefreq']) && $this->isValidChangefreq($options['changefreq'])) {
                    $routes[$name]['changefreq'] = $options['changefreq'];
                } else {
                    $routes[$name]['changefreq'] = $this->defaults['changefreq'];
                }

                // Set lastmod option if provided and valid
                if (isset($options['lastmod']) && $this->isValidLastmod($options['lastmod'])) {
                    $routes[$name]['lastmod'] = $options['lastmod'];
                }
            }
        }

        return $routes;
    }

    /**
     * Checks if provided value is a valid priority
     *
     * @param float $priority
     * @return boolean
     */
    protected function isValidPriority($priority)
    {
        if (!is_float($priority)) {
            return false;
        }

        if ($priority < 0.1 || $priority > 1) {
            return false;
        }

        return true;
    }

    /**
     * Checks if provided string is a valid changefreq
     *
     * @param string $freq
     * @return boolean
     */
    protected function isValidChangefreq($freq)
    {
        return in_array($freq, array(
            'always',
            'hourly',
            'daily',
            'weekly',
            'monthly',
            'yearly',
            'never',
        ));
    }

    /**
     * Checks if provided string is a valid date
     *
     * @param string $lastmod
     * @return boolean
     */
    protected function isValidLastmod($lastmod)
    {
        if (preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}[\+|\-]\d{2}:\d{2}$/', $lastmod) === 1) {
            return true;
        }

        if (preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $lastmod) === 1) {
            return true;
        }

        return false;
    }
}