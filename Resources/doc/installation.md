Installation
============

1. Install the FHMultiSiteBundle and it's dependencies:
    ```bash
    composer require freshheads/multi-site-bundle
    ```

2. Add the bundle and its dependencies (if not already present) to `config/bundles.php`:
   ```php
   $bundles = [
       // ...
       new FH\Bundle\MultiSiteBundle\FHMultiSiteBundle(),
       // ...
   ];
   ```


Model setup
-----------

Create entity class mapping config and repository in your application (for ORM):

``` php
<?php
// src/FH/Bundle/AppBundle/Entity/Site.php
declare(strict_types=1);

namespace App\Domain\Model\Site;

use FH\Bundle\MultiSiteBundle\Site\SiteInterface;

class Site implements SiteInterface
{
}
```

``` yaml
# config/doctrine/domain/site/Site.orm.yml

App\Domain\Model\Site:
    type: entity
    table: site
```

``` php
<?php
// src/App/Infrastructure/Model/Site/Repository/SiteRepository.php
declare(strict_types=1);

namespace App\Infrastructure\Model\Site\Repository;

use FH\Bundle\MultiSiteBundle\Site\SiteInterface;use FH\Bundle\MultiSiteBundle\Site\SiteRepositoryInterface;

final class SiteRepository implements SiteRepositoryInterface
{
    /**
     * @return SiteInterface[]
     */
    public function findAll() : array
    {
        // TODO: Implement findAll() method.
    }
}
```

``` yaml
# config/packages/fh_multi_site.yaml

fh_multi_site:
    repository: 'App/Infrastructure/Model/Site/Repository/SiteRepository'
```

Now the bundle is ready to use!
