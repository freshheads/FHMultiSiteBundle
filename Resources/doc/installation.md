# Bundle installation

Require the bundle as a dependency.

```bash
$ composer require freshheads/multi-site-bundle
```

Enable it in your application Kernel.

```php
<?php
// config/bundles.php
return [
    //...
    FH\Bundle\MultiSiteBundle\FHMultiSiteBundle::class => ['all' => true],
];
```

## Minimal configuration

You must implement `SiteRepositoryInterface` and add it's service id to `fh_multi_site` config.

``` yaml
# config/packages/fh_multi_site.yaml

fh_multi_site:
    repository: ~
```

See the [Site](site.md) documentation for more details.
