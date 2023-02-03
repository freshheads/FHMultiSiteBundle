<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Router;

use FH\Bundle\MultiSiteBundle\Site\IdentifiedSiteInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class PathIdentifiedUrlGenerator implements UrlGeneratorInterface
{
    private $requestContext;
    private $routeParameter;
    private $defaultIdentifier;
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, string $routeParameter = 'site', string $defaultIdentifier = null)
    {
        $this->routeParameter = $routeParameter;
        $this->defaultIdentifier = $defaultIdentifier;
        $this->urlGenerator = $urlGenerator;
    }

    public function setContext(RequestContext $context): void
    {
        $this->requestContext = $context;
    }

    public function getContext(): ?RequestContext
    {
        return $this->requestContext;
    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH): string
    {
        if (isset($parameters['site'])) {
            if ($parameters['site'] instanceof IdentifiedSiteInterface) {
                /** @var IdentifiedSiteInterface $site */
                $site = $parameters['site'];
                $identifier = $site->getIdentifier();
            } elseif (\is_string($parameters['site'])) {
                $identifier = $parameters['site'];
            }
        }

        if (isset($identifier)) {
            unset($parameters['site']);
            $parameters[$this->routeParameter] = $identifier === $this->defaultIdentifier ? null : $identifier;
        }

        return $this->urlGenerator->generate($name, $parameters, $referenceType);
    }
}
