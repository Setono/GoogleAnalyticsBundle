<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\ClientSide;

use Setono\GoogleAnalyticsBundle\Filter\Matcher\EqualityMatcher;
use Setono\GoogleAnalyticsBundle\Filter\Matcher\GlobMatcher;
use Setono\GoogleAnalyticsBundle\Filter\Matcher\MatcherInterface;
use Setono\GoogleAnalyticsBundle\Filter\Matcher\RegExpMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The path based filter will filter on the path part of the URL.
 * This filter considers a path like /admin and /admin/ identical, i.e. we test both versions.
 * NOTICE that if you want to exclude paths based on a regular expression, the regular expression MUST start with #
 */
final class PathBasedClientSideFilter implements ClientSideFilterInterface
{
    /** @var list<MatcherInterface> */
    private array $matchers;

    private RequestStack $requestStack;

    /** @var list<string> */
    private array $paths;

    /**
     * @param list<string> $paths
     */
    public function __construct(RequestStack $requestStack, array $paths)
    {
        $this->matchers = [
            new EqualityMatcher(),
            new GlobMatcher(),
            new RegExpMatcher(),
        ];

        $this->requestStack = $requestStack;
        $this->paths = $paths;
    }

    public function filter(array $context = []): bool
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return true;
        }

        $pathWithoutTrailingSlash = rtrim($request->getPathInfo(), '/');
        $pathWithTrailingSlash = $pathWithoutTrailingSlash . '/';

        foreach ($this->paths as $excludedPath) {
            foreach ($this->matchers as $matcher) {
                if ($matcher->supports($excludedPath) && ($matcher->matches($pathWithoutTrailingSlash, $excludedPath) || $matcher->matches($pathWithTrailingSlash, $excludedPath))) {
                    return false;
                }
            }
        }

        return true;
    }
}
