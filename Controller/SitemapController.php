<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Controller;

use Dpn\XmlSitemapBundle\Sitemap\SitemapManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Controller class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 */
class SitemapController
{
    /**
     * @var \Dpn\XmlSitemapBundle\Sitemap\SitemapManager
     */
    protected $manager;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var integer
     */
    protected $httpCache;

    /**
     * @param \Dpn\XmlSitemapBundle\Sitemap\SitemapManager $manager
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param integer $httpCache
     */
    public function __construct(SitemapManager $manager, RouterInterface $router, $httpCache = null)
    {
        $this->manager    = $manager;
        $this->router     = $router;
        $this->httpCache  = $httpCache;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sitemapAction()
    {
        if ($this->manager->getNumberOfSitemaps() > 1) {
            // redirect to /sitemap1.xml
            return new RedirectResponse($this->router->generate('dpn_xml_sitemap_number', array('number' => 1)));
        }

        return $this->createResponse($this->manager->renderSitemap());
    }

    /**
     * @param integer $number
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sitemapNumberAction($number)
    {
        $total = $this->manager->getNumberOfSitemaps();

        if (1 === $total) {
            // redirect to /sitemap.xml
            return new RedirectResponse($this->router->generate('dpn_xml_sitemap'));
        }

        if ($number > $total) {
            // redirect to /sitemap{n}.xml
            return new RedirectResponse($this->router->generate('dpn_xml_sitemap_number', array('number' => $total)));
        }

        return $this->createResponse($this->manager->renderSitemap($number));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sitemapIndexAction(Request $request)
    {
        return $this->createResponse($this->manager->renderSitemapIndex($request->getSchemeAndHttpHost()));
    }

    /**
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse($template)
    {
        $response = new Response($template);

        if (null !== $this->httpCache && 0 < $this->httpCache) {
            $response->setSharedMaxAge($this->httpCache);
        }

        return $response;
    }
}
