<?php

namespace AppBundle\Domain\Resource;

use AppBundle\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ResourceManager.
 */
final class ResourceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ResourceManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param \DateTime $date
     *
     * @return array
     */
    public function createResources(\DateTime $date): array
    {
        $resources = [];
        foreach (Resource::TYPES as $type) {
            $resources = $this->createResource($date, $type, $resources);
        }

        return $resources;
    }

    /**
     * @param \DateTime $date
     * @param string    $type
     * @param array     $baseResources
     *
     * @return array
     */
    public function createResource(\DateTime $date, string $type, array $baseResources = []): array
    {
        $count = 0;
        $reflection = new \ReflectionClass(Resource::class);
        $constant = $reflection->getConstant(strtoupper($type));
        while ($count < $constant) {
            $resource = new Resource();
            $resource->setType($type);
            $resource->setDate($date);
            $baseResources[] = $resource;
            $this->em->persist($resource);
            $count++;
        }

        $this->em->flush();

        return $baseResources;
    }
}
