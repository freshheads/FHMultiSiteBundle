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

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new FHMultiSiteExtension();
    }

    public function tearDown()
    {
        unset($this->container, $this->extension);
    }

    public function testExtensionRequiresARepository()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child node "repository" at path "fh_multi_site" must be configured.');

        $this->extension->load([], $this->container);
    }

    public function testRepositoryCanBeConfigured(): void
    {
        $this->extension->load(
            [
                'fh_multi_site' => [
                    'repository' => 'My\\Custom\\SiteRepository'
                ]
            ],
            $this->container
        );

        $this->assertTrue($this->container->hasAlias(SiteRepositoryInterface::class));
        $this->assertEquals('My\\Custom\\SiteRepository', (string) $this->container->getAlias(SiteRepositoryInterface::class));
    }
}
