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

use Dpn\XmlSitemapBundle\DpnXmlSitemapBundle;
use Mockery as m;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DpnXmlSitemapBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testCompilerPassesAreRegistered()
    {
        $container = m::mock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->shouldReceive('addCompilerPass')
            ->once()
            ->with(m::type('Dpn\XmlSitemapBundle\DependencyInjection\Compiler\GeneratorCompilerPass'))
        ;
        $bundle = new DpnXmlSitemapBundle();
        $bundle->build($container);
    }

    public function tearDown()
    {
        m::close();
    }
}
