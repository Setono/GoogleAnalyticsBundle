<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\Ga;

use Symfony\Component\HttpFoundation\Cookie;

/**
 * This class represents the _ga cookie
 */
final class Ga
{
    public int $version = 1;

    /**
     * This is the number of components in the domain name
     *
     * example.com => 2
     * sub.example.com => 3
     * etc.
     */
    public int $domainComponents;

    /**
     * /**
     * This is the number of components in the path
     *
     * / => 1
     * /folder/ => 2
     * /folder/sub-folder/ => 3
     * etc.
     */
    public int $pathComponents;

    public int $clientId;

    /**
     * The unix timestamp in seconds
     */
    public int $timestamp;

    private function __construct(int $domainComponents, int $pathComponents, int $clientId, int $timestamp)
    {
        $this->domainComponents = $domainComponents;
        $this->pathComponents = $pathComponents;
        $this->clientId = $clientId;
        $this->timestamp = $timestamp;
    }

    public static function new(): self
    {
        return new self(2, 1, random_int(1_000_000_000, 1_999_999_999), time());
    }

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

    public function __toString(): string
    {
        return $this->value();
    }

    public function value(): string
    {
        return sprintf(
            'GA%d.%d%s.%d.%d',
            $this->version,
            $this->domainComponents,
            $this->pathComponents > 1 ? ('-' . $this->pathComponents) : '',
            $this->clientId,
            $this->timestamp,
        );
    }

    public function asCookie(): Cookie
    {
        return Cookie::create('_ga', $this->value(), new \DateTimeImmutable('+2 years'))
            ->withHttpOnly(false) // we need this to allow the js library to also use the cookie value
        ;
    }
}
