<?php

namespace Blog\Entity;

use Blog\Model\Calendar;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity(repositoryClass="Blog\Entity\Repository\ArticleRepository")
 * @ORM\Table(name="article")
 */
class Article extends AbstractEntity
{
    const PHOTO_FOLDER = './public/img/articles/';
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_CENTER = 'center';

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

    /** @ORM\Column(name="text1", type="text", nullable=false) */
    protected $text1;

    /** @ORM\Column(name="text2", type="text", nullable=false) */
    protected $text2;

    /** @ORM\Column(name="text3", type="text", nullable=false) */
    protected $text3;

    /** @ORM\Column(name="text_position1", type="text", nullable=false) */
    protected $textPosition1;

    /** @ORM\Column(name="text_position2", type="text", nullable=false) */
    protected $textPosition2;

    /** @ORM\Column(name="text_position3", type="text", nullable=false) */
    protected $textPosition3;

    /** @ORM\Column(name="category", type="integer", nullable=true) */
    protected $category;

    /** @ORM\Column(name="thumbnail1", type="string", nullable=true) */
    protected $thumbnail1;

    /** @ORM\Column(name="photo1", type="string", nullable=true) */
    protected $photo1;

    /** @ORM\Column(name="thumbnail2", type="string", nullable=true) */
    protected $thumbnail2;

    /** @ORM\Column(name="photo2", type="string", nullable=true) */
    protected $photo2;

    /** @ORM\Column(name="photo_position1", type="string", nullable=false) */
    protected $photoPosition1;

    /** @ORM\Column(name="photo_position2", type="string", nullable=false) */
    protected $photoPosition2;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;

    /** @ORM\Column(name="day", type="string", nullable=false) */
    protected $day;

    /** @ORM\Column(name="month", type="integer", nullable=false) */
    protected $month;

    /** @ORM\Column(name="year", type="string", nullable=false) */
    protected $year;


    public function __construct()
    {
        $this->setDate(new \DateTime());
        $this->photoPosition1 = self::POSITION_LEFT;
        $this->photoPosition2 = self::POSITION_LEFT;
        $this->textPosition1 = self::POSITION_LEFT;
        $this->textPosition2 = self::POSITION_LEFT;
        $this->textPosition3 = self::POSITION_LEFT;
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
     * @param \DateTime $date
     * @return Article $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        $this->day = $date->format('d');
        $this->month = (int)$date->format('m');
        $this->year = $date->format('Y');
        return $this;
    }

    /**
     * @param string $day
     * @return Article $this
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $month
     * @return Article $this
     */
    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getMonthName()
    {
        return Calendar::getStaticMonthNames()[$this->month];
    }

    /**
     * @return string
     */
    public function getMonthShortName()
    {
        return Calendar::getStaticMonthShortNames()[$this->month];
    }

    /**
     * @param string $year
     * @return Article $this
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return string
     */
    public function getPhoto1()
    {
        return $this->photo1;
    }

    /**
     * @param string $photo
     * @return Article $this
     */
    public function setPhoto1($photo)
    {
        $this->photo1 = $photo;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto2()
    {
        return $this->photo2;
    }

    /**
     * @param string $photo
     * @return Article $this
     */
    public function setPhoto2($photo)
    {
        $this->photo2 = $photo;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoPosition1()
    {
        return $this->photoPosition1;
    }

    /**
     * @param string $photoPosition
     * @return Article $this
     */
    public function setPhotoPosition1($photoPosition)
    {
        $this->photoPosition1 = $photoPosition;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhotoPosition2()
    {
        return $this->photoPosition2;
    }

    /**
     * @param string $photoPosition
     * @return Article $this
     */
    public function setPhotoPosition2($photoPosition)
    {
        $this->photoPosition2 = $photoPosition;
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
    public function getText1()
    {
        return $this->text1;
    }

    /**
     * @param mixed $text
     * @return Article $this
     */
    public function setText1($text)
    {
        $this->text1 = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getText2()
    {
        return $this->text2;
    }

    /**
     * @param mixed $text
     * @return Article $this
     */
    public function setText2($text)
    {
        $this->text2 = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getText3()
    {
        return $this->text3;
    }

    /**
     * @param mixed $text
     * @return Article $this
     */
    public function setText3($text)
    {
        $this->text3 = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThumbnail1()
    {
        return $this->thumbnail1;
    }

    /**
     * @param mixed $thumbnail
     * @return Article $this
     */
    public function setThumbnail1($thumbnail)
    {
        $this->thumbnail1 = $thumbnail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThumbnail2()
    {
        return $this->thumbnail2;
    }

    /**
     * @param mixed $thumbnail
     * @return Article $this
     */
    public function setThumbnail2($thumbnail)
    {
        $this->thumbnail2 = $thumbnail;
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

    /**
     * @return mixed
     */
    public function getTextPosition1()
    {
        return $this->textPosition1;
    }

    /**
     * @param mixed $textPosition1
     */
    public function setTextPosition1($textPosition1)
    {
        $this->textPosition1 = $textPosition1;
    }

    /**
     * @return mixed
     */
    public function getTextPosition2()
    {
        return $this->textPosition2;
    }

    /**
     * @param mixed $textPosition2
     */
    public function setTextPosition2($textPosition2)
    {
        $this->textPosition2 = $textPosition2;
    }

    /**
     * @return mixed
     */
    public function getTextPosition3()
    {
        return $this->textPosition3;
    }

    /**
     * @param mixed $textPosition3
     */
    public function setTextPosition3($textPosition3)
    {
        $this->textPosition3 = $textPosition3;
    }
}
