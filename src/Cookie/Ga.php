<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Cookie;

final class Ga
{
    private function __construct(public readonly string $clientId)
    {
    }

    /**
     * @throws \InvalidArgumentException if the provided value is invalid
     */
    public static function fromString(string $value): self
    {
        if (preg_match('/(\d+\.\d+)$/', $value, $matches) !== 1) {
            throw new \InvalidArgumentException('The cookie value is not valid');
        }

        return new self($matches[1]);
    }
}
