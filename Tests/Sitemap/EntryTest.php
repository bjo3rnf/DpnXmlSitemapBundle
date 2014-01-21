<?php

namespace Dpn\XmlSitemapBundle\Tests\Sitemap;

use Dpn\XmlSitemapBundle\Sitemap\Entry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class EntryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructorProvider
     */
    public function testConstructor($url, $lastMod, $changeFreq, $priority, $expectedUrl, $expectedLastMod, $expectedChangeFreq, $expectedPriority)
    {
        $entry = new Entry($url, $lastMod, $changeFreq, $priority);

        $this->assertSame($expectedUrl, $entry->getUrl());
        $this->assertSame($expectedLastMod, $entry->getLastMod());
        $this->assertSame($expectedChangeFreq, $entry->getChangeFreq());
        $this->assertSame($expectedPriority, $entry->getPriority());
    }

    public function constructorProvider()
    {
        return array(
            array('/', null, null, null, '/', null, null, null),
            array('/', 'foo', 'bar', 'baz', '/', null, null, null),
            array('/', new \DateTime('Nov 1, 2013'), null, null, '/', '2013-11-01', null, null),
            array('/', '2013-11-01', null, null, '/', '2013-11-01', null, null),
            array('/', '2004-12-23T18:00:15+00:00', null, null, '/', '2004-12-23T18:00:15+00:00', null, null),
            array('/', null, 'monthly', null, '/', null, 'monthly', null),
            array('/', null, null, 0.6, '/', null, null, 0.6),
            array('/', null, null, '0.5', '/', null, null, 0.5),
            array('/', null, null, '3.0', '/', null, null, null),
            array('/', null, null, -1, '/', null, null, null)
        );
    }
}
