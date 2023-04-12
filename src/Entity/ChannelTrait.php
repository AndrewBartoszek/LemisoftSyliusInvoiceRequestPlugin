<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

trait ChannelTrait
{
    #[ORM\OneToOne(
        targetEntity: GusConfiguration::class,
        cascade: ["persist"],
    )]
    #[ORM\JoinColumn(
        name: "gus_configuration_id",
        referencedColumnName: 'id',
        nullable: true,
        onDelete: "CASCADE",
    )]
    private ?GusConfigurationInterface $gusConfiguration = null;

    public function getGusConfiguration(): ?GusConfigurationInterface
    {
        return $this->gusConfiguration;
    }

    public function setGusConfiguration(?GusConfigurationInterface $gusConfiguration): void
    {
        $this->gusConfiguration = $gusConfiguration;
    }

    public function hasGusApiConfigured(): bool
    {
        return null !== $this->gusConfiguration?->getToken();
    }

    public function isTestGusApi(): bool
    {
        return in_array($this->gusConfiguration?->isTest(), [null, true], true) || !$this->hasGusApiConfigured();
    }
}
