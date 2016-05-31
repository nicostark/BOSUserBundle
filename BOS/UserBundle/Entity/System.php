<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * System
 *
 * @ORM\Table(name="system")
 * @ORM\Entity(repositoryClass="BOS\UserBundle\Repository\SystemRepository")
 */
class System
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
     * @ORM\Column(name="name", type="string", length=20, unique=true)
     */
    private $name;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="systems",cascade={"persist"})
     */
    private $users;
  
        
    /**
     *@var Role
     * 
     *@ORM\OneToMany(targetEntity="Role", mappedBy="role")
     *@ORM\JoinColumn(name="id_role", referencedColumnName="id")
     **/
    private $roles;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return System
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
     * Add user
     *
     * @param \BOS\UserBundle\Entity\User $user
     *
     * @return System
     */
    public function addUser(\BOS\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \BOS\UserBundle\Entity\User $user
     */
    public function removeUser(\BOS\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add role
     *
     * @param \BOS\UserBundle\Entity\Role $role
     *
     * @return System
     */
    public function addRole(\BOS\UserBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \BOS\UserBundle\Entity\Role $role
     */
    public function removeRole(\BOS\UserBundle\Entity\Role $role)
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
}
