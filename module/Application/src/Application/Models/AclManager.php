<?php

namespace Application\Models;

use \Zend\Permissions\Acl\Acl;
use Blog\Entity\User;

class AclManager extends Acl
{
    private $identity;
    private $rapporteurGs;

    /**
     * @param mixed $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param mixed $rapporteurGs
     */
    public function setRapporteurGs($rapporteurGs)
    {
        $this->rapporteurGs = $rapporteurGs;
    }

    /**
     * @return mixed
     */
    public function getRapporteurGs()
    {
        return $this->rapporteurGs;
    }

    public function isUserAllowed($resource = null, $privilege = null)
    {
        $role = 'guest';
        /** @var User $user */
        $user = $this->identity;

        if (!is_null($user)) {
            $role = 'writer';
        }

        return parent::isAllowed($role, $resource, $privilege);
    }
}