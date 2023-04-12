<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Application\src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lemisoft\SyliusInvoiceRequestPlugin\Entity\OrderInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Entity\OrderTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;

#[ORM\Entity()]
#[ORM\Table(name: "sylius_order")]
class Order extends BaseOrder implements OrderInterface
{
    use OrderTrait;
}
