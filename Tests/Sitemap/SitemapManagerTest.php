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

use Dpn\XmlSitemapBundle\Sitemap\SitemapManager;
use Dpn\XmlSitemapBundle\Tests\Fixtures\TestGenerator;
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

    public function testGetUrlSet()
    {
        $manager = $this->getManager(10, 50000);

        $this->assertCount(10, $manager->getUrlSet());
    }

    public function testCountUrls()
    {
        $manager = $this->getManager(10, 50000);
        $this->assertEquals(10, $manager->countSitemapUrls());
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

    public function testGetUrlSetForSitemap()
    {
        $manager1 = $this->getManager(10, 50000);
        $this->assertCount(10, $manager1->getUrlSetForSitemap(1));

        $manager2 = $this->getManager(10, 5);
        $this->assertCount(5, $manager2->getUrlSetForSitemap(1));
        $this->assertCount(5, $manager2->getUrlSetForSitemap(2));

        $urls = $manager2->getUrlSetForSitemap(1);
        $this->assertEquals('http://localhost/foo/1', $urls[0]->getLoc());
        $this->assertEquals('http://localhost/foo/5', $urls[4]->getLoc());

        $urls = $manager2->getUrlSetForSitemap(2);
        $this->assertEquals('http://localhost/foo/6', $urls[0]->getLoc());
        $this->assertEquals('http://localhost/foo/10', $urls[4]->getLoc());

        $manager3 = $this->getManager(13, 5);
        $this->assertCount(5, $manager3->getUrlSetForSitemap(1));
        $this->assertCount(5, $manager3->getUrlSetForSitemap(2));
        $this->assertCount(3, $manager3->getUrlSetForSitemap(3));

        $urls = $manager3->getUrlSetForSitemap(1);
        $this->assertEquals('http://localhost/foo/1', $urls[0]->getLoc());
        $this->assertEquals('http://localhost/foo/5', $urls[4]->getLoc());

        $urls = $manager3->getUrlSetForSitemap(2);
        $this->assertEquals('http://localhost/foo/6', $urls[0]->getLoc());
        $this->assertEquals('http://localhost/foo/10', $urls[4]->getLoc());

        $urls = $manager3->getUrlSetForSitemap(3);
        $this->assertEquals('http://localhost/foo/11', $urls[0]->getLoc());
        $this->assertEquals('http://localhost/foo/13', $urls[2]->getLoc());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetUrlSetForSitemapNegative()
    {
        $this->getManager(10, 50000)->getUrlSetForSitemap(0);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetUrlSetForSitemapOutOfRange()
    {
        $this->getManager(10, 50000)->getUrlSetForSitemap(500);
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
     * @param $numberOfUrls
     * @param $maxPerSitemap
     *
     * @return SitemapManager
     */
    private function getManager($numberOfUrls, $maxPerSitemap)
    {
        $manager = new SitemapManager(array(), $maxPerSitemap, m::mock('Symfony\Component\Templating\EngineInterface'));
        $manager->addGenerator(new TestGenerator($numberOfUrls));

        return $manager;
    }
}
