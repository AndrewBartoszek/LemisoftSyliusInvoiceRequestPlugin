<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Controller\GusGetDataByNipAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes
        ->add(GusGetDataByNipAction::ROUTE_NAME, '/gus/get_data_from_nip/')
        ->controller(GusGetDataByNipAction::class)
        ->methods([Request::METHOD_POST]);
};
