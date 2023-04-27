<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Integration\Presentation\Form\Extension;

use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\GusConfiguration;
use Lemisoft\Tests\SyliusInvoiceRequestPlugin\Application\Entity\Channel;
use Lemisoft\Tests\SyliusInvoiceRequestPlugin\Common\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\FormInterface;

final class ChannelTypeExtensionTest extends AbstractIntegrationTestCase
{
    #[Test]
    public function itSetCorrectlyGusConfigurationData(): void
    {
        $submittedData = [
            'gusConfiguration'  => [
                'token' => $token = '1234',
                'isTest' => true,
            ],
        ];

        $channel = new Channel();
        $gusConfiguration = new GusConfiguration();
        $gusConfiguration->setToken('1111');
        $gusConfiguration->setIsTest(false);

        $form = $this->createForm($channel);
        $form->submit($submittedData);

        self::assertEquals($token, $channel->getGusConfiguration()?->getToken());
        self::assertTrue($channel->getGusConfiguration()?->isTest());
    }

    private function createForm(Channel $channel): FormInterface
    {
        // @phpstan-ignore-next-line
        return $this->get('form.factory')->create(ChannelType::class, $channel);
    }
}
