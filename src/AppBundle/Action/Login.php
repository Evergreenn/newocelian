<?php

namespace AppBundle\Action;

use AppBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Login.
 */
final class Login
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Login constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(
     *     path="/login",
     *     name="login"
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
        $user = $this->userRepository->findOneBy(['email' => $request->request->get('email')]);

        return new JsonResponse([
            'message'   => 'Login success',
            'api_token' => $user->getApiToken(),
            'email'     => $user->getEmail(),
            'id'        => $user->getId(),
        ]);
    }
}
