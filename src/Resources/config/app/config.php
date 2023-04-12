<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\GusApiService;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->import(
        '@LemisoftSyliusInvoiceRequestPlugin/src/Resources/config/sylius/events.yaml',
    );
    $containerConfigurator->import(
        '@LemisoftSyliusInvoiceRequestPlugin/src/Resources/config/sylius/sylius_resource.yaml',
    );
    $containerConfigurator->import(
        '@LemisoftSyliusInvoiceRequestPlugin/src/Resources/config/app/controller.php',
    );

    $services = $containerConfigurator->services();

    $services
        ->set(
            'lemisoft.sylius_invoice_request_plugin.service.gus.gus_api_service',
            GusApiService::class,
        )
        ->args([
            service('sylius.context.channel'),
            service('jms_serializer.serializer'),
        ])
        ->alias(GusApiService::class, 'lemisoft.sylius_invoice_request_plugin.service.gus.gus_api_service');
};
