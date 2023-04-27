<?php

declare(strict_types=1);

namespace Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Controller;

use Lemisoft\SyliusInvoiceRequestPlugin\Presentation\Form\Type\GusGetDataByNipType;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\GusApiService;
use Lemisoft\SyliusInvoiceRequestPlugin\Service\Gus\Model\GusDataResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[AsController]
final class GusGetDataByNipAction extends AbstractController
{
    public const ROUTE_NAME = 'lemisoft_invoice_request_gus_get_data_from_nip';

    public function __construct(private GusApiService $gusApiService, private SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $form = $this->createForm(GusGetDataByNipType::class);

        if ($form->handleRequest($request)->isValid()) {
            $nip = (string)$form->get('nip')->getData();
            $response = $this->gusApiService->getDataFromNip($nip);

            if (GusDataResponse::SUCCESS_STATUS === $response->status) {
                $jsonData = $this->serializer->serialize($response, 'json');

                return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
            }

            $form->get('nip')->addError(new FormError('lsirp.form.gus_nip_not_found'));
        }

        $jsonErrors = $this->serializer->serialize($form, 'json');

        return new JsonResponse($jsonErrors, Response::HTTP_BAD_REQUEST, [], true);
    }
}
