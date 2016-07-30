<?php

namespace CoreBundle\Model;

use CoreBundle\Model\om\BaseEmpAcc;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;


class EmpAcc extends BaseEmpAcc implements UserInterface
{
	/**
    * @var string $salt
    *
    * @ORM\Column(name="salt", type="string", length=255)
    */
    private $salt;

    /**
    * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
    * @ORM\JoinTable(name="user_roles")
    */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
    }

    /**
    * methods for the UserInterface
    *@var \Doctrine\Common\Collections\ArrayCollection
    */
    public function getRoles()
    {
        if ($this->getRole() == "ADMIN") {
            return array('ROLE_ADMIN');
        } else {
            return array('ROLE_ANALYST');
        }
    }

    /**
    * Set salt
    *
    * @param string $salt
    */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
    * Get salt
    *
    * @return string
    */
    public function getSalt()
    {
        return $this->salt;
    }

    public function eraseCredentials()
    {

    }

    public function serialize()
    {
    return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list (
        $this->id,
        $this->username,
        $this->password,
        ) = unserialize($serialized);
    }
}