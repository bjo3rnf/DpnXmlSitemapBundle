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

/**
 * Controller class
 *
 * @author Björn Fromme <mail@bjo3rn.com>
 */
class Controller
{
    /**
     * @var Symfony\Bundle\TwigBundle\TwigEngine;
     */
    protected $templating;

    /**
     * @var Dpn\XmlSitemapBundle\Manager\SitemapManager
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param TwigEngine $templating
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
        $routes = $this->manager->getRoutes();

        // Redirect to 404 error page if no routes are added to sitemap
        if (!$routes) {
            throw new NotFoundHttpException();
        }

        // Render sitemap
        return $this->templating->renderResponse('DpnXmlSitemapBundle::sitemap.xml.twig', array(
            'routes' => $routes,
        ));
    }
}