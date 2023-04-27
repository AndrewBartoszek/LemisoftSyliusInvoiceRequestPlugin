<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus;

use Exception;
use GusApi\GusApi;
use GusApi\ReportTypes;
use GusApi\SearchReport;
use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\ChannelInterface;
use Lemisoft\SyliusInvoiceRequestPlugin\Domain\Model\GusConfigurationInterface;
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

    public function __construct(private ChannelContextInterface $channelContext)
    {
    }

    /**
     *  For testing use nip: 5422456771
     */
    public function getDataFromNip(string $nip): GusDataResponse
    {
        $gusResponse = new GusDataResponse($nip);
        try {
            return $this->tryGetGusData($nip, $gusResponse);
        } catch (Exception) {
            $gusResponse->status = GusDataResponse::ERROR_STATUS;

            return $gusResponse;
        }
    }

    protected function tryGetGusData(string $nip, GusDataResponse $gusResponse): GusDataResponse
    {
        $gusApiClient = $this->getGusApiClient();
        $gusApiClient->login();
        $reports = $gusApiClient->getByNip($nip);
        foreach ($reports as $report) {
            if ($report->getSilo() !== SiloIdType::ACTIVITY_DELETED->value) {
                $gusResponse->fromSearchReport($report);
                $gusResponse = $this->getPersonalData($gusApiClient, $report, $gusResponse);
                $gusResponse->status = GusDataResponse::SUCCESS_STATUS;

                break;
            }
        }

        return $gusResponse;
    }

    protected function getPersonalData(
        GusApi $gusApiClient,
        SearchReport $report,
        GusDataResponse $gusResponse,
    ): GusDataResponse {
        $reportType = ReportTypes::REPORT_PERSON;
        $fullReport = $gusApiClient->getFullReport($report, $reportType);
        if (isset($fullReport[0][self::FULL_REPORT_FIRST_NAME_KEY])) {
            $gusResponse->firstName = $fullReport[0][self::FULL_REPORT_FIRST_NAME_KEY];
        }
        if (isset($fullReport[0][self::FULL_REPORT_LAST_NAME_KEY])) {
            $gusResponse->lastName = $fullReport[0][self::FULL_REPORT_LAST_NAME_KEY];
        }

        return $gusResponse;
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
            /** @var string $token */
            $env = self::PROD_ENV;
            $token = $gusConfig?->getToken();
            if (null === $token) {
                throw new \Exception('Production GUS configuration require token');
            }
        }

        return new GusApi($token, $env);
    }
}
