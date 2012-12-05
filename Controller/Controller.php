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

    /**
     * @param \Dpn\XmlSitemapBundle\Manager\SitemapManager $manager
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     */
    public function __construct(SitemapManager $manager, TwigEngine $templating)
    {
        $this->templating = $templating;
        $this->manager    = $manager;
    }

    /**
     * Sitemap action
     *
     * @param string $_format
     * @return Response
     */
    public function sitemapAction($_format)
    {
        $entries = $this->manager->getSitemapEntries();

        // Redirect to 404 error page if no routes are added to sitemap
        if (!$entries) {
            throw new NotFoundHttpException();
        }

        // Render sitemap
        return $this->templating->renderResponse('DpnXmlSitemapBundle::sitemap.xml.twig', array(
            'entries' => $entries,
        ));
    }
}