<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Form\Extension;

use Lemisoft\SyliusInvoiceRequestPlugin\Entity\OrderInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Form\Subscriber\CheckoutAddressTypeEventSubscriber;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CheckoutAddressTypeExtension extends AbstractTypeExtension
{
    protected const NIP_LENGTH = 10;

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
                'attr'     => ['class' => 'js-want-invoice-btn'],
            ])
            ->add('nip', NumberType::class, [
                'label'       => 'lsirp.form.checkout_address.nip',
                'required'    => true,
                'constraints' => [
                    new NotBlank(['groups' => ['want_invoice_group']]),
                    new Length(['value' => self::NIP_LENGTH, 'groups' => ['Default', 'sylius']]),
                ],
            ])
            ->addEventSubscriber(new CheckoutAddressTypeEventSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer('validation_groups', function (Options $options, array $validationGroups) {
            return function (FormInterface $form) use ($validationGroups) {
                if ((bool)$form->get('wantInvoice')->getData()) {
                    $validationGroups[] = 'want_invoice_group';
                }

                return $validationGroups;
            };
        });
    }

    public static function getExtendedTypes(): iterable
    {
        return [AddressType::class];
    }
}
