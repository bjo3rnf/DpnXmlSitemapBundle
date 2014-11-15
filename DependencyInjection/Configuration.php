<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dpn_xml_sitemap');

        $rootNode
            ->children()
                ->scalarNode('http_cache')
                    ->defaultNull()
                    ->info('The length of time (in seconds) to cache the sitemap/sitemap_index xml\'s (a reverse proxy is required)')
                ->end()
                ->scalarNode('max_per_sitemap')
                    ->defaultValue(50000)
                    ->info('The number of url entries in a single sitemap')
                ->end()
                ->arrayNode('defaults')
                    ->info('The default options for sitemap URL entries to be used if not overridden')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('priority')
                            ->defaultNull()
                            ->info('Value between 0.0 and 1.0 or null for sitemap protocol default')
                        ->end()
                        ->scalarNode('changefreq')
                            ->defaultNull()
                            ->info('One of [always, hourly, daily, weekly, monthly, yearly, never] or null for sitemap protocol default')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
