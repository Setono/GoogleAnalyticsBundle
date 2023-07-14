<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\Matcher;

interface MatcherInterface
{
    /**
     * Returns true if the matcher supports the given $pattern
     */
    public function supports(string $pattern): bool;

    /**
     * Returns true if the $value matches the $pattern
     */
    public function matches(string $value, string $pattern): bool;
}
