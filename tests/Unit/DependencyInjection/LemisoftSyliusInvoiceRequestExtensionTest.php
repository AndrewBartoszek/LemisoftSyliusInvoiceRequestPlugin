<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Unit\DependencyInjection;

use Doctrine\Bundle\MigrationsBundle\DependencyInjection\DoctrineMigrationsExtension;
use Lemisoft\SyliusInvoiceRequestPlugin\DependencyInjection\Configuration;
use Lemisoft\SyliusInvoiceRequestPlugin\DependencyInjection\LemisoftSyliusInvoiceRequestExtension;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SyliusLabs\DoctrineMigrationsExtraBundle\DependencyInjection\SyliusLabsDoctrineMigrationsExtraExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class LemisoftSyliusInvoiceRequestExtensionTest extends TestCase
{
    private ContainerBuilder $container;
    private LemisoftSyliusInvoiceRequestExtension $extension;

    #[Test]
    public function itAutoconfiguresPrependingDoctrineMigrationWithProperMigrationsPaths(): void
    {
        $this->container->registerExtension(new DoctrineMigrationsExtension());
        $this->container->registerExtension(new SyliusLabsDoctrineMigrationsExtraExtension());

        $this->extension->load([], $this->container);
        $this->extension->prepend($this->container);

        $doctrineConf = $this->container->getExtensionConfig('doctrine_migrations');
        $syliusCond = $this->container->getExtensionConfig('sylius_labs_doctrine_migrations_extra');

        self::assertArrayHasKey('LemisoftSyliusInvoiceRequestPlugin', $syliusCond[0]['migrations']);
        self::assertArrayHasKey('LemisoftSyliusInvoiceRequestPlugin', $doctrineConf[0]['migrations_paths']);
        self::assertSame(
            ['Sylius\Bundle\CoreBundle\Migrations'],
            $syliusCond[0]['migrations']['LemisoftSyliusInvoiceRequestPlugin'],
        );
        self::assertSame(
            '@LemisoftSyliusInvoiceRequestPlugin/migrations',
            $doctrineConf[0]['migrations_paths']['LemisoftSyliusInvoiceRequestPlugin'],
        );
    }

    #[Test]
    public function itDoesntAutoconfiguresPrependingDoctrineMigrationWhenIsDisabled(): void
    {
        $this->extension->load([], $this->container);
        $this->extension->prepend($this->container);

        $doctrineConf = $this->container->getExtensionConfig('doctrine_migrations');
        $syliusCond = $this->container->getExtensionConfig('sylius_labs_doctrine_migrations_extra');

        self::assertEmpty($doctrineConf);
        self::assertEmpty($syliusCond);
    }

    #[Test]
    public function itPrependsConfigurationWithDoctrineMapping(): void
    {
        $this->extension->load([], $this->container);
        $this->extension->prepend($this->container);

        $doctrineConfig = $this->container->getExtensionConfig('doctrine')[0];

        self::assertSame($doctrineConfig['orm']['mappings']['LemisoftSyliusInvoiceRequestPlugin'], [
            'type' => 'attribute',
            'dir' => __DIR__ . '../../src/Domain/Model',
            'is_bundle' => false,
            'prefix' => 'Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model',
            'alias' => 'LemisoftSyliusInvoiceRequestPlugin',
        ]);
    }

    #[Test]
    public function itReturnsCorrectConfiguration(): void
    {
        self::assertInstanceOf(Configuration::class, $this->extension->getConfiguration([], $this->container));
    }

    protected function setUp(): void
    {
        $this->extension = new LemisoftSyliusInvoiceRequestExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter(
            'kernel.bundles_metadata',
            ['LemisoftSyliusInvoiceRequestPlugin' => ['path' => __DIR__ . '../..']],
        );
    }
}
