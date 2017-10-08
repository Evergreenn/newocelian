<?php

declare(strict_types=1);

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\User;
use AppBundle\Security\Generator\TokenGenerator;
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
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * UserSubscriber constructor.
     *
     * @param EncoderFactoryInterface $encoderFactory
     * @param TokenGenerator          $tokenGenerator
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, TokenGenerator $tokenGenerator)
    {
        $this->encoderFactory = $encoderFactory;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                ['encodePassword', EventPriorities::PRE_WRITE], ['generateToken', EventPriorities::PRE_WRITE],
            ],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function encodePassword(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $request = $event->getRequest();
        if (is_array($user)) {
            return;
        }

        if (!$user instanceof User && !\in_array($request->getMethod(), [Request::METHOD_POST, Request::METHOD_PUT])) {
            return;
        }

        if (null === $user->getPlainPassword()) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $encoded = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($encoded);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function generateToken(GetResponseForControllerResultEvent $event)
    {
        $user = $event->getControllerResult();
        $request = $event->getRequest();
        if (is_array($user)) {
            return;
        }

        if (!$user instanceof User && !$request->isMethod(Request::METHOD_POST)) {
            return;
        }

        $token = $this->tokenGenerator->generateToken(User::TOKEN_LENGTH);
        $user->setApiToken($token);
    }
}
