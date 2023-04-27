<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Requirement\NipRequirement;

trait OrderTrait
{
    #[ORM\Column(type: Types::STRING, length: NipRequirement::MAX_LENGTH, nullable: true)]
    private ?string $nip = null;

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(?string $nip): void
    {
        $this->nip = $nip;
    }

    public function requestForInvoice(): bool
    {
        return null !== $this->nip;
    }
}
