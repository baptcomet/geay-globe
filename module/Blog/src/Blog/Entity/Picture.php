<?php

namespace Blog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="picture")
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(name="legend", type="string", length=256) */
    protected $legend;

    /** @ORM\Column(name="flickr_url", type="string", length=250) */
    protected $flickrUrl;

    /** @ORM\Column(name="rank", type="integer") */
    protected $rank;

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
    public function getFlickrUrl()
    {
        return $this->flickrUrl;
    }

    /**
     * @param string $flickrUrl
     * @return Picture $this
     */
    public function setFlickrUrl($flickrUrl)
    {
        $this->flickrUrl = $flickrUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return Picture $this
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }
}
