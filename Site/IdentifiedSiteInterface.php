<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
interface IdentifiedSiteInterface extends SiteInterface
{
    public function getIdentifier(): string;

    public function matches(string $identifier): bool;
}
