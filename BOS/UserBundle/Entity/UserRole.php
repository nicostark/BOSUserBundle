<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * UserRole
 *
 * @ORM\Table(name="user_role", uniqueConstraints={@UniqueConstraint(name="user_roles_idx", columns={"user_id", "role_id"})})
 * @ORM\Entity(repositoryClass="BOS\UserBundle\Repository\UserRoleRepository")
 */
class UserRole
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
     * @var BOSUser
     * @ORM\ManyToOne(targetEntity="BOSUser", inversedBy="userroles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="user")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \BOS\UserBundle\Entity\BOSUser $user
     *
     * @return UserRole
     */
    public function setUser(\BOS\UserBundle\Entity\BOSUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BOS\UserBundle\Entity\BOSUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set role
     *
     * @param \BOS\UserBundle\Entity\Role $role
     *
     * @return UserRole
     */
    public function setRole(\BOS\UserBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \BOS\UserBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
