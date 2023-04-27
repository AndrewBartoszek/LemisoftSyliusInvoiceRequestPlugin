<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\GusApiService;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import('config/*.php');

    $containerConfigurator->import('config/sylius_resource.yaml');

    $services = $containerConfigurator->services();

    $services
        ->set(
            'lemisoft.sylius_invoice_request_plugin.service.gus.gus_api_service',
            GusApiService::class,
        )
        ->args([service('sylius.context.channel')])
        ->alias(GusApiService::class, 'lemisoft.sylius_invoice_request_plugin.service.gus.gus_api_service');
};
