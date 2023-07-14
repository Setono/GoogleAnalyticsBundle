<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Tests\Unit\Filter\ClientSide;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\GoogleAnalyticsBundle\Filter\ClientSide\PathBasedClientSideFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class PathBasedClientSideFilterTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     * @dataProvider providePaths
     *
     * @param list<string> $excludedPaths
     */
    public function it_filters(string $path, array $excludedPaths, bool $expectedFilterResult): void
    {
        $request = $this->prophesize(Request::class);
        $request->getPathInfo()->willReturn($path);

        $requestStack = new RequestStack();
        $requestStack->push($request->reveal());
        $filter = new PathBasedClientSideFilter($requestStack, $excludedPaths);

        self::assertSame($expectedFilterResult, $filter->filter());
    }

    /**
     * @return \Generator<int, array{0: string, 1: list<string>, 2: bool}>
     */
    public function providePaths(): \Generator
    {
        yield ['/', ['/admin', '/api'], true];
        yield ['/admin', ['/admin', '/api'], false];
        yield ['/admin/', ['/admin', '/api'], false];
        yield ['/admin2', ['/admin', '/api'], true];

        yield ['/sub1/sub2/sub3', ['#/sub1/.*#'], false];

        yield ['/sub1/sub2/sub3', ['/sub1/su[ab][0123]/**/'], false];

        yield ['/sub1/sub2/sub3', ['/sub1/**/'], false];
        yield ['/sub1/sub2/sub3/', ['/sub1/**/'], false];

        // Example of excluding a whole subfolder with the same regexp
        yield ['/admin', ['#/admin(/.*)?#'], false];
        yield ['/admin/', ['#/admin(/.*)?#'], false];
        yield ['/admin/google-analytics', ['#/admin(/.*)?#'], false];
        yield ['/admin/google-analytics/settings', ['#/admin(/.*)?#'], false];
    }
}
