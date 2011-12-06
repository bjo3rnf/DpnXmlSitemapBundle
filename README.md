dreipunktnull XML sitemap bundle
================================

This bundle generates XML sitemaps for your favourite search engine by extracting
sitemap information out of the site's routes.

#### Installation

### Step 1: Download the DpnXmlSitemapBundle

The DpnXmlSitemapBundle files should be downloaded to the
'vendor/bundles/Dpn/XmlSitemapBundle' directory.

You can accomplish this several ways, depending on your personal preference.
The first method is the standard Symfony2 method.

***Using the vendors script***

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

***Using submodules***

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add git://github.com/dreipunktnull/XmlSitemapBundle.git vendor/bundles/Dpn/XmlSitemapBundle
$ git submodule update --init
```

### Step 2: Configure the Autoloader

Now you will need to add the `Dpn` namespace to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamspaces(array(
    // ...
    'Dpn' => __DIR__.'/../vendor/bundles',
));
```
### Step 3: Enable the bundle

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

### Step 4: Register the routing in `app/config/routing.yml`

```yml
DpnXmlSitemapBundle:
    resource: "@DpnXmlSitemapBundle/Resources/config/routing.xml"
```

#### Configuration

Add the following configuration to your `app/config/config.yml`:

    dpn_xml_sitemap: ~

The bundle uses a default priority of 0.5 and a changefreq of 'weekly'. You can
override these defaults in your `app/config/config.yml`:

    dpn_xml_sitemap:
        defaults:
            priority: 0.5
            changefreq: weekly

#### Usage

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

or if you simply wnat to use the defaults:

```php
    /**
     * @Route("/find/me/in/sitemap", options={"sitemap" = true})
     */
```

The generated sitemap is then available under the url /sitemap.xml and the bundle
will throw a not found exception if it doesn't contain any routes.

#### License

See `Resources/meta/LICENSE`.
