<?php

declare(strict_types=1);

namespace AppBundle\Security\Generator;

/**
 * Class TokenGenerator.
 */
class TokenGenerator
{
    /**
     * @param int $length
     *
     * @return string
     */
    public function generateToken(int $length): string
    {
        $bytes = \random_bytes($length);

        return \rtrim(\strtr(\base64_encode($bytes), '+/', '-_'), '=');
    }
}
