<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\Matcher;

final class RegExpMatcher implements MatcherInterface
{
    public function supports(string $pattern): bool
    {
        return strpos($pattern, '#') === 0;
    }

    public function matches(string $value, string $pattern): bool
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return preg_match($pattern, $value) === 1;
    }
}
