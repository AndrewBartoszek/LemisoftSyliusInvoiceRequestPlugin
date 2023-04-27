<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Type;

use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Groups\FormGroupsType;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Input\NipValidationRules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class GusGetDataByNipType extends AbstractType
{
    const NUMERIC_VALUE_CODE = 0;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nip', NumberType::class, [
                'required'    => true,
                'scale' => self::NUMERIC_VALUE_CODE,
                'constraints' => [
                    new NotBlank(groups: [Constraint::DEFAULT_GROUP, FormGroupsType::SYLIUS]),
                    new Length(
                        exactly: NipValidationRules::EXACTLY_LENGTH,
                        groups: [Constraint::DEFAULT_GROUP, FormGroupsType::SYLIUS],
                    ),
                    new Regex(pattern: NipValidationRules::ONLY_NUMBERS),
                ],
            ]);
    }

    public function getBlockPrefix(): string
    {
        return 'lemisoft_sylius_invoice_request_gus_get_data';
    }
}
