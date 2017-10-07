<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserSubscriber.
 */
class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * UserSubscriber constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function encodePassword(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $request = $event->getRequest();
        if (!$user instanceof User && !in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT])) {
            return;
        }

        if (null === $user->getPlainPassword()) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $encoded = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($encoded);
    }
}
