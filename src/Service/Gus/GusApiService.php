<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus;

use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use GusApi\ReportTypes;
use JMS\Serializer\SerializerInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Entity\ChannelInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Entity\GusConfigurationInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\Model\GusDataResponse;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\Model\SiloIdType;
use Sylius\Component\Channel\Context\ChannelContextInterface;

final class GusApiService
{
    private const TEST_TOKEN = 'abcde12345abcde12345';
    private const DEV_ENV = 'dev';
    private const PROD_ENV = 'prod';
    private const FULL_REPORT_FIRST_NAME_KEY = 'fiz_imie1';
    private const FULL_REPORT_LAST_NAME_KEY = 'fiz_nazwisko';

    public function __construct(private ChannelContextInterface $channelContext, private SerializerInterface $serializer)
    {
    }

    /**
     *  For testing use nip: 5422456771
     */
    public function getDataFromNip(string $nip): GusDataResponse
    {
        $gusApiClient = $this->getGusApiClient();

        $nip = str_replace([' ', '-'], '', $nip);

        $gusResponse = new GusDataResponse($nip);
        try {
            $gusApiClient->login();
            $reports = $gusApiClient->getByNip($nip);
            foreach ($reports as $report) {
                if ($report->getSilo() !== SiloIdType::ACTIVITY_DELETED->value) {
                    $reportType = ReportTypes::REPORT_PERSON;
                    $gusResponse->fromSearchReport($report);

                    $fullReport = $gusApiClient->getFullReport($report, $reportType);
                    if (isset($fullReport[0][self::FULL_REPORT_FIRST_NAME_KEY])) {
                        $gusResponse->firstName = $fullReport[0][self::FULL_REPORT_FIRST_NAME_KEY];
                    }
                    if (isset($fullReport[0][self::FULL_REPORT_LAST_NAME_KEY])) {
                        $gusResponse->lastName = $fullReport[0][self::FULL_REPORT_LAST_NAME_KEY];
                    }

                    $gusResponse->status = GusDataResponse::SUCCESS_STATUS;

                    break;
                }
            }

            if ($gusResponse->status === GusDataResponse::ERROR_STATUS) {
                $gusResponse->massage = 'Data not found';
            }

            return $gusResponse;
        } catch (InvalidUserKeyException|NotFoundException $e) {
            $gusResponse->status = GusDataResponse::ERROR_STATUS;
            $gusResponse->massage = $e->getMessage();

            return $gusResponse;
        }
    }

    public function getJsonResponse(GusDataResponse $response): string
    {
        return $this->serializer->serialize($response, 'json');
    }

    protected function getGusApiClient(): GusApi
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        if ($channel->isTestGusApi()) {
            $token = self::TEST_TOKEN;
            $env = self::DEV_ENV;
        } else {
            /** @var GusConfigurationInterface $gusConfig */
            $gusConfig = $channel->getGusConfiguration();
            $token = $gusConfig->getToken();
            $env = self::PROD_ENV;
        }

        return new GusApi($token, $env);
    }
}
