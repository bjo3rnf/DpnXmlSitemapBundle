<?php

namespace Dpn\XmlSitemapBundle\Tests\Sitemap;

use Dpn\XmlSitemapBundle\Manager\SitemapManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
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
        $this->assertEquals('/foo/1', $entries[0]->getUri());
        $this->assertEquals('/foo/5', $entries[4]->getUri());

        $entries = $manager2->getEntriesForSitemap(2);
        $this->assertEquals('/foo/6', $entries[0]->getUri());
        $this->assertEquals('/foo/10', $entries[4]->getUri());

        $manager3 = $this->getManager(13, 5);
        $this->assertCount(5, $manager3->getEntriesForSitemap(1));
        $this->assertCount(5, $manager3->getEntriesForSitemap(2));
        $this->assertCount(3, $manager3->getEntriesForSitemap(3));

        $entries = $manager3->getEntriesForSitemap(1);
        $this->assertEquals('/foo/1', $entries[0]->getUri());
        $this->assertEquals('/foo/5', $entries[4]->getUri());

        $entries = $manager3->getEntriesForSitemap(2);
        $this->assertEquals('/foo/6', $entries[0]->getUri());
        $this->assertEquals('/foo/10', $entries[4]->getUri());

        $entries = $manager3->getEntriesForSitemap(3);
        $this->assertEquals('/foo/11', $entries[0]->getUri());
        $this->assertEquals('/foo/13', $entries[2]->getUri());

        $this->setExpectedException('\InvalidArgumentException');
        $manager3->getEntriesForSitemap(500);
    }

    /**
     * @param $numberOfEntries
     * @param $maxPerSitemap
     *
     * @return \Dpn\XmlSitemapBundle\Manager\SitemapManager
     */
    private function getManager($numberOfEntries, $maxPerSitemap)
    {
        $defaults = array(
            'priority' => '0.5',
            'changefreq' => 'weekly'
        );

        $generator = new TestGenerator($numberOfEntries);
        $manager = new SitemapManager($defaults, $maxPerSitemap);
        $manager->addGenerator($generator);

        return $manager;
    }
}
