<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

interface IdentifierMappingInterface
{
    public function findIdentifierByHostname(string $hostname): ?string;

    /**
     * @return string[]
     */
    public function findHostnamesByIdentifier(string $identifier, string $locale = null): array;
}
