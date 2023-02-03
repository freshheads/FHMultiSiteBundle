<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 */
final class UrlGeneratorExtension extends AbstractExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('site_path', [$this, 'generateSitePath']),
            new TwigFunction('site_url', [$this, 'generateSiteUrl']),
        ];
    }

    public function generateSitePath(string $name, array $parameters = []): string
    {
        return $this->urlGenerator->generate($name, $parameters);
    }

    public function generateSiteUrl($site, string $name, array $parameters = [], $schemeRelative = false): string
    {
        $parameters['site'] = $site;

        return $this->urlGenerator->generate(
            $name,
            $parameters,
            $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
