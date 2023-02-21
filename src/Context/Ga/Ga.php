<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

/**
 * This class represents the _ga cookie
 */
final class Ga
{
    public int $version = 1;

    private function __construct(
        /**
         * This is the number of components in the domain name
         *
         * example.com => 2
         * sub.example.com => 3
         * etc.
         */
        public int $domainComponents,
        /**
         * This is the number of components in the path
         *
         * / => 1
         * /folder/ => 2
         * /folder/sub-folder/ => 3
         * etc.
         */
        public int $pathComponents,
        public int $clientId,
        /**
         * The unix timestamp in seconds
         */
        public int $timestamp,
    ) {
    }

    /**
     * @throws \InvalidArgumentException if the provided value is invalid
     */
    public static function fromString(string $value): self
    {
        [, $domainAndPath, $clientId, $timestamp] = explode('.', $value);

        if (is_numeric($domainAndPath)) {
            $domainComponents = (int) $domainAndPath;
            $pathComponents = 1;
        } else {
            [$domainComponents, $pathComponents] = explode('-', $domainAndPath);
        }

        return new self((int) $domainComponents, (int) $pathComponents, (int) $clientId, (int) $timestamp);
    }
}
