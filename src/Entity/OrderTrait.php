<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait OrderTrait
{
    #[ORM\Column(name: "nip", type: "string", length: 10, nullable: true)]
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
