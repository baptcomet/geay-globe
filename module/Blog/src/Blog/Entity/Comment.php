<?php

namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="text", type="text", nullable=false) */
    protected $text;

    /** @ORM\Column(name="signature", type="string", length=255, nullable=false) */
    protected $signature;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=false)
     */
    protected $article;

    public function __construct()
    {
    }

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Writer $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param mixed $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param array $data
     */
    public function exchangeArrayForm($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->text = (!empty($data['text'])) ? $data['text'] : null;
        $this->signature = (!empty($data['signature'])) ? $data['signature'] : null;

        if (!empty($data['date'])) {
            $date = explode('/', $data['date']);
            $data['date'] = $date[2] . '-' . $date[1] . '-' . $date[0];
            $dateTime = $data['date'] . ' 00:00:00';
            $this->date = new \DateTime($dateTime);
        } else {
            $this->date = new \DateTime('0000-00-00 00:00:00');
        }
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}