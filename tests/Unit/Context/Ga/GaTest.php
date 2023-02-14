<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Context\Ga;

use PHPUnit\Framework\TestCase;
use Setono\GoogleAnalyticsBundle\Context\Ga\Ga;

final class GaTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getValidGaValues
     */
    public function it_creates_from_string(string $value, int $expectedVersion, int $expectedDomainComponents, int $expectedPathComponents, int $expectedRandomNumber, int $expectedTimestamp): void
    {
        $ga = Ga::fromString($value);

        self::assertSame($expectedVersion, $ga->version);
        self::assertSame($expectedDomainComponents, $ga->domainComponents);
        self::assertSame($expectedPathComponents, $ga->pathComponents);
        self::assertSame($expectedRandomNumber, $ga->clientId);
        self::assertSame($expectedTimestamp, $ga->timestamp);
    }

    /**
     * @return \Generator<array-key, array{0: string, 1: int, 2: int, 3: int, 4: int, 5: int}>
     */
    public function getValidGaValues(): \Generator
    {
        yield ['GA1.2.1412792479.1641820422', 1, 2, 1, 1412792479, 1641820422];
        yield ['GA1.4-2.1412792479.1641820422', 1, 4, 2, 1412792479, 1641820422];
    }
}
