<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SitemapControllerTest extends WebTestCase
{
    public function testSingleSitemapIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap_index.xml');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertLessThanOrEqual(86400, $response->getTtl());
        $this->assertContains('http://localhost/sitemap.xml', $response->getContent());
    }

    public function testMultiSitemapIndex()
    {
        $client = static::createClient(array('environment' => 'test1'));
        $client->request('GET', '/sitemap_index.xml');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNull($response->getTtl());
        $this->assertContains('http://localhost/sitemap1.xml', $response->getContent());
        $this->assertContains('http://localhost/sitemap2.xml', $response->getContent());
    }

    public function testSingleSitemap()
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertLessThanOrEqual(86400, $response->getTtl());
        $this->assertContains('http://localhost/bar', $response->getContent());
        $this->assertNotContains('http://localhost/foo', $response->getContent());
    }

    public function testMultiSitemap()
    {
        $client = static::createClient(array('environment' => 'test1'));
        $client->request('GET', '/sitemap1.xml');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNull($response->getTtl());
        $this->assertContains('http://localhost/foo/1', $response->getContent());
        $this->assertContains('http://localhost/foo/6', $response->getContent());
        $this->assertNotContains('http://localhost/bar', $response->getContent());

        $client->request('GET', '/sitemap2.xml');

        $response = $client->getResponse();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains('http://localhost/foo/7', $response->getContent());
        $this->assertContains('http://localhost/foo/10', $response->getContent());
        $this->assertContains('http://localhost/bar', $response->getContent());
        $this->assertNotContains('http://localhost/foo/2', $response->getContent());
        $this->assertNotContains('http://localhost/foo/6', $response->getContent());
    }

    public function testSingleSitemapRedirect()
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap1.xml');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/sitemap.xml', $client->getRequest()->getUri());
    }

    public function testMultiFirstSitemapRedirect()
    {
        $client = static::createClient(array('environment' => 'test1'));
        $client->request('GET', '/sitemap.xml');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/sitemap1.xml', $client->getRequest()->getUri());
    }

    public function testMultiOutOfRangeSitemapRedirect()
    {
        $client = static::createClient(array('environment' => 'test1'));
        $client->request('GET', '/sitemap3.xml');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('http://localhost/sitemap2.xml', $client->getRequest()->getUri());
    }
}
