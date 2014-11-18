<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Tests\Command;

use Dpn\XmlSitemapBundle\Command\DumpCommand;
use Dpn\XmlSitemapBundle\Sitemap\SitemapManager;
use Dpn\XmlSitemapBundle\Tests\Fixtures\TestGenerator;
use Mockery as m;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Routing\RequestContext;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DumpCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleSitemapsExecute()
    {
        $templating = m::mock('Symfony\Component\Templating\EngineInterface');
        $templating->shouldReceive('render')
            ->times(2);

        $router = m::mock('Symfony\Component\Routing\RouterInterface');
        $router->shouldReceive('getContext')
            ->once()
            ->andReturn(new RequestContext());

        $manager = new SitemapManager(array(), 50000, $templating);
        $manager->addGenerator(new TestGenerator(10));

        $application = new Application();
        $application->add(new DumpCommand($manager, $router, sys_get_temp_dir()));

        $command = $application->find('dpn:xml-sitemap:dump');
        $commandTester = new CommandTester($command);

        $this->assertFileNotExists($this->getSitemapIndexFile());
        $this->assertFileNotExists($this->getSitemapFile());
        $this->assertFileNotExists($this->getSitemap1File());
        $this->assertFileNotExists($this->getSitemap2File());

        $commandTester->execute(array(
            'command'   => $command->getName(),
            'host'      => 'http://localhost',
            '--target'  => sys_get_temp_dir(),
        ));

        $this->assertFileExists($this->getSitemapIndexFile());
        $this->assertFileExists($this->getSitemapFile());
        $this->assertFileNotExists($this->getSitemap1File());
        $this->assertFileNotExists($this->getSitemap2File());

        $this->assertContains(sprintf('[file+] %s', $this->getSitemapIndexFile()), $commandTester->getDisplay());
        $this->assertContains(sprintf('[file+] %s', $this->getSitemapFile()), $commandTester->getDisplay());
    }

    public function testMultipleSitemapsExecute()
    {
        $templating = m::mock('Symfony\Component\Templating\EngineInterface');
        $templating->shouldReceive('render')
            ->times(3);

        $router = m::mock('Symfony\Component\Routing\RouterInterface');
        $router->shouldReceive('getContext')
            ->once()
            ->andReturn(new RequestContext());

        $manager = new SitemapManager(array(), 5, $templating);
        $manager->addGenerator(new TestGenerator(10));

        $application = new Application();
        $application->add(new DumpCommand($manager, $router, sys_get_temp_dir()));

        $command = $application->find('dpn:xml-sitemap:dump');
        $commandTester = new CommandTester($command);

        $this->assertFileNotExists($this->getSitemapIndexFile());
        $this->assertFileNotExists($this->getSitemapFile());
        $this->assertFileNotExists($this->getSitemap1File());
        $this->assertFileNotExists($this->getSitemap2File());

        $commandTester->execute(array(
            'command'   => $command->getName(),
            'host'      => 'http://localhost',
            '--target'  => sys_get_temp_dir(),
        ));

        $this->assertFileExists($this->getSitemapIndexFile());
        $this->assertFileNotExists($this->getSitemapFile());
        $this->assertFileExists($this->getSitemap1File());
        $this->assertFileExists($this->getSitemap2File());

        $this->assertContains(sprintf('[file+] %s', $this->getSitemapIndexFile()), $commandTester->getDisplay());
        $this->assertContains(sprintf('[file+] %s', $this->getSitemap1File()), $commandTester->getDisplay());
        $this->assertContains(sprintf('[file+] %s', $this->getSitemap2File()), $commandTester->getDisplay());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The directory "foo" does not exist.
     */
    public function testInvalidDir()
    {
        $templating = m::mock('Symfony\Component\Templating\EngineInterface');
        $router = m::mock('Symfony\Component\Routing\RouterInterface');

        $manager = new SitemapManager(array(), 5, $templating);

        $application = new Application();
        $application->add(new DumpCommand($manager, $router, sys_get_temp_dir()));

        $command = $application->find('dpn:xml-sitemap:dump');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'   => $command->getName(),
            'host'      => 'http://localhost',
            '--target'  => 'foo',
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The host "foo" is invalid.
     */
    public function testInvalidHost()
    {
        $templating = m::mock('Symfony\Component\Templating\EngineInterface');
        $router = m::mock('Symfony\Component\Routing\RouterInterface');

        $manager = new SitemapManager(array(), 5, $templating);

        $application = new Application();
        $application->add(new DumpCommand($manager, $router, sys_get_temp_dir()));

        $command = $application->find('dpn:xml-sitemap:dump');
        $commandTester = new CommandTester($command);

        $commandTester->execute(array(
            'command'   => $command->getName(),
            'host'      => 'foo',
            '--target'  => sys_get_temp_dir(),
        ));
    }

    public function setUp()
    {
        $files = array($this->getSitemapIndexFile(), $this->getSitemapFile(), $this->getSitemap1File(), $this->getSitemap2File());

        foreach ($files as $file) {
            if (!file_exists($file)) {
                continue;
            }

            unlink($file);
        }
    }

    public function tearDown()
    {
        m::close();
    }

    private function getSitemapIndexFile()
    {
        return sys_get_temp_dir().'/sitemap_index.xml';
    }

    private function getSitemapFile()
    {
        return sys_get_temp_dir().'/sitemap.xml';
    }

    private function getSitemap1File()
    {
        return sys_get_temp_dir().'/sitemap1.xml';
    }

    private function getSitemap2File()
    {
        return sys_get_temp_dir().'/sitemap2.xml';
    }
}
