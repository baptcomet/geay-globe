<?php

namespace Blog\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *  name="user",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="nickname_unique", columns={"nickname"})}
 * )
 */
class User extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="nickname", type="string", length=255, nullable=false) */
    protected $nickname;

    /** @ORM\Column(name="password", type="string", length=255, nullable=false) */
    protected $password;

    /** @ORM\Column(name="firstName", type="string", length=255, nullable=false) */
    protected $firstName;

    /** @ORM\Column(name="lastName", type="string", length=255, nullable=false) */
    protected $lastName;

    /** @ORM\Column(name="email", type="string", length=255, nullable=true) */
    protected $email;

    /** @ORM\Column(name="description", type="text", nullable=true) */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="writer")
     */
    protected $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    /** @return mixed */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /** @return mixed */
    public function getLastName()
    {
        return $this->lastName;
    }

    /** @return mixed */
    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /** @return mixed */
    public function getEmail()
    {
        return $this->email;
    }

    /** @return mixed */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param int $id
     * @return User $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /** @param mixed $firstName
     * @return User $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /** @param mixed $lastName
     * @return User $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /** @param mixed $email
     * @return User $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


    /**
     * @param $password
     * @return User $this
     */
    public function setPassword($password)
    {
        $this->password = $this->cryptPassword($password);
        return $this;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return $this->password == $this->cryptPassword($password);
    }

    /**
     * @param mixed $articles
     * @return User $this
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param array $data
     */
    public function exchangeArrayForm($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->firstName = (!empty($data['firstName'])) ? $data['firstName'] : null;
        $this->lastName = (!empty($data['lastName'])) ? $data['lastName'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->nickname = (!empty($data['nickname'])) ? $data['nickname'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        if (isset($data['password']) && !empty($data['password'])) {
            $this->setPassword($data['password']);
        }
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getFullNameReverse()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

    public function cryptPassword($password)
    {
        return hash('md5', $password);
    }

    public static function generatePassword($length = 8)
    {
        $alphabetLowerCase = 'abcdefghijkmnopqrstuvwxyz'; // without l
        $alphabetUpperCase = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // without I, O
        $numeric = '123456789'; // without 0
        $specialChars = '!@#$%^&*()_-=+;:,.?';

        $chars = $alphabetLowerCase . $alphabetUpperCase . $numeric . $specialChars;

        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }
}