# dreipunktnull XML sitemap bundle

This bundle generates XML sitemaps for your favourite search engine by extracting
sitemap information out of the site's routes.

## Installation

The DpnXmlSitemapBundle files should be downloaded to the
'vendor/bundles/Dpn/XmlSitemapBundle' directory.

You can accomplish this in several ways, depending on your personal preference.
The first method is the standard Symfony2 method.

### Using composer

Add DpnXmlSitemapBundle in your composer.json:

```json
{
    "require": {
        "dpn/xml-sitemap-bundle": "*"
    }
}
```

### Using the vendors script

Add the following lines to your `deps` file:

```
[DpnXmlSitemapBundle]
    git=git://github.com/dreipunktnull/XmlSitemapBundle.git
    target=/bundles/Dpn/XmlSitemapBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

### Using submodules

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add git://github.com/dreipunktnull/DpnXmlSitemapBundle.git vendor/bundles/Dpn/XmlSitemapBundle
$ git submodule update --init
```

### Configure the Autoloader (if not using composer)

Now you will need to add the `Dpn` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamspaces(array(
    // ...
    'Dpn' => __DIR__.'/../vendor/bundles',
));
```

### Enable the bundle

Finally, enable the bundle in the kernel:

```php
<?php
// app/appKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Dpn\XmlSitemapBundle\DpnXmlSitemapBundle(),
    );
}
```

### Register the routing in `app/config/routing.yml`

```yml
DpnXmlSitemapBundle:
    resource: "@DpnXmlSitemapBundle/Resources/config/routing.xml"
```

## Configuration

Add the following configuration to your `app/config/config.yml`:

    dpn_xml_sitemap: ~

The bundle uses a default priority of 0.5 and a changefreq of 'weekly'. You can
override these defaults in your `app/config/config.yml`:

    dpn_xml_sitemap:
        defaults:
            priority: 0.5
            changefreq: weekly

## Usage

To expose a route to the sitemap add the option `sitemap` to your route definition:

```xml
<route id="my_route_to_expose" pattern="/find/me/in/sitemap">
    <default key="_controller">MyBundle:Controller:action</default>
    <option key="sitemap">
        <priority>0.7</priority>
        <changefreq>always</changefreq>
    </option>
</route>
```

or annotation

```php
    /**
     * @Route("/find/me/in/sitemap", options={"sitemap" = {"priority" = 0.7}})
     */
```

or if you simply want to use the defaults:

```php
    /**
     * @Route("/find/me/in/sitemap", options={"sitemap" = true})
     */
```

The generated sitemap is then available under the url /sitemap.xml and the bundle will throw a not found exception
if it doesn't contain any routes.

### HTTP Caching

You can enable default http caching for the sitemap.xml route by setting the number of seconds in your config:

```yaml
dpn_xml_sitemap:
    http_cache: 3600
```

## Full Default Configuration

```yaml
dpn_xml_sitemap:
    http_cache:           ~
    defaults:
        priority:             0.5
        changefreq:           weekly
```

### Custom Generator

By default, the bundle looks at your routing config to find routes that you have the `sitemap` option set.  You can also
create your own sitemap entry generator:

1. Create a generator class that implements the `Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface` interface.  This class
must have a `generate()` method that returns an array of `Dpn\XmlSitemapBundle\Sitemap\Entry` objects.  Here is an 
example:

    ```php
    class MySitemapGenerator implements \Dpn\XmlSitemapBundle\Sitemap\GeneratorInterface
    {
        public function generate()
        {
            $entries = array();
            
            $entry = new \Dpn\XmlSitemapBundle\Sitemap\Entry();
            $entry->setUri('/foo');
            $entries[] = $entry;
            
            // add more entries - perhaps fetched from database
            
            return $entries;
        }
    }
    ```
    
2. Add this class as a service tagged with `dpn_xml_sitemap.generator`:

    ```yaml
    services:
        my.sitemap_generator:
            class: MySitemapGenerator
            tags: 
                - { name: dpn_xml_sitemap.generator }
    ```

## License

See `Resources/meta/LICENSE`.
