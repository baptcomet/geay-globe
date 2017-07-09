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
    const MOSAIQUE_OFF = 0;
    const MOSAIQUE_ON = 1;

    const CATEGORY_RESTAURANT = '1';
    const CATEGORY_TRIP = '2';
    const CATEGORY_PLACE = '3';
    const CATEGORY_VIDEO = '4';

    public static $categories = array(
        self::CATEGORY_RESTAURANT => 'Restaurant',
        self::CATEGORY_TRIP => 'Voyage',
        self::CATEGORY_PLACE => 'Place To Be',
        self::CATEGORY_VIDEO => 'Vidéo',
    );

    public static $presentations = array(
        self::MOSAIQUE_OFF => 'Fil',
        self::MOSAIQUE_ON => 'Mosaïque',
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

    /** @ORM\Column(name="text", type="text", nullable=true) */
    protected $text;

    /**
     * @ORM\OneToMany(targetEntity="Picture", mappedBy="article", cascade={"persist"})
     * @ORM\OrderBy({"rank" = "ASC"})
     */
    protected $pictures;

    /** @ORM\Column(name="category", type="integer", nullable=true) */
    protected $category;

    /** @ORM\Column(name="presentation", type="integer", nullable=true) */
    protected $presentation;

    /** @ORM\Column(name="date", type="datetime", nullable=false) */
    protected $date;

    /** @ORM\Column(name="day", type="string", nullable=false) */
    protected $day;

    /** @ORM\Column(name="month", type="integer", nullable=false) */
    protected $month;

    /** @ORM\Column(name="year", type="string", nullable=false) */
    protected $year;

    /** @ORM\Column(name="youtube", type="string", nullable=true) */
    protected $youtube;


    public function __construct()
    {
        $this->setDate(new \DateTime());
        $this->pictures = new ArrayCollection();
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
     * @return string
     */
    public function getCategoryLib()
    {
        return self::$categories[$this->category];
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
     * @return int
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param int $presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * @return bool
     */
    public function isMosaique() {
        return $this->getPresentation() == self::MOSAIQUE_ON;
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
     * @return string
     */
    public function getDateFullString()
    {
        if ($this->date) {
            return $this->day . ' ' . $this->getMonthName() . ' ' . $this->year;
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
     * @return Article $this
     */
    public function updateDate()
    {
        $dateString = $this->getYear() . '-' . $this->getMonthNumber() . '-' . $this->getDay();
        $this->date = DateTime::createFromFormat('Y-m-d', $dateString);
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
     * @return int
     */
    public function getMonthNumber()
    {
        return Calendar::getStaticMonthNumbers()[$this->month];
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
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
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
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param mixed $youtube
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return Picture
     */
    public function getMainPicture()
    {
        if ($this->pictures->count()) {
            return $this->pictures->first();
        }
        return null;
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
     * @return ArrayCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @return ArrayCollection
     */
    public function getPicturesWithoutMain()
    {
        $pictures = $this->pictures;
        $pictures->removeElement($this->getMainPicture());
        return $pictures;
    }

    /**
     * @param ArrayCollection $pictures
     * @return Article $this
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
        return $this;
    }

    /**
     * @param ArrayCollection $pictures
     * @return Article $this
     */
    public function addPictures($pictures)
    {
        foreach ($pictures as $picture) {
            $this->addPicture($picture);
        }
        return $this;
    }

    /**
     * @param Picture $picture
     * @return Article $this
     */
    public function addPicture($picture)
    {
        $this->pictures->add($picture);
        $picture->setArticle($this);
        return $this;
    }

    /**
     * @param ArrayCollection $pictures
     * @return Article $this
     */
    public function removePictures($pictures)
    {
        foreach ($pictures as $picture) {
            $this->removePicture($picture);
        }
        return $this;
    }

    /**
     * @param Picture $picture
     * @return Article $this
     */
    public function removePicture($picture)
    {
        $this->pictures->removeElement($picture);
        return $this;
    }

    /**
     * @return array
     */
    public static function getCategoryKeys()
    {
        return array_keys(self::$categories);
    }
}
