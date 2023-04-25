<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Unit;

use Lemisoft\SyliusInvoiceRequestPlugin\DependencyInjection\LemisoftSyliusInvoiceRequestExtension;
use Lemisoft\SyliusInvoiceRequestPlugin\LemisoftSyliusInvoiceRequestPlugin;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SyliusInvoiceRequestPluginTest extends TestCase
{
    private const PARENT_DIRECTORY_LEVEL = 2;

    #[Test]
    public function itCreatesContainerExtensionClass(): void
    {
        $bundle = new LemisoftSyliusInvoiceRequestPlugin();

        self::assertInstanceOf(LemisoftSyliusInvoiceRequestExtension::class, $bundle->getContainerExtension());
    }

    #[Test]
    public function itReturnsValidExtensionPath(): void
    {
        $bundle = new LemisoftSyliusInvoiceRequestPlugin();

        self::assertEquals(\dirname(__DIR__, self::PARENT_DIRECTORY_LEVEL), $bundle->getPath());
    }
}
