<?php

namespace Lemisoft\SyliusInvoiceRequestPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Core\Model\ChannelInterface as CoreChannel;

interface ChannelInterface extends BaseChannelInterface, CoreChannel
{
    public function getGusConfiguration(): ?GusConfigurationInterface;

    public function setGusConfiguration(?GusConfigurationInterface $gusConfiguration): void;

    public function hasGusApiConfigured(): bool;

    public function isTestGusApi(): bool;
}
