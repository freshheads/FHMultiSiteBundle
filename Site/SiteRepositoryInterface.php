<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
interface SiteRepositoryInterface
{
    /**
     * @return SiteInterface[]
     */
    public function findAll(): array;
}
