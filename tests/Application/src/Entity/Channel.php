<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Application\src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lemisoft\SyliusInvoiceRequestPlugin\Entity\ChannelInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Entity\ChannelTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

#[ORM\Entity()]
#[ORM\Table(name:"sylius_channel")]
class Channel extends BaseChannel implements ChannelInterface
{
    use ChannelTrait;
}
