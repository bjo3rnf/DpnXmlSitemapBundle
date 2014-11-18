<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Tests\Sitemap\DependencyInjection;

use Dpn\XmlSitemapBundle\DependencyInjection\DpnXmlSitemapExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DpnXmlSitemapExtensionTest extends AbstractExtensionTestCase
{
    public function testDefault()
    {
        $this->load();
        $this->compile();

        $this->assertContainerBuilderHasService('dpn_xml_sitemap.controller');
        $this->assertContainerBuilderHasService('dpn_xml_sitemap.manager');
        $this->assertContainerBuilderHasService('dpn_xml_sitemap.route_option_generator');
        $this->assertContainerBuilderHasService('dpn_xml_sitemap.dump_command');

        $this->assertContainerBuilderHasParameter('dpn_xml_sitemap.defaults', array('priority' => null, 'changefreq' => null));
        $this->assertContainerBuilderHasParameter('dpn_xml_sitemap.http_cache', null);
        $this->assertContainerBuilderHasParameter('dpn_xml_sitemap.max_per_sitemap', 50000);
    }

    public function testCustomConfig()
    {
        $this->load(array(
                'http_cache' => 86400,
                'max_per_sitemap' => 100,
                'defaults' => array('priority' => 1, 'changefreq' => 'weekly'),
            ));
        $this->compile();

        $this->assertContainerBuilderHasService('dpn_xml_sitemap.controller');
        $this->assertContainerBuilderHasService('dpn_xml_sitemap.manager');
        $this->assertContainerBuilderHasService('dpn_xml_sitemap.route_option_generator');
        $this->assertContainerBuilderHasService('dpn_xml_sitemap.dump_command');

        $this->assertContainerBuilderHasParameter('dpn_xml_sitemap.defaults', array('priority' => 1, 'changefreq' => 'weekly'));
        $this->assertContainerBuilderHasParameter('dpn_xml_sitemap.http_cache', 86400);
        $this->assertContainerBuilderHasParameter('dpn_xml_sitemap.max_per_sitemap', 100);
    }

    protected function getContainerExtensions()
    {
        return array(new DpnXmlSitemapExtension());
    }
}
