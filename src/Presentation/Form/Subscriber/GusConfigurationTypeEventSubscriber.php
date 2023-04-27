<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Subscriber;

use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\GusConfiguration;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

final class GusConfigurationTypeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    public function preSetData(FormEvent $event): void
    {
        /** @var null|GusConfiguration $data */
        $data = $event->getData();
        $form = $event->getForm();

        $form->add('isTest', CheckboxType::class, [
            'label'    => 'lsirp.form.channel.gus_api_test',
            'required' => false,
            'data'     => (null === $data || $data->isTest())
        ]);
    }
}
