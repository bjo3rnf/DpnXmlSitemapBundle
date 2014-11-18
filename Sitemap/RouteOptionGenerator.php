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
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function generate()
    {
        $urls = new UrlSet();
        $collection = $this->router->getRouteCollection();

        foreach ($collection->all() as $name => $route) {
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

            $urls->add(
                $url,
                true === isset($options['lastmod']) ? $options['lastmod'] : null,
                true === isset($options['changefreq']) ? $options['changefreq'] : null,
                true === isset($options['priority']) ? $options['priority'] : null
            );
        }

        return $urls;
    }
}
