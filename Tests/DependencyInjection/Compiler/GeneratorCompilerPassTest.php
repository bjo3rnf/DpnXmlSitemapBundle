<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Tests\Sitemap\DependencyInjection\Compiler;

use Dpn\XmlSitemapBundle\DependencyInjection\Compiler\GeneratorCompilerPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GeneratorCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testProcess()
    {
        $this->setDefinition('dpn_xml_sitemap.manager', new Definition());

        $generator = new Definition();
        $generator->addTag('dpn_xml_sitemap.generator');

        $this->setDefinition('my_generator', $generator);
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'dpn_xml_sitemap.manager',
            'addGenerator',
            array(
                new Reference('my_generator'),
            )
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GeneratorCompilerPass());
    }
}
