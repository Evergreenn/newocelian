<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User.
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("email", message="Cette adresse mail est déjà prise.")
 *
 * @ApiResource(
 *     attributes={
 *         "normalization_context" = {"groups" = {"read"}},
 *         "denormalization_context" = {"groups" = {"write"}}
 *     },
 * )
 */
class User implements UserInterface, \Serializable
{
    const DEFAULT_ROLE = 'ROLE_USER';
    const TOKEN_LENGTH = 16;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Email(message="L'adresse mail n'est pas valide.")
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Groups({"read", "write"})
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min="5",
     *     max="32",
     *     minMessage="Le mot de passe doit faire 5 charactères minimum.",
     *     maxMessage="Le mot de passe doit faire 32 charactères maximum."
     * )
     *
     * @Groups({"write"})
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     *
     * @Groups({"read"})
     */
    private $roles = [];

    /**
     * @var string
     *
     * @ORM\Column(name="api_token", type="string", length=255)
     */
    private $apiToken;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles = [])
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return \serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
        ) = \unserialize($serialized);
    }

    /**
     * Set apiToken.
     *
     * @param string $apiToken
     *
     * @return User
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * Get apiToken.
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }
}
