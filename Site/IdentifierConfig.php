<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

final class IdentifierConfig
{
    /**
     * @var array
     */
    private $hostnames;
    /**
     * @var array
     */
    private $locales;

    public function __construct(array $hostnames, array $locales)
    {
        $this->hostnames = $hostnames;
        $this->locales = $locales;
    }

    public function getHostnames(): array
    {
        return $this->hostnames;
    }

    public function getLocales(): array
    {
        return $this->locales;
    }
}
