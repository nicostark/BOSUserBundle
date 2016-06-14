<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RolePermission
 *
 * @ORM\Table(name="role_permission")
 * @ORM\Entity(repositoryClass="BOS\UserBundle\Repository\RolePermissionRepository")
 */
class RolePermission
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
     * @var Role
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="rolePermission")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;    
    
    /**
     * @var Permission
     * @ORM\ManyToOne(targetEntity="Permission", inversedBy="rolePermission")
     * @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     */
    private $permission;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permission = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set permission
     *
     * @param \BOS\UserBundle\Entity\Permission $permission
     *
     * @return RolePermission
     */
    public function setPermission(\BOS\UserBundle\Entity\Permission $permission = null)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return \BOS\UserBundle\Entity\Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set role
     *
     * @param \BOS\UserBundle\Entity\Role $role
     *
     * @return RolePermission
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
