<?php

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
        $entry = $urls[0];

        $this->assertInstanceOf('Dpn\XmlSitemapBundle\Sitemap\Entry', $entry);
        $this->assertSame('http://localhost/bar', $entry->getUrl());

        $entry = $urls[1];

        $this->assertSame('weekly', $entry->getChangeFreq());
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
