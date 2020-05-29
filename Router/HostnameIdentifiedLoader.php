<?php
declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Router;

use FH\Bundle\MultiSiteBundle\Site\IdentifierMapping;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class HostnameIdentifiedLoader extends Loader
{
    public const TYPE = 'hostname_identified';

    /**
     * Resource stack to prevent loading loops
     * @var string[]
     */
    private $resourceStack = [];

    private $identifierMapping;

    public function __construct(IdentifierMapping $identifierMapping)
    {
        $this->identifierMapping = $identifierMapping;
    }

    public function load($resource, string $type = null): RouteCollection
    {
        $this->resourceStack[] = $resource;

        $importedCollection = $this->import($resource);

        array_pop($this->resourceStack);

        if (count($importedCollection) <= 0) {
            return $importedCollection;
        }

        $collection = new RouteCollection();

        foreach ($importedCollection as $name => $route) {
            $configuredRoute = $this->configureRoute($route);
            $collection->add($name, $configuredRoute);
        }

        return $collection;
    }

    public function supports($resource, string $type = null): bool
    {
        return null === $type && !in_array($resource, $this->resourceStack);
    }

    private function configureRoute(Route $route): Route
    {
        if (!$route->hasOption('hostname_identifiers')) {
            return $route;
        }

        $newRoute = clone $route;
        $identifiers = (array) $newRoute->getOption('hostname_identifiers');
        $locale = $newRoute->hasDefault('_canonical_route') ? $newRoute->getDefault('_locale') : null;
        $hostnames = $this->resolveHostnames($identifiers, $locale);

        if (count($hostnames) === 0) {
            throw new \RuntimeException(sprintf('No hostnames resolved for path "%s" and host identifiers %s', $route->getPath(), implode(', ', $identifiers)));
        }

        $regex = implode('|', array_map('preg_quote', $hostnames));

        $newRoute
            ->setHost('{site_hostname}')
            ->setRequirement('site_hostname', $regex);

        if (count($hostnames) === 1) {
            $newRoute->setDefault('site_hostname', reset($hostnames));
        }

        return $newRoute;
    }

    private function resolveHostnames(array $identifiers, ?string $locale): array
    {
        $hostnames = [];

        foreach ($identifiers as $identifier) {
            $mappedHostnames = $this->identifierMapping->findHostnamesByIdentifier($identifier, $locale);
            $hostnames = array_merge($hostnames, $mappedHostnames);
        }

        return $hostnames;
    }
}
