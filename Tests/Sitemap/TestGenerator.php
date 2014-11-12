<?php

namespace Dpn\XmlSitemapBundle\Tests\Sitemap;

use Dpn\XmlSitemapBundle\Sitemap\Entry;
use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TestGenerator implements GeneratorInterface
{
    protected $numberOfEntries;

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
