<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Controller\Shop;

use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\GusApiService;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\Model\GusDataResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class GusApiController extends AbstractController
{
    public function __construct(private GusApiService $gusApiService)
    {
    }

    public function getCompanyDataFromNIPAction(Request $request)
    {
        $nip = $request->request->get('nip');

        if (null === $nip) {
            throw new BadRequestException('Należy podać parametr nip');
        }

        /** @var string $nip */
        $response = $this->gusApiService->getDataFromNip($nip);

        if (GusDataResponse::ERROR_STATUS === $response->status) {
            return new JsonResponse($response, 400);
        }

        return new JsonResponse($response);
    }
}
