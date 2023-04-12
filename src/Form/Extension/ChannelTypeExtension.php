<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Form\Extension;

use Lemisoft\SyliusInvoiceRequestPlugin\Form\Type\GusConfigurationType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ChannelTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gusConfiguration', GusConfigurationType::class);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ChannelType::class];
    }
}
