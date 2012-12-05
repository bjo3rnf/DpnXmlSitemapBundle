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

/**
 * Controller class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 */
class Controller
{
    protected $templating;

    protected $manager;

    protected $httpCache;

    /**
     * @param \Dpn\XmlSitemapBundle\Manager\SitemapManager $manager
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @param $httpCache
     */
    public function __construct(SitemapManager $manager, TwigEngine $templating, $httpCache)
    {
        $this->templating = $templating;
        $this->manager    = $manager;
        $this->httpCache  = $httpCache;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function sitemapAction()
    {
        $entries = $this->manager->getSitemapEntries();

        // Redirect to 404 error page if no routes are added to sitemap
        if (!$entries) {
            throw new NotFoundHttpException();
        }

        $response = new Response($this->templating->render('DpnXmlSitemapBundle::sitemap.xml.twig', array(
                'entries' => $entries,
            )));

        if ($this->httpCache) {
            $response->setSharedMaxAge($this->httpCache);
        }

        return $response;
    }
}