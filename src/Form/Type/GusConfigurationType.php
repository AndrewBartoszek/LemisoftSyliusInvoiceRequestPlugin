<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Form\Type;

use Lemisoft\SyliusInvoiceRequestPlugin\Entity\GusConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GusConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextType::class, [
                'label'    => 'lsirp.form.channel.gus_api_token',
                'required' => false,
            ])
            ->add('isTest', CheckboxType::class, [
                'label'    => 'lsirp.form.channel.gus_api_test',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GusConfiguration::class,
        ]);
    }
}
