# Twig extensions

The bundle implements 2 Twig extensions:
- `CurrentSiteExtension`
- `UrlGeneratorExtension`

## CurrentSiteExtension

The `CurrentSiteExtension` provides a Twig function which returns the current site.
The following example will work if your implementation of `SiteInterface` has a `title`.

```twig
{{ current_site().title }}
```

## UrlGeneratorExtension

The `UrlGeneratorExtension` provides 2 Twig functions which helps with url generation.

The extension is linked to the implementation of `SiteResolverInterface` and the related `UrlGeneratorInterface`.
When you have a custom implementation of `SiteResolverInterface`, this extension is **not loaded**.

The functions will use the custom implementation of `UrlGeneratorInterface`.
By default, the current Site will be used for route generation.
You can link to another site by using `site_url` and passing is explicitly.

```twig
{{ site_path('route_name', { 'routeParam': value  }) }}
{{ site_url('site_default', 'route_name', { 'routeParam': value  }) }}
```
