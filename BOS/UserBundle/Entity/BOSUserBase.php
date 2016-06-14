<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/** 
 * @author Nicolás Bullorini
 */

 abstract class BOSUserBase {
 	
 	/**
 	 * @var integer
 	 *
 	 * @ORM\Column(name="id", type="integer")
 	 * @ORM\Id
 	 * @ORM\GeneratedValue(strategy="AUTO")
 	 */
 	protected $id;
 	
 	/**
 	 * @var string
 	 *
 	 * @ORM\Column(name="username", type="string", length=255, unique=true)
 	 */
 	protected $username;
 	
 	/**
 	 * @var string
 	 *
 	 * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
 	 */
 	protected $email;
 	
 	/**
 	 * @var string
 	 *
 	 * @ORM\Column(name="password", type="string", length=1024)
 	 */
 	protected $password;
 	
 	/**
 	 * @var string
 	 *
 	 * @ORM\Column(name="salt", type="string", length=1024, nullable=true)
 	 */
 	protected $salt;
 	
        
        /**
         * @ORM\ManyToMany(targetEntity="System", inversedBy="users")
         * @ORM\JoinTable(name="users_systems")
         */
        protected $systems;

        /**
         * @var UserRole
         * 
         * @ORM\OneToMany(targetEntity="UserRole", mappedBy="user")
        */
        protected $userRole;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->systems = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userRole = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return BOSUser
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
     * Set email
     *
     * @param string $email
     *
     * @return BOSUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return BOSUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

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

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return BOSUser
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
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

    /**
     * Add system
     *
     * @param \BOS\UserBundle\Entity\System $system
     *
     * @return BOSUser
     */
    public function addSystem(\BOS\UserBundle\Entity\System $system)
    {
        $this->systems[] = $system;

        return $this;
    }

    /**
     * Remove system
     *
     * @param \BOS\UserBundle\Entity\System $system
     */
    public function removeSystem(\BOS\UserBundle\Entity\System $system)
    {
        $this->systems->removeElement($system);
    }

    /**
     * Get systems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSystems()
    {
        return $this->systems;
    }

    /**
     * Add userRole
     *
     * @param \BOS\UserBundle\Entity\UserRole $userRole
     *
     * @return BOSUser
     */
    public function addUserRole(\BOS\UserBundle\Entity\UserRole $userRole)
    {
        $this->userRole[] = $userRole;

        return $this;
    }

    /**
     * Remove userRole
     *
     * @param \BOS\UserBundle\Entity\UserRole $userRole
     */
    public function removeUserRole(\BOS\UserBundle\Entity\UserRole $userRole)
    {
        $this->userRole->removeElement($userRole);
    }

    /**
     * Get userRole
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserRole()
    {
        return $this->userRole;
    }
    
 }