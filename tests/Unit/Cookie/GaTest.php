<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Cookie;

use PHPUnit\Framework\TestCase;
use Setono\GoogleAnalyticsBundle\Cookie\Ga;

final class GaTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getValidGaValues
     */
    public function it_creates_from_string(string $value, string $expectedClientId): void
    {
        $ga = Ga::fromString($value);

        self::assertSame($expectedClientId, $ga->clientId);
    }

    /**
     * @test
     *
     * @dataProvider getInvalidGaValues
     */
    public function it_throws(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Ga::fromString($value);
    }

    /**
     * @return \Generator<array-key, array{0: string, 1: string}>
     */
    public function getValidGaValues(): \Generator
    {
        yield ['GA1.2.1412792479.1641820422', '1412792479.1641820422'];
        yield ['GA1.4-2.1412792479.1641820422', '1412792479.1641820422'];
    }

    /**
     * @return \Generator<array-key, array{0: string}>
     */
    public function getInvalidGaValues(): \Generator
    {
        yield [''];
        yield ['GA1.4-2.1641820422.1641820422.invalid'];
    }
}
