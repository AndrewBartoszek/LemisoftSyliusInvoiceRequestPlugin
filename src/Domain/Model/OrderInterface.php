<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model;

use Sylius\Component\Core\Model\OrderInterface as SyliusOrderInterface;

interface OrderInterface extends SyliusOrderInterface
{
    public function getNip(): ?string;

    public function setNip(?string $nip): void;

    public function requestForInvoice(): bool;
}
