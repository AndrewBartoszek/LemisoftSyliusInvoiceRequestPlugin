<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Common;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractIntegrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        static::bootKernel();
    }

    protected function get(string $service): object
    {
        // @phpstan-ignore-next-line
        return self::$kernel->getContainer()->get($service);
    }
}
