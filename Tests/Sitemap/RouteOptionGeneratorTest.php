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

use Dpn\XmlSitemapBundle\Sitemap\RouteOptionGenerator;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RouteOptionGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $router = new Router(
            new ClosureLoader(),
            function () {
                $routeCollection = new RouteCollection();
                $routeCollection->add('foo', new Route('/foo'));
                $routeCollection->add('bar', new Route('/bar', array(), array(), array('sitemap' => true)));
                $routeCollection->add('baz', new Route('/baz', array(), array(), array('sitemap' => array('changefreq' => 'weekly'))));

                return $routeCollection;
            }
        );

        $generator = new RouteOptionGenerator($router);
        $urls = $generator->generate();

        $this->assertCount(2, $urls);
        $url = $urls[0];

        $this->assertInstanceOf('Dpn\XmlSitemapBundle\Sitemap\Url', $url);
        $this->assertSame('http://localhost/bar', $url->getLoc());

        $url = $urls[1];

        $this->assertSame('weekly', $url->getChangeFreq());
    }

    public function testMissingParameters()
    {
        $router = new Router(
            new ClosureLoader(),
            function () {
                $routeCollection = new RouteCollection();
                $routeCollection->add('foo', new Route('/foo/{bar}', array(), array(), array('sitemap' => true)));

                return $routeCollection;
            }
        );

        $this->setExpectedException('\InvalidArgumentException', 'The route "foo" cannot have the sitemap option because it requires parameters');
        $generator = new RouteOptionGenerator($router);
        $generator->generate();
    }
}
