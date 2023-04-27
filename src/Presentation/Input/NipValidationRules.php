<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Input;

use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Requirement\NipRequirement;

final class NipValidationRules
{
    public const EXACTLY_LENGTH = NipRequirement::MAX_LENGTH;
    public const ONLY_NUMBERS = '/^[0-9]+$/';
}
