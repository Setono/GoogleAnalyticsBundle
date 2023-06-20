<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle\Provider;

use Setono\GoogleAnalyticsBundle\ValueObject\Container;

final class ConfigurationBasedContainerProvider implements ContainerProviderInterface
{
    /** @var list<array{container_id: string}> */
    private array $containers;

    /**
     * @param list<array{container_id: string}> $containers
     */
    public function __construct(array $containers)
    {
        // this will filter all containers where the container id is empty
        $this->containers = array_values(array_filter($containers, static function (array $container): bool {
            return '' !== $container['container_id'];
        }));
    }

    public function getContainers(): array
    {
        return array_map(
            static fn (array $container): Container => new Container($container['container_id']),
            $this->containers,
        );
    }
}
