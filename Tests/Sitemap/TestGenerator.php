<?php

namespace Dpn\XmlSitemapBundle\Tests\Sitemap;

use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;
use Dpn\XmlSitemapBundle\Sitemap\Entry;

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
            $entry = new Entry();
            $entry->setUri(sprintf('/foo/%s', $i));

            $entries[] = $entry;
        }

        return $entries;
    }

}
