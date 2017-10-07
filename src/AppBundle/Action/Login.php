<?php

namespace AppBundle\Action;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Login.
 */
final class Login
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Login constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route(
     *     path="/login",
     *     name="login"
     * )
     *
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function __invoke()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return new JsonResponse([
            'message'   => 'Login success',
            'api_token' => $user->getApiToken(),
        ]);
    }
}
