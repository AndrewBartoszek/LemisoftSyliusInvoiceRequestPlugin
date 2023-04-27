<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Extension;

use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Type\GusConfigurationType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gusConfiguration', GusConfigurationType::class, [
                'label' => false,
            ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChannelType::class];
    }
}
