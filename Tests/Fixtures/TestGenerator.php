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

use Dpn\XmlSitemapBundle\Sitemap\Entry;
use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TestGenerator implements GeneratorInterface
{
    private $numberOfEntries;

    public function __construct($numberOfEntries)
    {
        $this->numberOfEntries = $numberOfEntries;
    }

    public function generate()
    {
        $entries = array();

        for ($i = 1; $i <= $this->numberOfEntries; $i++) {
            $entry = new Entry(sprintf('http://localhost/foo/%s', $i));
            $entries[] = $entry;
        }

        return $entries;
    }
}
