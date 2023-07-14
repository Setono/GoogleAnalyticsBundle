<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\Matcher;

final class EqualityMatcher implements MatcherInterface
{
    public function supports(string $pattern): bool
    {
        return true;
    }

    public function matches(string $value, string $pattern): bool
    {
        return $value === $pattern;
    }
}
