<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Controller\GusGetDataByNipAction;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\GusApiService;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(GusGetDataByNipAction::class)
        ->args([
            service(GusApiService::class),
            service('serializer'),
        ])
        ->autowire()
        ->public();
};
