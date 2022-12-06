<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Context\SessionId;

final class SessionId
{
    /**
     * The session id
     */
    public string $value;

    /**
     * When the session id was created
     */
    public int $timestamp;

    private function __construct(string $value, int $timestamp)
    {
        $this->value = $value;
        $this->timestamp = $timestamp;
    }

    public static function new(): self
    {
        return new self(bin2hex(random_bytes(8)), time());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
