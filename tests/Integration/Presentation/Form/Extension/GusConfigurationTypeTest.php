<?php

declare(strict_types=1);

namespace Lemisoft\Tests\SyliusInvoiceRequestPlugin\Integration\Presentation\Form\Extension;

use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\GusConfiguration;
use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Type\GusConfigurationType;
use Lemisoft\Tests\SyliusInvoiceRequestPlugin\Common\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Form\FormInterface;

final class GusConfigurationTypeTest extends AbstractIntegrationTestCase
{
    #[Test]
    public function itSetCorrectlyGusConfigurationData(): void
    {
        $submittedData = [
            'token'  => $token = '1234',
            'isTest' => true,
        ];

        $gusConfiguration = new GusConfiguration();
        $gusConfiguration->setToken('1111');
        $gusConfiguration->setIsTest(false);

        $form = $this->createForm($gusConfiguration);
        $form->submit($submittedData);

        self::assertEquals($token, $gusConfiguration->getToken());
        self::assertTrue($gusConfiguration->isTest());
    }

    #[Test]
    public function isClearTokenInTestConfigurationData(): void
    {
        $submittedData = [
            'token'  => null,
            'isTest' => $isTest = true,
        ];

        $gusConfiguration = new GusConfiguration();
        $gusConfiguration->setToken('1111');
        $gusConfiguration->setIsTest($isTest);

        $form = $this->createForm($gusConfiguration);
        $form->submit($submittedData);

        self::assertNull($gusConfiguration->getToken());
        self::assertTrue($gusConfiguration->isTest());
        self::assertEmpty($form->get('token')->getErrors()->count());
    }

    #[Test]
    public function isClearTokenInProductionConfigurationData(): void
    {
        $submittedData = [
            'token'  => null,
            'isTest' => $isTest = false,
        ];

        $gusConfiguration = new GusConfiguration();
        $gusConfiguration->setToken('1111');
        $gusConfiguration->setIsTest($isTest);

        $form = $this->createForm($gusConfiguration);
        $form->submit($submittedData);

        self::assertNotEmpty($form->get('token')->getErrors()->count());
    }

    private function createForm(GusConfiguration $gusConfiguration): FormInterface
    {
        // @phpstan-ignore-next-line
        return $this->get('form.factory')->create(GusConfigurationType::class, $gusConfiguration);
    }
}
