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
final class Url
{
    /**
     * @var string
     */
    protected $loc;

    /**
     * @var string|null
     */
    protected $lastMod = null;

    /**
     * @var string|null
     */
    protected $changeFreq = null;

    /**
     * @var float|null
     */
    protected $priority = null;

    /**
     * @param mixed $lastMod
     *
     * @return string|null
     */
    public static function normalizeLastMod($lastMod)
    {
        if ($lastMod instanceof \DateTime) {
            return $lastMod->format('Y-m-d');
        }

        if (1 === preg_match('/^\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}[\+|\-]\d{2}:\d{2}$/', $lastMod) ||
            1 === preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $lastMod)
        ) {
            return $lastMod;
        }

        return null;
    }

    /**
     * @param mixed $priority
     *
     * @return float|null
     */
    public static function normalizePriority($priority)
    {
        if (true === is_numeric($priority)) {
            $priority = round(floatval($priority), 1);
            if (0 <= $priority && 1 >= $priority) {
                return $priority;
            }
        }

        return null;
    }

    /**
     * @param mixed $changeFreq
     *
     * @return string|null
     */
    public static function normalizeChangeFreq($changeFreq)
    {
        $changeFreq = strtolower($changeFreq);

        if (in_array($changeFreq, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
            return $changeFreq;
        }

        return null;
    }

    /**
     * @param string      $loc
     * @param null|string $lastMod
     * @param null|string $changeFreq
     * @param null|float  $priority
     */
    public function __construct($loc, $lastMod = null, $changeFreq = null, $priority = null)
    {
        $components = parse_url($loc);

        if (!isset($components['scheme']) || !isset($components['host'])) {
            throw new \InvalidArgumentException(sprintf('The url "%s" is not absolute.', $loc));
        }

        $this->loc = htmlspecialchars($loc);

        $this->lastMod = self::normalizeLastMod($lastMod);
        $this->changeFreq = self::normalizeChangeFreq($changeFreq);
        $this->priority = self::normalizePriority($priority);
    }

    /**
     * @return string
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * @return null|string
     */
    public function getLastMod()
    {
        return $this->lastMod;
    }

    /**
     * @return null|string
     */
    public function getChangeFreq()
    {
        return $this->changeFreq;
    }

    /**
     * @return null|float
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
