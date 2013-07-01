<?php

namespace Dpn\XmlSitemapBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DpnXmlSitemapExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('dpn_xml_sitemap.defaults', $config['defaults']);
        $container->setParameter('dpn_xml_sitemap.http_cache', $config['http_cache']);
        $container->setParameter('dpn_xml_sitemap.max_per_sitemap', $config['max_per_sitemap']);
        $container->setParameter('dpn_xml_sitemap.check_format', $config['check_format']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
