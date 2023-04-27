<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\DependencyInjection;

use Sylius\Bundle\CoreBundle\DependencyInjection\PrependDoctrineMigrationsTrait;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class LemisoftSyliusInvoiceRequestExtension extends Extension implements PrependExtensionInterface
{
    use PrependDoctrineMigrationsTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.php');
    }

    public function getConfiguration(array $config, ContainerBuilder $container): ConfigurationInterface
    {
        return new Configuration();
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependDoctrineMigrations($container);

        $this->prependDoctrineMapping($container);
    }

    protected function getMigrationsNamespace(): string
    {
        return 'LemisoftSyliusInvoiceRequestPlugin';
    }

    protected function getMigrationsDirectory(): string
    {
        return '@LemisoftSyliusInvoiceRequestPlugin/migrations';
    }

    protected function getNamespacesOfMigrationsExecutedBefore(): array
    {
        return ['Sylius\Bundle\CoreBundle\Migrations'];
    }

    private function prependDoctrineMapping(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'LemisoftSyliusInvoiceRequestPlugin' => [
                        'type' => 'attribute',
                        'dir' => $this->getPath($container, '/src/Domain/Model'),
                        'is_bundle' => false,
                        'prefix' => 'Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model',
                        'alias' => 'LemisoftSyliusInvoiceRequestPlugin',
                    ],
                ],
            ],
        ]);
    }

    private function getPath(ContainerBuilder $container, string $path): string
    {
        /** @var array<string, array<string, string>> $metadata */
        $metadata = $container->getParameter('kernel.bundles_metadata');

        return $metadata['LemisoftSyliusInvoiceRequestPlugin']['path'] . $path;
    }
}
