<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Unit\DependencyInjection;

use Lemisoft\SyliusInvoiceRequestPlugin\DependencyInjection\Configuration;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    #[Test]
    public function itCreateEmptyConfiguration(): void
    {
        $processor = new Processor();

        self::assertEmpty($processor->processConfiguration(new Configuration(), []));
    }
}
