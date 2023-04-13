<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Entity;

interface GusConfigurationInterface
{
    public function getId(): ?int;

    public function getToken(): ?string;

    public function setToken(?string $token): void;

    public function isTest(): bool;

    public function setIsTest(bool $isTest): void;
}
