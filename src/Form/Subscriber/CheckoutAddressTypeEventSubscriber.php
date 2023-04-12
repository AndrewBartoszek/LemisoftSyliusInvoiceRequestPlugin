<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class CheckoutAddressTypeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event): void
    {
        /** @var array|null $addressEventData */
        $addressEventData = $event->getData();

        if (null !== $addressEventData) {
            $addressEventData = $this->clearNipField($addressEventData);
        }

        $event->setData($addressEventData);
    }

    protected function clearNipField(array $addressData): array
    {
        if (!isset($addressData['wantInvoice'])) {
            $addressData['nip'] = null;
        }

        return $addressData;
    }
}
