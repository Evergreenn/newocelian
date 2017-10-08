<?php

declare(strict_types=1);

namespace AppBundle\Action;

use AppBundle\Domain\Resource\ResourceManager;
use AppBundle\Entity\Resource;
use AppBundle\Repository\ResourceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
     * GetAvailableResources constructor.
     *
     * @param ResourceRepository $resourceRepository
     * @param ResourceManager    $resourceManager
     */
    public function __construct(ResourceRepository $resourceRepository, ResourceManager $resourceManager)
    {
        $this->resourceRepository = $resourceRepository;
        $this->resourceManager = $resourceManager;
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
     * @return array
     */
    public function __invoke(Request $request)
    {
        $body = $request->getContent();
        $date = \json_decode($body, true);
        if (!isset($date['date'])) {
            throw new BadRequestHttpException();
        }

        $datetime = new \DateTime($date['date']);
        $resources = $this->resourceRepository->findBy(['date' => $datetime, 'appointment' => null]);

        if (empty($resources)) {
            $resources = $this->resourceManager->createResources($datetime);
        }

        return $resources;
    }
}
