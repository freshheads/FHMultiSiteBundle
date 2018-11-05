# Site resolver

The site resolver is a service which detects which site belongs to the current request. The site
resolver has to be enabled explicitly:

``` yaml
# config/packages/fh_multi_site.yaml

fh_multi_site:
    resolver: ~
```

This feature requires an implementation of `SiteResolverInterface`. There are 2 build in resolvers:
- `HostnameIdentifiedSiteResolver`
- `PrefixedPathIdentifiedSiteResolver`

```yaml
fh_multi_site:
    resolver:
        # Add following lines when you want to use the build-in HostnameIdentifiedSiteResolver
        type: 'hostname_identified'
        host_mapping: []
        
        # Add following lines when you want to use the build-in PrefixedPathIdentifiedSiteResolver
        type: 'prefixed_path_identified'
        default_identifier: ~
        identifiers: []  
        
        # Add following lines when you want to use a custom implementation of SiteResolverInterface
        type: 'service'
        service_id: ~
```        

## Build-in resolver: HostnameIdentifiedSiteResolver

Example of resolved sites by the `HostnameIdentifiedSiteResolver`.

| Request  | Resolved Site |
| ------------- | ------------- |
| https://example-xx.com | site_xx |
| https://example-yy.com/random-slug  | site_yy |
| https://example-zz.com/random-slug/random-slug-2 | site_zz |
| https://other-domain.com | throws `SiteNotFoundException` |

```yaml
fh_multi_site:
    resolver:
        type: 'hostname_identified'
        host_mapping: '%host_mapping%'
     
parameters:
    host_mapping:
         -
             identifier: 'site_xx'
             hostnames: 'example-xx.com'
         -
             identifier: 'site_yy'
             hostnames: 'example-yy.com'
         -
             identifier: 'site_zz'
             hostnames: 'example-zz.com'
```

## Build-in resolver: PrefixedPathIdentifiedSiteResolver

Example of resolved sites by the `PrefixedPathIdentifiedSiteResolver`.

| Request  | Resolved Site |
| ------------- | ------------- |
| https://example.com | site_default |
| https://example.com/random-slug  | site_default |
| https://other-domain.com | site_default |
| https://example.com/section | section |
| https://example.com/section/random-slug | section |
| https://example.com/path-prefix-2 | path-prefix-2 |

```yaml
fh_multi_site:
    resolver:
        type: 'prefixed_path_identified'
        default_identifier: 'site_default'
        identifiers: [ 'section', 'path-prefix-2' ]
```

## Create your own SiteResolver

Create a resolver class which implements `SiteResolverInterface` and returns the site object:

``` php
<?php
namespace App\Site;

use FH\Bundle\MultiSiteBundle\Site\SiteInterface;
use FH\Bundle\MultiSiteBundle\Site\SiteResolverInterface;

final class CustomSiteResolver implements SiteResolverInterface
{
    public function resolve(): SiteInterface;
    {
        // ....
        return $site;
    }
}
```

Register class as a service:

``` yaml
services:
    App\Site\CustomSiteResolver: ~
```

And configure this service as the resolver:

``` yaml
# config/packages/fh_multi_site.yaml

fh_multi_site:
    resolver:
        type: 'service'
        service_id: 'App\Site\CustomSiteResolver'
```
