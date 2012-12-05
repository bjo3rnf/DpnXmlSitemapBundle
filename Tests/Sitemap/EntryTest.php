<?php

namespace Dpn\XmlSitemapBundle\Tests\Sitemap;

use Dpn\XmlSitemapBundle\Sitemap\Entry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class EntryTest extends \PHPUnit_Framework_TestCase
{
    protected $defaults = array(
        'priority' => '0.5',
        'changefreq' => 'weekly'
    );

    public function testSetDefaults()
    {
        $entry = new Entry();

        $entry->setDefaults($this->defaults);
        $this->assertEquals('weekly', $entry->getChangeFreq());
        $this->assertEquals(0.5, $entry->getPriority());

        $entry = new Entry();

        $entry->setChangeFreq('yearly');
        $entry->setDefaults($this->defaults);
        $this->assertEquals('yearly', $entry->getChangeFreq());
        $this->assertEquals(0.5, $entry->getPriority());

        $entry = new Entry();

        $entry->setPriority(0.1);
        $entry->setDefaults($this->defaults);
        $this->assertEquals('weekly', $entry->getChangeFreq());
        $this->assertEquals(0.1, $entry->getPriority());
    }

    public function testBadDefaultsPriority()
    {
        $defaults = array('priority' => 'foo');
        $entry = new Entry();

        $this->setExpectedException('\InvalidArgumentException');
        $entry->setDefaults($defaults);
    }

    public function testBadDefaultsChangeFreq()
    {
        $defaults = array('changefreq' => 'foo');
        $entry = new Entry();

        $this->setExpectedException('\InvalidArgumentException');
        $entry->setDefaults($defaults);
    }

    public function testSetLastMod()
    {
        $entry = new Entry();

        $entry->setLastMod('foo');
        $this->assertNull($entry->getLastMod());

        $entry->setLastMod('2012-12-05');
        $this->assertEquals('2012-12-05', $entry->getLastMod());

        $entry->setLastMod(new \DateTime('2001-01-02'));
        $this->assertEquals('2001-01-02', $entry->getLastMod());
    }

    public function testSetUri()
    {
        $entry = new Entry();

        $entry->setUri('/foo');
        $this->assertEquals('/foo', $entry->getUri());

        $entry->setUri('foo');
        $this->assertEquals('/foo', $entry->getUri());
    }

    public function testSetChangeFreq()
    {
        $entry = new Entry();

        $entry->setChangeFreq('foo');
        $this->assertNull($entry->getChangeFreq());

        $entry->setChangeFreq('weekly');
        $this->assertEquals('weekly', $entry->getChangeFreq());
    }

    public function testSetPriority()
    {
        $entry = new Entry();

        $entry->setPriority(2.0);
        $this->assertNull($entry->getPriority());

        $entry->setPriority(-2.1);
        $this->assertNull($entry->getPriority());

        $entry->setPriority('foo');
        $this->assertNull($entry->getPriority());

        $entry->setPriority(0.5);
        $this->assertEquals(0.5, $entry->getPriority());

        $entry->setPriority('0.5');
        $this->assertEquals(0.5, $entry->getPriority());
    }
}
