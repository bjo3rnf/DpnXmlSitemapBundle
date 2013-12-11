<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Controller;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Dpn\XmlSitemapBundle\Manager\SitemapManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 */
class Controller
{
    protected $templating;

    protected $manager;

    protected $router;

    protected $httpCache;

    /**
     * @param \Dpn\XmlSitemapBundle\Manager\SitemapManager   $manager
     * @param \Symfony\Bundle\TwigBundle\TwigEngine          $templating
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     * @param $httpCache
     */
    public function __construct(SitemapManager $manager, TwigEngine $templating, Router $router, $httpCache)
    {
        $this->templating = $templating;
        $this->manager    = $manager;
        $this->router     = $router;
        $this->httpCache  = $httpCache;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function sitemapAction()
    {
        $total = $this->manager->getNumberOfSitemaps();

        return $this->renderSitemap($this->manager->getSitemapEntries());
    }

    public function sitemapNumberAction($number)
    {
        $total = $this->manager->getNumberOfSitemaps();

        if ($number > $total) {
            return new RedirectResponse($this->router->generate('dpn_xml_sitemap_number', array('number' => $total)));
        }

        return $this->renderSitemap($this->manager->getEntriesForSitemap($number));
    }

    public function sitemapIndexAction()
    {
        $response = new Response($this->templating->render('DpnXmlSitemapBundle::sitemap_index.xml.twig', array(
                'num_sitemaps' => $this->manager->getNumberOfSitemaps()
            )));

        if ($this->httpCache) {
            $response->setSharedMaxAge($this->httpCache);
        }

        return $response;
    }

    /**
     * @param  array                                      $entries
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderSitemap(array $entries)
    {
        $response = new Response($this->templating->render('DpnXmlSitemapBundle::sitemap.xml.twig', array(
                'entries' => $entries,
            )));

        if ($this->httpCache) {
            $response->setSharedMaxAge($this->httpCache);
        }

        return $response;
    }
}
