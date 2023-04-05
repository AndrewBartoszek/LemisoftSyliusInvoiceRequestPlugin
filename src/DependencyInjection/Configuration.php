<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('lemisoft_sylius_invoice_request_plugin');
    }
}
