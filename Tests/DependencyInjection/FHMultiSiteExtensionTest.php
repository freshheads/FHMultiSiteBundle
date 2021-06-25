<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Tests\DependencyInjection;

use FH\Bundle\MultiSiteBundle\DependencyInjection\FHMultiSiteExtension;
use FH\Bundle\MultiSiteBundle\Site\SiteRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class FHMultiSiteExtensionTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var FHMultiSiteExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new FHMultiSiteExtension();
    }

    protected function tearDown(): void
    {
        unset($this->container, $this->extension);
    }

    public function testExtensionRequiresARepository(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessageMatches('/^The child (config|node) "repository" (under|at path) "fh_multi_site" must be configured.$/');

        $this->extension->load([], $this->container);
    }

    public function testRepositoryCanBeConfigured(): void
    {
        $this->extension->load(
            [
                'fh_multi_site' => [
                    'repository' => 'My\\Custom\\SiteRepository',
                ],
            ],
            $this->container
        );

        $this->assertTrue($this->container->hasAlias(SiteRepositoryInterface::class));
        $this->assertSame('My\\Custom\\SiteRepository', (string) $this->container->getAlias(SiteRepositoryInterface::class));
    }
}
