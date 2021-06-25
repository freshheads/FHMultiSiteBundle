<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\DependencyInjection;

use FH\Bundle\MultiSiteBundle\Router\HostnameIdentifiedUrlGenerator;
use FH\Bundle\MultiSiteBundle\Router\PathIdentifiedUrlGenerator;
use FH\Bundle\MultiSiteBundle\Site\HostnameIdentifiedSiteResolver;
use FH\Bundle\MultiSiteBundle\Site\PrefixedPathIdentifiedSiteResolver;
use FH\Bundle\MultiSiteBundle\Site\SiteRepositoryInterface;
use FH\Bundle\MultiSiteBundle\Site\SiteResolverInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Joris van de Sande <joris.van.de.sande@freshheads.com>
 * @final
 */
class FHMultiSiteExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.yml');

        $this->configureRepository($config['repository'], $container);

        if ($config['resolver']['enabled']) {
            $loader->load('resolver.yml');
            $this->configureResolver($config['resolver'], $container, $loader);

            if (\in_array($config['resolver']['type'], ['hostname_identified', 'prefixed_path_identified'], true)) {
                $loader->load('twig_url_generator.yml');
            }
        }
    }

    private function configureRepository(string $repositoryServiceId, ContainerBuilder $container): void
    {
        $container->setAlias(SiteRepositoryInterface::class, $repositoryServiceId);
    }

    private function configureResolver(array $config, ContainerBuilder $container, FileLoader $loader): void
    {
        if ('hostname_identified' === $config['type']) {
            $serviceId = $this->createHostnameIdentifiedResolver($config, $container, $loader);
        } elseif ('prefixed_path_identified' === $config['type']) {
            $serviceId = $this->createPrefixedPathIdentifiedResolver($config, $container, $loader);
        } elseif ('service' === $config['type']) {
            $serviceId = $config['service_id'];
        }

        $container->setAlias(SiteResolverInterface::class, $serviceId);
    }

    private function createPrefixedPathIdentifiedResolver(
        array $config,
        ContainerBuilder $container,
        FileLoader $loader
    ): string {
        $loader->load('prefixed_path_identified_resolver.yml');

        $serviceId = PrefixedPathIdentifiedSiteResolver::class;

        $container->setParameter(
            'fh_multi_site.prefixed_path_identified_resolver.identifiers',
            array_values($config['identifiers'])
        );
        $container->setParameter(
            'fh_multi_site.prefixed_path_identified_resolver.default_identifier',
            $config['default_identifier']
        );
        $container->setParameter(
            'fh_multi_site.prefixed_path_identified_resolver.route_parameter',
            $config['route_parameter']
        );

        $container->setAlias('fh_multi_site.resolver.url_generator', PathIdentifiedUrlGenerator::class);

        return $serviceId;
    }

    private function createHostnameIdentifiedResolver(
        array $config,
        ContainerBuilder $container,
        FileLoader $loader
    ): string {
        $loader->load('hostname_identified_resolver.yml');

        $serviceId = HostnameIdentifiedSiteResolver::class;

        $container->setParameter('fh_multi_site.hostname_identified_resolver.identifier_mapping', $config['host_mapping']);
        $container->setAlias('fh_multi_site.resolver.url_generator', HostnameIdentifiedUrlGenerator::class);

        return $serviceId;
    }
}
