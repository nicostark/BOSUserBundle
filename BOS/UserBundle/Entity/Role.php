<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="BOS\UserBundle\Entity\RoleRepository")
    */
class Role
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
     * @ORM\Column(name="name", type="string", length=20)
     */
    private $name;

    /**
     * @var System
     * @ORM\ManyToOne(targetEntity="System", inversedBy="roles")
     * @ORM\JoinColumn(name="system_id", referencedColumnName="id")
     */
    
    private $system;
    
     /**
     *@var UserRole
     * 
     *@ORM\OneToMany(targetEntity="UserRole", mappedBy="role")
     **/
    private $userRoles;

    /**
     *@var RolePermission
     * 
     *@ORM\OneToMany(targetEntity="RolePermission", mappedBy="role")
     **/
    private $rolePermissions;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->Permissions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set system
     *
     * @param \BOS\UserBundle\Entity\System $system
     *
     * @return Role
     */
    public function setSystem(\BOS\UserBundle\Entity\System $system = null)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return \BOS\UserBundle\Entity\System
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Add userRole
     *
     * @param \BOS\UserBundle\Entity\UserRole $userRole
     *
     * @return Role
     */
    public function addUserRole(\BOS\UserBundle\Entity\UserRole $userRole)
    {
        $this->userRoles[] = $userRole;

        return $this;
    }

    /**
     * Remove userRole
     *
     * @param \BOS\UserBundle\Entity\UserRole $userRole
     */
    public function removeUserRole(\BOS\UserBundle\Entity\UserRole $userRole)
    {
        $this->userRoles->removeElement($userRole);
    }

    /**
     * Get userRoles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Add rolePermission
     *
     * @param \BOS\UserBundle\Entity\RolePermission $rolePermission
     *
     * @return Role
     */
    public function addRolePermission(\BOS\UserBundle\Entity\RolePermission $rolePermission)
    {
        $this->rolePermissions[] = $rolePermission;

        return $this;
    }

    /**
     * Remove rolePermission
     *
     * @param \BOS\UserBundle\Entity\RolePermission $rolePermission
     */
    public function removeRolePermission(\BOS\UserBundle\Entity\RolePermission $rolePermission)
    {
        $this->rolePermissions->removeElement($rolePermission);
    }

    /**
     * Get rolePermissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRolePermissions()
    {
        return $this->rolePermissions;
    }
}
