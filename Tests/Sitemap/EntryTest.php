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

    public function testInvalidUrl()
    {
        $this->setExpectedException('\InvalidArgumentException', 'The url "localhost" is not absolute.');

        $entry = new Entry('localhost');
    }

    public function constructorProvider()
    {
        return array(
            array('http://localhost/', null, null, null, 'http://localhost/', null, null, null),
            array('http://localhost/index.php?foo=bar&bar=baz', null, null, null, 'http://localhost/index.php?foo=bar&amp;bar=baz', null, null, null),
            array('http://localhost/', 'foo', 'bar', 'baz', 'http://localhost/', null, null, null),
            array('http://localhost/', new \DateTime('Nov 1, 2013'), null, null, 'http://localhost/', '2013-11-01', null, null),
            array('http://localhost/', '2013-11-01', null, null, 'http://localhost/', '2013-11-01', null, null),
            array('http://localhost/', '2004-12-23T18:00:15+00:00', null, null, 'http://localhost/', '2004-12-23T18:00:15+00:00', null, null),
            array('http://localhost/', null, 'monthly', null, 'http://localhost/', null, 'monthly', null),
            array('http://localhost/', null, null, 0.6, 'http://localhost/', null, null, 0.6),
            array('http://localhost/', null, null, '0.5', 'http://localhost/', null, null, 0.5),
            array('http://localhost/', null, null, '3.0', 'http://localhost/', null, null, null),
            array('http://localhost/', null, null, -1, 'http://localhost/', null, null, null)
        );
    }
}
