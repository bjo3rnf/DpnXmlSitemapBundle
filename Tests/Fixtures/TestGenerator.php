<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Tests\Fixtures;

use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;
use Dpn\XmlSitemapBundle\Sitemap\UrlSet;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TestGenerator implements GeneratorInterface
{
    private $numberOfUrls;

    public function __construct($numberOfUrls)
    {
        $this->numberOfUrls = $numberOfUrls;
    }

    public function generate()
    {
        $urls = new UrlSet();

        for ($i = 1; $i <= $this->numberOfUrls; $i++) {
            $urls->add(sprintf('http://localhost/foo/%s', $i));
        }

        return $urls;
    }
}
