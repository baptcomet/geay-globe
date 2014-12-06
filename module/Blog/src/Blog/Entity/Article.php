<?php

namespace Blog\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Feed\Writer\Writer;

/** @ORM\Entity
 * @ORM\Table(name="article")
 */
class Article extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles", cascade={"persist"})
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", unique=false, nullable=true)
     */
    protected $tags;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article", fetch="EXTRA_LAZY")
     */
    protected $comments;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles"), fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="writer_id", referencedColumnName="id", nullable=true)
     */
    protected $writer;

    /** @ORM\Column(name="title", type="string", nullable=false) */
    protected $title;

    /** @ORM\Column(name="subtitle", type="string", nullable=true) */
    protected $subtitle;

    /** @ORM\Column(name="text", type="text", nullable=false) */
    protected $text;

    /** @ORM\Column(name="thumbnail", type="string", nullable=true) */
    protected $thumbnail;

    /** @ORM\Column(name="photo", type="string", nullable=true) */
    protected $photo;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $subtitle
     * @return $this
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * @param bool $newLine
     * @return mixed
     */
    public function getCompleteTitle($newLine = false)
    {
        $title = $this->getTitle();
        if ($this->getSubtitle()) {
            if ($newLine) {
                $title .= ' : <br/>' . $this->getSubtitle();
            } else {
                $title .= ' : ' . $this->getSubtitle();
            }
        }
        return $title;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
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
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
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
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return Writer
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param User $writer
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
    }

    public function exchangeArrayForm($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : self::STATUS_OFFLINE;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->text = (!empty($data['text'])) ? $data['text'] : null;
        $this->photo = (!empty($data['photo'])) ? $data['photo'] : null;
        $this->thumbnail = (!empty($data['thumbnail'])) ? $data['thumbnail'] : null;

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
        $data = get_object_vars($this);

        /** @var DateTime $dateData */
        $dateData = $data['date'];
        $date = $dateData->format('d/m/Y');
        $data['date'] = $date;

        return $data;
    }
}
