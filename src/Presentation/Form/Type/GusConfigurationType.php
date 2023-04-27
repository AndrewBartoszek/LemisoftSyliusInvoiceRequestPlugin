<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Type;

use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\GusConfiguration;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Groups\FormGroupsType;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Subscriber\GusConfigurationTypeEventSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

final class GusConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextType::class, [
                'label'       => 'lsirp.form.channel.gus_api_token',
                'required'    => false,
                'constraints' => [
                    new NotBlank(groups: [FormGroupsType::GUS_CONFIGURATION_TYPE_PROD_CONF]),
                ],
            ])
            ->addEventSubscriber(new GusConfigurationTypeEventSubscriber());;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'        => GusConfiguration::class,
            'validation_groups' => function (FormInterface $form) {
                /** @var null|GusConfiguration $config */
                $config = $form->getData();

                $groups = [Constraint::DEFAULT_GROUP, FormGroupsType::SYLIUS];

                if ($this->isProductConfig($config)) {
                    $groups[] = FormGroupsType::GUS_CONFIGURATION_TYPE_PROD_CONF;
                }

                return $groups;
            },
        ]);
    }

    protected function isProductConfig(?GusConfiguration $config): bool
    {
        return null !== $config && !$config->isTest();
    }
}
