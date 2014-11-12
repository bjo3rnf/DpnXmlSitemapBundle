<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Tests\Sitemap;

use Dpn\XmlSitemapBundle\Sitemap\Entry;
use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;
use Dpn\XmlSitemapBundle\Sitemap\SitemapManager;
use Mockery as m;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SitemapManagerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testGetEntries()
    {
        $manager = $this->getManager(10, 50000);

        $this->assertCount(10, $manager->getSitemapEntries());
    }

    public function testCountEntries()
    {
        $manager = $this->getManager(10, 50000);
        $this->assertEquals(10, $manager->countSitemapEntries());
    }

    public function testGetNumberOfSitemaps()
    {
        $manager1 = $this->getManager(10, 50000);
        $this->assertEquals(1, $manager1->getNumberOfSitemaps());

        $manager2 = $this->getManager(10, 5);
        $this->assertEquals(2, $manager2->getNumberOfSitemaps());

        $manager3 = $this->getManager(13, 5);
        $this->assertEquals(3, $manager3->getNumberOfSitemaps());
    }

    public function testGetEntriesForSitemap()
    {
        $manager1 = $this->getManager(10, 50000);
        $this->assertCount(10, $manager1->getEntriesForSitemap(1));

        $manager2 = $this->getManager(10, 5);
        $this->assertCount(5, $manager2->getEntriesForSitemap(1));
        $this->assertCount(5, $manager2->getEntriesForSitemap(2));

        $entries = $manager2->getEntriesForSitemap(1);
        $this->assertEquals('http://localhost/foo/1', $entries[0]->getUrl());
        $this->assertEquals('http://localhost/foo/5', $entries[4]->getUrl());

        $entries = $manager2->getEntriesForSitemap(2);
        $this->assertEquals('http://localhost/foo/6', $entries[0]->getUrl());
        $this->assertEquals('http://localhost/foo/10', $entries[4]->getUrl());

        $manager3 = $this->getManager(13, 5);
        $this->assertCount(5, $manager3->getEntriesForSitemap(1));
        $this->assertCount(5, $manager3->getEntriesForSitemap(2));
        $this->assertCount(3, $manager3->getEntriesForSitemap(3));

        $entries = $manager3->getEntriesForSitemap(1);
        $this->assertEquals('http://localhost/foo/1', $entries[0]->getUrl());
        $this->assertEquals('http://localhost/foo/5', $entries[4]->getUrl());

        $entries = $manager3->getEntriesForSitemap(2);
        $this->assertEquals('http://localhost/foo/6', $entries[0]->getUrl());
        $this->assertEquals('http://localhost/foo/10', $entries[4]->getUrl());

        $entries = $manager3->getEntriesForSitemap(3);
        $this->assertEquals('http://localhost/foo/11', $entries[0]->getUrl());
        $this->assertEquals('http://localhost/foo/13', $entries[2]->getUrl());

        $this->setExpectedException('\InvalidArgumentException');
        $manager3->getEntriesForSitemap(500);
    }

    public function testRenderSitemap()
    {
        $templating = m::mock('Symfony\Component\Templating\EngineInterface');
        $templating
            ->shouldReceive('render')
            ->twice()
            ->with('DpnXmlSitemapBundle::sitemap.xml.twig', m::type('array'))
            ->andReturn('rendered template');

        $manager = new SitemapManager(array(), 1, $templating);
        $manager->addGenerator(new TestGenerator(2));

        $this->assertSame('rendered template', $manager->renderSitemap());
        $this->assertSame('rendered template', $manager->renderSitemap(2));
    }

    public function testRenderSitemapIndex()
    {
        $templating = m::mock('Symfony\Component\Templating\EngineInterface');
        $templating
            ->shouldReceive('render')
            ->once()
            ->with('DpnXmlSitemapBundle::sitemap_index.xml.twig', array('num_sitemaps' => 2, 'host' => 'http://localhost'))
            ->andReturn('rendered template');

        $manager = new SitemapManager(array(), 1, $templating);
        $manager->addGenerator(new TestGenerator(2));

        $this->assertSame('rendered template', $manager->renderSitemapIndex('http://localhost'));
    }

    /**
     * @param $numberOfEntries
     * @param $maxPerSitemap
     *
     * @return SitemapManager
     */
    private function getManager($numberOfEntries, $maxPerSitemap)
    {
        $manager = new SitemapManager(array(), $maxPerSitemap, m::mock('Symfony\Component\Templating\EngineInterface'));
        $manager->addGenerator(new TestGenerator($numberOfEntries));

        return $manager;
    }
}

class TestGenerator implements GeneratorInterface
{
    protected $numberOfEntries;

    public function __construct($numberOfEntries)
    {
        $this->numberOfEntries = $numberOfEntries;
    }

    public function generate()
    {
        $entries = array();

        for ($i = 1; $i <= $this->numberOfEntries; $i++) {
            $entry = new Entry(sprintf('http://localhost/foo/%s', $i));
            $entries[] = $entry;
        }

        return $entries;
    }
}
