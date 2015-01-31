<?php

namespace Blog\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Blog\Entity\Repository\ArticleRepository")
 * @ORM\Table(name="article")
 */
class Article extends AbstractEntity
{
    const PHOTO_FOLDER = './public/img/articles/';
    const PHOTO_POSITION_LEFT = 'left';
    const PHOTO_POSITION_RIGHT = 'right';
    const PHOTO_POSITION_CENTER = 'center';

    public static $categories = array(
        '1' => 'Restaurant',
        '2' => 'Voyage',
        '3' => 'Place',
    );

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

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

    /** @ORM\Column(name="category", type="integer", nullable=true) */
    protected $category;

    /** @ORM\Column(name="thumbnail", type="string", nullable=true) */
    protected $thumbnail;

    /** @ORM\Column(name="photo", type="string", nullable=true) */
    protected $photo;

    /** @ORM\Column(name="photo_position", type="string", nullable=false) */
    protected $photoPosition;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;


    public function __construct()
    {
        $this->date = new \DateTime();
        $this->photoPosition = self::PHOTO_POSITION_LEFT;
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return Article $this
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
     * @return Article $this
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
     * @return Article $this
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
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param integer $category
     * @return Article $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
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
     * @return Article $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDateString()
    {
        if ($this->date) {
            return $this->getDate()->format('d/m/Y');
        } else {
            return '-';
        }
    }

    /**
     * @param mixed $date
     * @return Article $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     * @return Article $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoPosition()
    {
        return $this->photoPosition;
    }

    /**
     * @param string $photoPosition
     * @return Article $this
     */
    public function setPhotoPosition($photoPosition)
    {
        $this->photoPosition = $photoPosition;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getTagsString()
    {
        $tags = array();
        /** @var Tag $tagObject */
        foreach ($this->tags as $tagObject) {
            array_push($tags, $tagObject->getTitle());
        }
        return implode(' ', $tags);
    }

    /**
     * @param mixed $tags
     * @return Article $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
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
     * @return Article $this
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
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
     * @return Article $this
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return User
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param User $writer
     * @return Article $this
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
        return $this;
    }
}
