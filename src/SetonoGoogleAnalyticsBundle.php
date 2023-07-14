<?php

declare(strict_types=1);

namespace Setono\GoogleAnalyticsBundle;

use Setono\CompositeCompilerPass\CompositeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SetonoGoogleAnalyticsBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new CompositeCompilerPass(
            'setono_google_analytics.filter.client_side.composite',
            'setono_google_analytics.client_side_filter'
        ));
    }
}
