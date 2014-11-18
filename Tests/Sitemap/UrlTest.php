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

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructorProvider
     */
    public function testConstructor($url, $lastMod, $changeFreq, $priority, $expectedUrl, $expectedLastMod, $expectedChangeFreq, $expectedPriority)
    {
        $url = new Url($url, $lastMod, $changeFreq, $priority);

        $this->assertSame($expectedUrl, $url->getLoc());
        $this->assertSame($expectedLastMod, $url->getLastMod());
        $this->assertSame($expectedChangeFreq, $url->getChangeFreq());
        $this->assertSame($expectedPriority, $url->getPriority());
    }

    public function testInvalidUrl()
    {
        $this->setExpectedException('\InvalidArgumentException', 'The url "localhost" is not absolute.');

        $url = new Url('localhost');
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
            array('http://localhost/', null, null, -1, 'http://localhost/', null, null, null),
        );
    }
}
