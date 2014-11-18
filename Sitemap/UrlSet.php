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
final class UrlSet implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var Url[]
     */
    private $urls;

    /**
     * @param Url[] $urls
     */
    public function __construct(array $urls = array())
    {
        foreach ($urls as $url) {
            $this[] = $url;
        }
    }

    /**
     * Create a Url and add to set.
     *
     * @param string      $loc
     * @param null|string $lastMod
     * @param null|string $changeFreq
     * @param null|float  $priority
     */
    public function add($loc, $lastMod = null, $changeFreq = null, $priority = null)
    {
        $this[] = new Url($loc, $lastMod, $changeFreq, $priority);
    }

    /**
     * Combine UrlSet onto this one.
     *
     * @param UrlSet $urlSet
     */
    public function combine(UrlSet $urlSet)
    {
        foreach ($urlSet as $url) {
            $this[] = $url;
        }
    }

    /**
     * Extracts a new UrlSet slice of this UrlSet.
     *
     * @param int      $offset
     * @param int|null $length
     *
     * @return UrlSet
     */
    public function slice($offset, $length = null)
    {
        return new UrlSet(array_slice($this->urls, $offset, $length));
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->urls);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->urls);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->urls[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->urls[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Url) {
            throw new \InvalidArgumentException(sprintf('"%s" must be an instance of Dpn\XmlSitemapBundle\Sitemap\Url', gettype($value)));
        }

        if (is_null($offset)) {
            $this->urls[] = $value;

            return;
        }

        $this->urls[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->urls[$offset]);
    }
}
