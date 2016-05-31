<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="BOS\UserBundle\Repository\RoleRepository")
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
     * @ORM\ManyToOne(targetEntity="System", inversedBy="system")
     * @ORM\JoinColumn(name="id_system", referencedColumnName="id")
     */
    
    private $system;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    private $roles;
    
    /**
     *@var Permission
     * 
     *@ORM\OneToMany(targetEntity="Permission", mappedBy="permission")
     *@ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     **/
    
    private $Permissions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add role
     *
     * @param \BOS\UserBundle\Entity\User $role
     *
     * @return Role
     */
    public function addRole(\BOS\UserBundle\Entity\User $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \BOS\UserBundle\Entity\User $role
     */
    public function removeRole(\BOS\UserBundle\Entity\User $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add permission
     *
     * @param \BOS\UserBundle\Entity\Permission $permission
     *
     * @return Role
     */
    public function addPermission(\BOS\UserBundle\Entity\Permission $permission)
    {
        $this->Permissions[] = $permission;

        return $this;
    }

    /**
     * Remove permission
     *
     * @param \BOS\UserBundle\Entity\Permission $permission
     */
    public function removePermission(\BOS\UserBundle\Entity\Permission $permission)
    {
        $this->Permissions->removeElement($permission);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->Permissions;
    }
}
