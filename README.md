# DpnXmlSitemapBundle

[![Build Status](http://img.shields.io/travis/bjo3rnf/DpnXmlSitemapBundle.svg?style=flat-square)](https://travis-ci.org/bjo3rnf/DpnXmlSitemapBundle)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/bjo3rnf/DpnXmlSitemapBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/bjo3rnf/DpnXmlSitemapBundle/)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/bjo3rnf/DpnXmlSitemapBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/bjo3rnf/DpnXmlSitemapBundle/)
[![Latest Stable Version](http://img.shields.io/packagist/v/dpn/xml-sitemap-bundle.svg?style=flat-square)](https://packagist.org/packages/dpn/xml-sitemap-bundle)
[![License](http://img.shields.io/packagist/l/dpn/xml-sitemap-bundle.svg?style=flat-square)](https://packagist.org/packages/dpn/xml-sitemap-bundle)

This bundle generates XML sitemaps for your favourite search engine by extracting
sitemap information out of your application's routes. Additionally, you can create
your own generators to provide URLs. The sitemap(s) generated follow the
[sitemap protocol](http://www.sitemaps.org/protocol.html).

## Installation

1. Install with composer:

        composer require dpn/xml-sitemap-bundle

2. Enable the bundle in the kernel:

    ```php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Dpn\XmlSitemapBundle\DpnXmlSitemapBundle(),
        );
    }
    ```

3. Register the routing in `app/config/routing.yml`

    ```yaml
    DpnXmlSitemapBundle:
        resource: "@DpnXmlSitemapBundle/Resources/config/routing.xml"
    ```

## Usage

### Exposing Routes

To expose a route to the sitemap add the option `sitemap` to your route definition:

```yaml
blog_index:
    path:      /blog
    defaults:  { _controller: AppBundle:Blog:index }
    sitemap: true
```

This will expose this route to your sitemap using the default options from your config. To control the options
for this sitemap entry, add them to the `sitemap` option:

```yaml
blog_index:
    path:      /blog
    defaults:  { _controller: AppBundle:Blog:index }
    sitemap:
        priority: 0.7
        changefreq: hourly
```

**NOTE**: Only routes without parameters may be exposed in this way. For routes with parameters, you must create
a custom generator (see below).

### Custom Generator

For more complex routes that have parameters, you must create a custom generator.

1. Create a generator class that implements the `Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface` interface.
This class must have a `generate()` method that returns an array of `Dpn\XmlSitemapBundle\Sitemap\Entry` objects.

    ```php
    use Dpn\XmlSitemapBundle\Sitemap\Entry;
    use Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface;

    class MySitemapGenerator implements GeneratorInterface
    {
        public function generate()
        {
            $entries = array();

            $entries[] = new Entry('http://example.com/foobar'); // must be absolute URL

            // add more entries - perhaps fetched from database

            return $entries;
        }
    }
    ```

2. Add this class as a service tagged with `dpn_xml_sitemap.generator`:

    ```yaml
    services:
        my_sitemap_generator:
            class: MySitemapGenerator
            tags:
                - { name: dpn_xml_sitemap.generator }
    ```

### Sitemap Index

According to [sitemaps.org](http://www.sitemaps.org/protocol.html#index) the maximum number of entries a `sitemap.xml`
may have is 50,000.  When the number of sitemap entries exceeds this, the entries are split across multiple sitemaps
(ie `/sitemap1.xml`,`/sitemap2.xml`...`/sitemapN.xml`).

A sitemap index is accessible at `/sitemap_index.xml`.

The maximum entries per sitemap is configurable:

```yaml
dpn_xml_sitemap:
    max_per_sitemap: 50000 #default
```

### HTTP Caching

You can enable http caching for the `sitemap(n).xml`/`sitemap_index.xml` URI's by setting the number of
seconds in your config:

```yaml
dpn_xml_sitemap:
    http_cache: 3600
```

## Full Default Configuration

The following is the default configuration for this bundle:

```yaml
dpn_xml_sitemap:

    # The length of time (in seconds) to cache the sitemap/sitemap_index xml\'s (a reverse proxy is required)
    http_cache:      ~

    # The number of url entries in a single sitemap
    max_per_sitemap: 50000

    # The default options for sitemap URL entries to be used if not overridden
    defaults:

        # Value between 0.0 and 1.0 or null for sitemap protocol default
        priority:   ~

        # One of [always, hourly, daily, weekly, monthly, yearly, never] or null for sitemap protocol default
        changefreq: ~
```

## License

See `Resources/meta/LICENSE`.
