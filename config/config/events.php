<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Config\SyliusUiConfig;

return static function (SyliusUiConfig $config): void {
    $config
        ->event('sylius.admin.channel.form.second_column_content')
        ->block('invoice_request', '@LemisoftSyliusInvoiceRequestPlugin/Admin/Channel/Form/_gus_data.html.twig');

    $config
        ->event('sylius.shop.layout.javascripts')
        ->block('invoice_request_js', '@LemisoftSyliusInvoiceRequestPlugin/Shop/Checkout/address_js.html.twig');

    $config
        ->event('lemisoft.shop.checkout.address.want_nip_switch')
        ->block('want_nip_switch', '@LemisoftSyliusInvoiceRequestPlugin/Shop/Checkout/_checkout_want_invoice.html.twig');

    $config
        ->event('lemisoft.shop.checkout.address.billing_address.nip_container')
        ->block('content', '@LemisoftSyliusInvoiceRequestPlugin/Shop/Checkout/_checkout_nip_field.html.twig');
};
