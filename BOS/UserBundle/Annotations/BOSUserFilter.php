<?php
namespace BOS\UserBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * 
 * @author nbullorini
 * @Annotation
 * @Target("METHOD")
 */
class BOSUserFilter extends Annotation
{
	public $loginRequired = false;
	public $roles = "";
}