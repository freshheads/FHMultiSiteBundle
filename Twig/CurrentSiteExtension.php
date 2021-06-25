<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Twig;

use FH\Bundle\MultiSiteBundle\Site\SiteInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class CurrentSiteExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_site', [$this, 'getCurrentSite']),
        ];
    }

    public function getCurrentSite(): ?SiteInterface
    {
        $request = $this->requestStack->getMasterRequest();

        if (!$request instanceof Request) {
            return null;
        }

        return $request->get('site');
    }
}
