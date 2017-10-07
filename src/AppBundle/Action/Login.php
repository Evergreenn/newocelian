<?php

namespace AppBundle\Action;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class Login.
 */
final class Login
{
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
        return new JsonResponse('Success');
    }
}
