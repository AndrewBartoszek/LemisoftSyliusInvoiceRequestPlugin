<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model;

use Sylius\Component\Channel\Model\ChannelInterface as BaseChannelInterface;
use Sylius\Component\Core\Model\ChannelInterface as CoreChannel;

interface ChannelInterface extends BaseChannelInterface, CoreChannel
{
    public function getGusConfiguration(): ?GusConfigurationInterface;

    public function setGusConfiguration(?GusConfigurationInterface $gusConfiguration): void;

    public function isTestGusApi(): bool;
}
