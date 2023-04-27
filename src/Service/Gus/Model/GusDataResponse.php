<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\Model;

use GusApi\SearchReport;

class GusDataResponse
{
    public const ERROR_STATUS = 'error';
    public const SUCCESS_STATUS = 'success';

    public string $status = self::ERROR_STATUS;
    public string $massage = '';
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $company = null;
    public ?string $street = null;
    public ?string $city = null;
    public ?string $buildingNumber = null;
    public ?string $apartmentNumber = null;
    public ?string $postCode = null;

    public function fromSearchReport(SearchReport $searchReport): void
    {
        $this->company = $searchReport->getName();
        $this->street = $searchReport->getStreet();
        $this->city = $searchReport->getCity();
        $this->buildingNumber = $searchReport->getPropertyNumber();
        $this->apartmentNumber = $searchReport->getApartmentNumber();
        $this->postCode = $searchReport->getZipCode();
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
