<?php

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
                ->scalarNode('http_cache')->defaultNull()->end()
                ->scalarNode('max_per_sitemap')->defaultValue(50000)->end()
                ->arrayNode('defaults')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('priority')->defaultNull()->end()
                        ->scalarNode('changefreq')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
