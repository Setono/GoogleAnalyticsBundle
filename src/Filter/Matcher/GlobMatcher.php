<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\Matcher;

use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Glob\Glob;

final class GlobMatcher implements MatcherInterface
{
    public function supports(string $pattern): bool
    {
        return (new Filesystem())->isAbsolutePath($pattern);
    }

    public function matches(string $value, string $pattern): bool
    {
        return Glob::match($value, $pattern);
    }
}
