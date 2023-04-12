<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: 'lemisoft_gus_configuration')]
class GusConfiguration implements ResourceInterface, GusConfigurationInterface
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue()]
    private ?int $id = null;

    #[Column(
        name: "token",
        type: "string",
        nullable: true,
    )]
    private ?string $token = null;

    #[Column(
        name: "is_test",
        type: "boolean",
        nullable: false,
        options: ["default" => true],
    )]
    private bool $isTest = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function isTest(): bool
    {
        return $this->isTest;
    }

    public function setIsTest(bool $isTest): void
    {
        $this->isTest = $isTest;
    }
}
