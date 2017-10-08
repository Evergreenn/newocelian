<?php

declare(strict_types=1);

namespace AppBundle\Action;

use AppBundle\Domain\Resource\ResourceManager;
use AppBundle\Entity\Resource;
use AppBundle\Repository\ResourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class GetAvailableResources.
 */
final class GetAvailableResources
{
    /**
     * @var ResourceRepository
     */
    private $resourceRepository;

    /**
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * GetAvailableResources constructor.
     *
     * @param ResourceRepository  $resourceRepository
     * @param ResourceManager     $resourceManager
     * @param SerializerInterface $serializer
     */
    public function __construct(ResourceRepository $resourceRepository, ResourceManager $resourceManager, SerializerInterface $serializer)
    {
        $this->resourceRepository = $resourceRepository;
        $this->resourceManager = $resourceManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route(
     *     path="/available_resources",
     *     name="available_resources",
     *     defaults={"_api_resource_class" = Resource::class, "_api_collection_operation_name" = "available_resources"}
     * )
     *
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $body = $request->getContent();
        $date = \json_decode($body, true);
        if (!isset($date['date'])) {
            throw new BadRequestHttpException('Date invalide');
        }

        $datetime = new \DateTime($date['date']);
        $resources = $this->resourceRepository->findBy(['date' => $datetime, 'appointment' => null]);

        if (empty($resources)) {
            $resources = $this->resourceManager->createResources($datetime);
        }

        $countBike = 0;
        $countCarpet = 0;
        $countEllipticBike = 0;
        $resourcesData = [];
        foreach ($resources as $resource) {
            switch ($resource->getType()) {
                case 'bike':
                    $resourcesData['bike'][] = $resource;
                    $countBike++;
                    break;
                case 'carpet':
                    $resourcesData['carpet'][] = $resource;
                    $countCarpet++;
                    break;
                case 'elliptic_bike':
                    $resourcesData['elliptic_bike'][] = $resource;
                    $countEllipticBike++;
                    break;
                default:
                    continue;
            }
        }

        $data = [
            'resources' => $resourcesData,
            'countBike' => $countBike,
            'countCarpet' => $countCarpet,
            'countEllipticBike' => $countEllipticBike,
        ];

        $response =  new JsonResponse();
        $response->setData($this->serializer->serialize($data, 'json'));

        return $response;
    }
}
