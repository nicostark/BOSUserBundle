<?php	

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BOSUser
 *
 * @ORM\Table(name="bos_user")
 * @ORM\Entity(repositoryClass="BOS\UserBundle\Entity\BOSUserRepository")
 */
class BOSUser extends BOSUserBase
{

}
