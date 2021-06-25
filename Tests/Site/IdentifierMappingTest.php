<?php

declare(strict_types=1);

namespace FH\Bundle\MultiSiteBundle\Tests\Site;

use FH\Bundle\MultiSiteBundle\Site\IdentifierMapping;
use FH\Bundle\MultiSiteBundle\Site\IdentifierMappingInterface;
use PHPUnit\Framework\TestCase;

final class IdentifierMappingTest extends TestCase
{
    private const MAPPING = [
        'amsterdam' => [
            'hostnames' => [
                'amsterdam.local',
                'amsterdam.nl',
            ],
            'locales' => ['nl', 'en'],
        ],
        'tilburg' => [
            'hostnames' => [
                'tilburg.local',
                'tilburg.nl',
            ],
            'locales' => ['nl'],
        ],
        'den-bosch' => [
            'hostnames' => [
                'den-bosch.local',
                'den-bosch.nl',
            ],
            'locales' => [],
        ],
    ];

    /**
     * @var IdentifierMappingInterface
     */
    private $identifierMapping;

    protected function setUp(): void
    {
        $this->identifierMapping = new IdentifierMapping(self::MAPPING);
    }

    /**
     * @dataProvider identifierMappingData
     */
    public function testFindIdentifierByHostnameReturnsCorrectIdentifier(string $hostname, string $expectedIdentifier = null): void
    {
        $identifier = $this->identifierMapping->findIdentifierByHostname($hostname);

        $this->assertSame($expectedIdentifier, $identifier);
    }

    public function testFindIdentifierByHostnameReturnsNullWhenNoIdentifierMatches(): void
    {
        $this->assertNull($this->identifierMapping->findIdentifierByHostname('xxxx.xxx'));
    }

    public function testFindHostnamesByIdentifierWithoutLocaleReturnsCorrectHostnames(): void
    {
        foreach (self::MAPPING as $identifier => $item) {
            $this->assertSame($item['hostnames'], $this->identifierMapping->findHostnamesByIdentifier($identifier));
        }
    }

    public function testFindHostnamesByIdentifierWithLocaleReturnsCorrectHostnames(): void
    {
        $hostnames = $this->identifierMapping->findHostnamesByIdentifier('amsterdam', 'nl');
        $this->assertSame(self::MAPPING['amsterdam']['hostnames'], $hostnames);

        $hostnames = $this->identifierMapping->findHostnamesByIdentifier('amsterdam', 'en');
        $this->assertSame(self::MAPPING['amsterdam']['hostnames'], $hostnames);

        $hostnames = $this->identifierMapping->findHostnamesByIdentifier('tilburg', 'en');
        $this->assertEmpty($hostnames);

        $hostnames = $this->identifierMapping->findHostnamesByIdentifier('den-bosch', 'nl');
        $this->assertEmpty($hostnames);
    }

    public function testFindHostnamesByIdentifierReturnsEmptyHostnamesOnNonExistentIdentifier(): void
    {
        $hostnames = $this->identifierMapping->findHostnamesByIdentifier('xxxx');
        $this->assertEmpty($hostnames);
    }

    public function identifierMappingData(): array
    {
        return [
            ['amsterdam.local', 'amsterdam'],
            ['amsterdam.nl', 'amsterdam'],
            ['tilburg.local', 'tilburg'],
            ['tilburg.nl', 'tilburg'],
        ];
    }
}
