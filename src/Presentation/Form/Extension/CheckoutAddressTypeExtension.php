<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Extension;

use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\OrderInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Groups\FormGroupsType;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Subscriber\CheckoutAddressTypeEventSubscriber;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Input\NipValidationRules;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class CheckoutAddressTypeExtension extends AbstractTypeExtension
{
    const NUMERIC_VALUE_CODE = 0;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var OrderInterface $order */
        $order = $options['data'];

        $builder
            ->add('wantInvoice', CheckboxType::class, [
                'mapped'   => false,
                'required' => false,
                'label'    => 'lsirp.form.checkout_address.want_invoice',
                'data'     => null !== $order->getNip(),
            ])
            ->add('nip', NumberType::class, [
                'label'       => 'lsirp.form.checkout_address.nip',
                'required'    => true,
                'scale' => self::NUMERIC_VALUE_CODE,
                'constraints' => [
                    new NotBlank(groups: ['want_invoice_group']),
                    new Length(
                        exactly: NipValidationRules::EXACTLY_LENGTH,
                        groups: [Constraint::DEFAULT_GROUP, FormGroupsType::SYLIUS],
                    ),
                    new Regex(pattern: NipValidationRules::ONLY_NUMBERS),
                ],
            ])
            ->addEventSubscriber(new CheckoutAddressTypeEventSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer(
            'validation_groups',
            static fn (Options $options, array $validationGroups) => static function (FormInterface $form) use (
                $validationGroups
            ) {
                if ((bool)$form->get('wantInvoice')->getData()) {
                    $validationGroups[] = 'want_invoice_group';
                }

                return $validationGroups;
            }
        );
    }

    public static function getExtendedTypes(): iterable
    {
        return [AddressType::class];
    }
}
