<?php

namespace Blog\Entity;

use Blog\Entity\Article;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="picture")
 */
class Picture
{
    const FOLDER = 'pictures';
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_CENTER = 'center';

    public static $positions = array(
        self::POSITION_LEFT => 'Gauche',
        self::POSITION_RIGHT => 'Droite',
        self::POSITION_CENTER => 'Centrée',
    );

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="legend", type="string", length=256) */
    protected $legend;

    /** @ORM\Column(name="filename", type="string", length=250) */
    protected $filename;

    /** @var String nom temporaire du fichier sauvegardé par PHP */
    protected $tmpName;

    /** @ORM\Column(name="position", type="string", nullable=false) */
    protected $position;

    /** @ORM\Column(name="text", type="text", nullable=true) */
    protected $text;

    /** @ORM\Column(name="text_position", type="text", nullable=false) */
    protected $textPosition;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="pictures", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $article;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * @param string $legend
     * @return Picture $this
     */
    public function setLegend($legend)
    {
        $this->legend = $legend;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return 'thumbnail_' . $this->filename;
    }

    /**
     * Sauvegarde également le fichier temporaire créé par PHP si le type est un array
     * @param array|string $file
     * @return Picture $this
     */
    public function setFilename($file)
    {
        if (is_array($file) && isset($file['name']) && isset($file['tmp_name'])) {
            $this->tmpName = $file['tmp_name'];
            $file = $file['name'];
        }
        $this->filename = $file;
        return $this;
    }

    /**
     * Retourne le fichier temporaire créé par PHP
     * @return String
     */
    public function getTempFilename()
    {
        return $this->tmpName;
    }

    /**
     * @param string $position
     * @return Picture $this
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getPositionLabel()
    {
        return self::$positions[$this->position];
    }

    /**
     * @param string $text
     * @return Picture $this
     */
    public function setText($text)
    {
        $this->text = $text;
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
     * @param string $textPosition
     * @return Picture $this
     */
    public function setTextPosition($textPosition)
    {
        $this->textPosition = $textPosition;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextPosition()
    {
        return $this->textPosition;
    }

    /**
     * @return string
     */
    public function getTextPositionLabel()
    {
        return self::$positions[$this->textPosition];
    }

    /**
     * @param string $tmpName
     * @return Picture $this
     */
    public function setTmpName($tmpName)
    {
        $this->tmpName = $tmpName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return Picture $this
     */
    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return Article::BASE_UPLOAD_PATH . $this->getArticle()->getId() . '/' . self::FOLDER . '/' . $this->filename;
    }

    /**
     * Retourne la liste des extensions autorisés pour les illustrations
     * @return array
     */
    public static function getStaticAuthorisedExtensionList()
    {
        return array(
            'jpg',
            'jpeg',
            'gif',
            'bmp',
            'png',
        );
    }
}
