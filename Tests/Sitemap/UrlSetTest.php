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

use Dpn\XmlSitemapBundle\Sitemap\Url;
use Dpn\XmlSitemapBundle\Sitemap\UrlSet;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class UrlSetTest extends \PHPUnit_Framework_TestCase
{
    public function testCombine()
    {
        $urlSet = $this->createUrlSet();
        $urlSet['foo'] = new Url('http://example.com');

        $this->assertCount(3, $urlSet);

        $urlSet->combine($this->createUrlSet());

        $this->assertCount(5, $urlSet);
    }

    public function testSlice()
    {
        $urlSet = $this->createUrlSet();
        $urlSet->add('http://example.com');
        $slice = $urlSet->slice(1);

        $this->assertCount(2, $slice);
        $this->assertSame('http://example.com', $slice[1]->getLoc());

        $slice = $urlSet->slice(1, 1);

        $this->assertCount(1, $slice);
        $this->assertSame('http://localhost2', $slice[0]->getLoc());
    }

    public function testCount()
    {
        $this->assertCount(2, $this->createUrlSet());
    }

    public function testArrayAccess()
    {
        $urlSet = $this->createUrlSet();
        $urlSet['foo'] = new Url('http://example.com');

        $this->assertCount(3, $urlSet);
        $this->assertTrue(isset($urlSet['foo']));
        $this->assertSame('http://example.com', $urlSet['foo']->getLoc());

        unset($urlSet['foo']);

        $this->assertCount(2, $urlSet);
    }

    public function testIterator()
    {
        foreach ($this->createUrlSet() as $url) {
            $this->assertInstanceOf('Dpn\XmlSitemapBundle\Sitemap\Url', $url);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalid()
    {
        $urlSet = $this->createUrlSet();

        $urlSet[] = new \stdClass();
    }

    /**
     * @return UrlSet
     */
    private function createUrlSet()
    {
        return new UrlSet(array(new Url('http://localhost1'), new Url('http://localhost2')));
    }
}
