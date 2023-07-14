<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Filter\ClientSide;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\GoogleAnalyticsBundle\Filter\ClientSide\HostnameBasedClientSideFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class HostnameBasedClientSideFilterTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     * @dataProvider providePaths
     *
     * @param list<string> $excludedHostnames
     */
    public function it_filters(string $hostname, array $excludedHostnames, bool $expectedFilterResult): void
    {
        $request = $this->prophesize(Request::class);
        $request->getHttpHost()->willReturn($hostname);

        $requestStack = new RequestStack();
        $requestStack->push($request->reveal());
        $filter = new HostnameBasedClientSideFilter($requestStack, $excludedHostnames);

        self::assertSame($expectedFilterResult, $filter->filter());
    }

    /**
     * @return \Generator<int, array{0: string, 1: list<string>, 2: bool}>
     */
    public function providePaths(): \Generator
    {
        yield ['github.com', ['api.github.com'], true];
        yield ['github.com', ['*.github.com'], true];
        yield ['www.github.com', ['*.github.com'], false];
    }
}
