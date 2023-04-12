<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Lemisoft\SyliusInvoiceRequestPlugin\Form\Extension\ChannelTypeExtension;
use Lemisoft\SyliusInvoiceRequestPlugin\Form\Extension\CheckoutAddressTypeExtension;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services
        ->set(
            'lemisoft.sylius_invoice_request_plugin.form.extension.checkout_address_type_extension',
            CheckoutAddressTypeExtension::class,
        )
        ->tag(
            'form.type_extension',
            ['extended_type' => AddressType::class],
        );

    $services
        ->set(
            'lemisoft.sylius_invoice_request_plugin.form.extension.channel_type_extension',
            ChannelTypeExtension::class,
        )
        ->tag(
            'form.type_extension',
            ['extended_type' => ChannelType::class],
        );
};
