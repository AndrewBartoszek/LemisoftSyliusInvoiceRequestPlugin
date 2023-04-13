<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusInvoiceRequestPlugin\Controller\Shop\GusApiController;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\GusApiService;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();
    $services
        ->defaults()
        ->autoconfigure(true)
        ->autowire(true)
        ->tag('controller.service_arguments');

    $services
        ->set('lemisoft.sylius_invoice_request_plugin.controller.shop.gus_api_controller', GusApiController::class)
        ->args([
            service(GusApiService::class),
        ]);
};
