<?php

namespace AppBundle\Action;

use AppBundle\Repository\ResourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Resource;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class GetAvailableResources.
 */
class GetAvailableResources
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ResourceRepository
     */
    private $resourceRepository;

    /**
     * GetAvailableResources constructor.
     *
     * @param SerializerInterface $serializer
     * @param ResourceRepository  $resourceRepository
     */
    public function __construct(SerializerInterface $serializer, ResourceRepository $resourceRepository)
    {
        $this->serializer = $serializer;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @Route(
     *     path="/available_resources",
     *     name="available_resources",
     *     defaults={"_api_resource_class" = Resource::class, "_api_collection_operation_name" = "available_resources"}
     * )
     *
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return array
     */
    public function __invoke(Request $request)
    {
        $body = $request->getContent();
        $date = json_decode($body, true);
        if (!isset($date['date'])) {
            throw new BadRequestHttpException();
        }

        $datetime = new \DateTime($date['date']);
        $resources = $this->resourceRepository->findBy(['date' => $datetime]);

        return $resources;
    }
}
