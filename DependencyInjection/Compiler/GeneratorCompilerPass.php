<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GeneratorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('dpn_xml_sitemap.manager')) {
            return;
        }

        $definition = $container->getDefinition(
            'dpn_xml_sitemap.manager'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'dpn_xml_sitemap.generator'
        );

        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addGenerator', array(new Reference($id)));
        }
    }

}
