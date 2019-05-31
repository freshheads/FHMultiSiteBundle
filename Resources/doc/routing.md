# Routing

The bundle provides 2 custom implementations of Symfony's `UrlGeneratorInterface`:
- `HostnameIdentifiedUrlGenerator`
- `PathIdentifiedUrlGenerator`

For the `hostname_identified` resolver strategy, a custom route loader (`HostnameIdentifiedLoader`) is available.

## Route loader 

The `HostnameIdentifiedLoader` adds `host` requirements to a route collection.
By using this strategy, you can add specific routes to a specific site.

To load the routes via the `HostnameIdentifiedLoader`, add the `hostname_identifiers` option to a route or route import.

```yaml
# config/routes

# single route
home:
    path: /
    options:
        hostname_identifiers: [ 'site_default' ]    

# or route collection
_imported:
    resource: 'other_file.yaml'
    options:
        hostname_identifiers: [ 'site_default', 'site_xx' ]
```

You can verify the host requirements by running `bin/console debug:router {route_name}`.
As you can see in the example above, you can import the routes for multiple sites.

```bash
+--------------+------------------------------------------------------------+
| Property     | Value                                                      |
+--------------+------------------------------------------------------------+
| Route Name   | home                                                       |
| Host         | {site_hostname}                                            |
| Host Regex   | #^(?P<site_hostname>example\.com)$#sDi                     |
| Requirements | site_hostname: example\.com                                |
| Options      | compiler_class: Symfony\Component\Routing\RouteCompiler    |
|              | hostname_identifiers: array (0 => 'site_default',)         |
+--------------+------------------------------------------------------------+
```

## UrlGenerators

The url generators will help you generate routes.
You can provide a `site`, from which the generator will extract the required route parameters. 
