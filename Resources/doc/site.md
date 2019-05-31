# Site model

The main interface to implement is `SiteInterface`, which is an empty interface.
To use any of the build-in [resolvers](site_resolver.md), `IdentifiedSiteInterface` has to be implemented.

```php
<?php
namespace FH\Bundle\MultiSiteBundle\Site;

interface IdentifiedSiteInterface extends SiteInterface
{
    public function getIdentifier(): string;
    public function matches(string $identifier): bool;
}
```

There is no build-in repository or list of sites, this is something that is part of your application.
As long as you have a model that implements `SiteInterface`, you can use this bundle.

Example of a concrete site model:

```php
<?php
namespace App\Model;

use FH\Bundle\MultiSiteBundle\Site\IdentifiedSiteInterface;

final class Site implements IdentifiedSiteInterface
{
    private $identifier;
    private $title;

    public function __construct(string $identifier, string $title)
    {
        $this->identifier = $identifier;
        $this->title = $title;
    }

    public function matches(string $identifier): bool
    {
        return $identifier === $this->identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
```

## Site repository

Your application must implement the `SiteRepositoryInterface`:

```php
<?php
namespace FH\Bundle\MultiSiteBundle\Site;

interface SiteRepositoryInterface
{
    /**
     * @return SiteInterface[]
     */
    public function findAll(): array;
}
```

It is up to you how to implement this service:
- You can load the sites from code
- You can link it to Doctrine, to fetch the sites from a database
- ...

Example of a repository from code:

```php
<?php
namespace App\Model;

use FH\Bundle\MultiSiteBundle\Site\SiteRepositoryInterface;

final class SiteRepository implements SiteRepositoryInterface
{
    public function findAll(): array
    {
        return [
           new Site('site_default', 'Default site for my application'),
           new Site('site_subdomain', 'Site that runs on a subdomain')
       ];
    }
}
```

Create a service of your repository:

``` yaml
services:
    App\Model\SiteRepository: ~
```

Add it to the package config:

``` yaml
# config/packages/fh_multi_site.yaml

fh_multi_site:
    repository: 'App\Model\SiteRepository'
```
