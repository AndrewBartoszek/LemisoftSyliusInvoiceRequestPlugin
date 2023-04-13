<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait OrderTrait
{
    public const NIP_LENGTH = 10;

    #[ORM\Column(name: "nip", type: "string", length: self::NIP_LENGTH, nullable: true)]
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
