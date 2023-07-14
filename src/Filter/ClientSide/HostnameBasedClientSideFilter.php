<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Filter\ClientSide;

use Setono\GoogleAnalyticsBundle\Filter\Matcher\EqualityMatcher;
use Setono\GoogleAnalyticsBundle\Filter\Matcher\FnMatcher;
use Setono\GoogleAnalyticsBundle\Filter\Matcher\MatcherInterface;
use Setono\GoogleAnalyticsBundle\Filter\Matcher\RegExpMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The hostname based filter will filter on the hostname part of the URL.
 * NOTICE that if you want to exclude hostnames based on a regular expression, the regular expression MUST start with #
 */
final class HostnameBasedClientSideFilter implements ClientSideFilterInterface
{
    /** @var list<MatcherInterface> */
    private array $matchers;

    private RequestStack $requestStack;

    /** @var list<string> */
    private array $hostnames;

    /**
     * @param list<string> $hostnames
     */
    public function __construct(RequestStack $requestStack, array $hostnames)
    {
        $this->matchers = [
            new EqualityMatcher(),
            new FnMatcher(),
            new RegExpMatcher(),
        ];

        $this->requestStack = $requestStack;
        $this->hostnames = $hostnames;
    }

    public function filter(array $context = []): bool
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return true;
        }

        $hostname = $request->getHttpHost();

        foreach ($this->hostnames as $excludedHostname) {
            foreach ($this->matchers as $matcher) {
                if ($matcher->supports($excludedHostname) && $matcher->matches($hostname, $excludedHostname)) {
                    return false;
                }
            }
        }

        return true;
    }
}
