<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 * For the full copyright and license information, please view the Resources/meta/LICENSE
 * file that was distributed with this source code.
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface GeneratorInterface
{
    /**
     * @return Url[]
     */
    public function generate();
}
