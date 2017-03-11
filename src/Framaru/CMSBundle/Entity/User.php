<?php

namespace Framaru\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// allow to auth user from the DB
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Framaru\CMSBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=55)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Making sure the whenever we save a user into the DB, that the entered password is encrypted using Bcrypt
     *
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    // one of the methods needed in order to be able to use the user interface
    // return null, because we will be handling the hashing or the salting of the pwd ourselves using Bcryp
    public function getSalt()
    {
        return null;
    }

    // one of the methods needed in order to be able to use the user interface
    // returns an array of roles for the user, access control.
    public function getRoles()
    {
        return array("ROLE_USER");
    }

    // one of the methods needed in order to be able to use the user interface
    // Will be used behind the scenes by Symfony to erase sinsitive info about the user
    public function eraseCredentials()
    {

    }
}
