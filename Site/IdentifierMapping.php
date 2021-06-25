<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Site;

final class IdentifierMapping implements IdentifierMappingInterface
{
    /**
     * @var IdentifierConfig[]
     */
    private $mapping;

    /**
     * Mapping should be in the form of:
     * <code>
     * [
     *     'identifier' => [ 'hostnames' => [], 'locales' => [] ]
     * ]
     * </code>.
     *
     * @param array $mapping mapping from identifier to matching request part
     */
    public function __construct(array $mapping)
    {
        foreach ($mapping as $identifier => $config) {
            $this->mapping[$identifier] = new IdentifierConfig($config['hostnames'], $config['locales']);
        }
    }

    public function findIdentifierByHostname(string $hostname): ?string
    {
        foreach ($this->mapping as $identifier => $config) {
            if (\in_array($hostname, $config->getHostnames(), true)) {
                return $identifier;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function findHostnamesByIdentifier(string $identifier, string $locale = null): array
    {
        if (!isset($this->mapping[$identifier])) {
            return [];
        }

        $config = $this->mapping[$identifier];

        if (!\is_string($locale)) {
            return $config->getHostnames();
        }

        return \in_array($locale, $config->getLocales(), true) ? $config->getHostnames() : [];
    }
}
