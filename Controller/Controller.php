<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Controller;

use Dpn\XmlSitemapBundle\Manager\SitemapManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Controller class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 */
class Controller
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Dpn\XmlSitemapBundle\Manager\SitemapManager
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
     * @param \Dpn\XmlSitemapBundle\Manager\SitemapManager $manager
     * @param \Symfony\Component\Templating\EngineInterface $templating
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param integer $httpCache
     */
    public function __construct(SitemapManager $manager, EngineInterface $templating, RouterInterface $router, $httpCache = null)
    {
        $this->templating = $templating;
        $this->manager    = $manager;
        $this->router     = $router;
        $this->httpCache  = $httpCache;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sitemapAction()
    {
        return $this->renderSitemap($this->manager->getSitemapEntries());
    }

    /**
     * @param integer $number
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function sitemapNumberAction($number)
    {
        $total = $this->manager->getNumberOfSitemaps();

        if ($number > $total) {
            return new RedirectResponse($this->router->generate('dpn_xml_sitemap_number', array('number' => $total)));
        }

        return $this->renderSitemap($this->manager->getEntriesForSitemap($number));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sitemapIndexAction()
    {
        $response = new Response($this->templating->render('DpnXmlSitemapBundle::sitemap_index.xml.twig', array(
            'num_sitemaps' => $this->manager->getNumberOfSitemaps()
        )));

        if (null !== $this->httpCache && 0 < $this->httpCache) {
            $response->setSharedMaxAge($this->httpCache);
        }

        return $response;
    }

    /**
     * @param  array $entries
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderSitemap(array $entries)
    {
        $response = new Response($this->templating->render('DpnXmlSitemapBundle::sitemap.xml.twig', array(
            'entries' => $entries,
        )));

        if (null !== $this->httpCache && 0 < $this->httpCache) {
            $response->setSharedMaxAge($this->httpCache);
        }

        return $response;
    }
}
