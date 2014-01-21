<?php

/**
 * This file is part of the DpnXmlSitemapBundle package.
 *
 * (c) Björn Fromme <mail@bjo3rn.com>
 *
 */

namespace Dpn\XmlSitemapBundle\Sitemap;

/**
 * @author Björn Fromme <mail@bjo3rn.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
	/**
	 * @var array
	 */
	protected $defaults;

	/**
	 * @param array $defaults
	 */
	public function setDefaults(array $defaults)
	{
		$this->defaults = $defaults;
	}

    /**
     * @return \Dpn\XmlSitemapBundle\Sitemap\Entry[]
     */
    abstract public function generate()
    {
    	return array();
    }

    /**
     * @param mixed $lastMod
     * @return string|null
     */
    public function normalizeLastMod($lastMod)
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
     * @return float|null
     */
    public function normalizePriority($priority)
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
     * @return string|null
     */
    public function normalizeChangeFreq($changeFreq)
    {
        $changeFreq = strtolower($changeFreq);

        if (in_array($changeFreq, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) {
            return $changeFreq;
        }

        return null;
    }

}