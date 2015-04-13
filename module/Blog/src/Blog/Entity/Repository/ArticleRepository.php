<?php

namespace Blog\Entity\Repository;

use Blog\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function findByTags($tags)
    {
        $articles = new ArrayCollection();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('Blog\Entity\Article', 'a')
            ->leftJoin('a.tags', 't')
            ->where('t.title IN(?1)')
            ->andWhere('a.status = ?2')
            ->orderBy('a.date', 'DESC')
            ->setParameter(1, $tags)
            ->setParameter(2, Article::STATUS_ONLINE);

        $result = $qb->getQuery()->getResult();

        foreach ($result as $article) {
            $articles->add($article);
        }
        return $articles;
    }

    public function findByCategories($categories)
    {
        $articles = new ArrayCollection();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('Blog\Entity\Article', 'a')
            ->where('a.category IN(?1)')
            ->andWhere('a.status = ?2')
            ->orderBy('a.date', 'DESC')
            ->setParameter(1, $categories)
            ->setParameter(2, Article::STATUS_ONLINE);

        $result = $qb->getQuery()->getResult();

        foreach ($result as $article) {
            $articles->add($article);
        }
        return $articles;
    }

    public function getAllYears($categories = null)
    {
        $years = array();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('Blog\Entity\Article', 'a')
            ->andWhere('a.status = ?1')
            ->setParameter(1, Article::STATUS_ONLINE)
            ->orderBy('a.date', 'DESC')
            ->groupBy('a.year');

        if (sizeof($categories)) {
            $qb->andWhere('a.category IN(?2)')
                ->setParameter(2, $categories);
        }

        $result = $qb->getQuery()->getResult();

        /** @var Article $article */
        foreach ($result as $article) {
            array_push($years, $article->getYear());
        }
        return $years;
    }

    public function getAllCategories()
    {
        $categories = array();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('Blog\Entity\Article', 'a')
            ->andWhere('a.status = ?1')
            ->setParameter(1, Article::STATUS_ONLINE)
            ->orderBy('a.category', 'ASC')
            ->groupBy('a.category');

        $result = $qb->getQuery()->getResult();

        /** @var Article $article */
        foreach ($result as $article) {
            array_push($categories, $article->getCategory());
        }
        return $categories;
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function getRecentArticleFrom($article)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('Blog\Entity\Article', 'a')
            ->where('a.date > ?1')
            ->andWhere('a.status = ?2')
            ->setParameter(1, $article->getDate())
            ->setParameter(2, Article::STATUS_ONLINE)
            ->orderBy('a.date', 'ASC')
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();
        if (sizeof($result)) {
            return $result[0];
        } else {
            return null;
        }
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function getOldArticleFrom($article)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
            ->from('Blog\Entity\Article', 'a')
            ->where('a.date < ?1')
            ->andWhere('a.status = ?2')
            ->setParameter(1, $article->getDate())
            ->setParameter(2, Article::STATUS_ONLINE)
            ->orderBy('a.date', 'DESC')
            ->setMaxResults(1);
        $result = $qb->getQuery()->getResult();
        if (sizeof($result)) {
            return $result[0];
        } else {
            return null;
        }
    }
}
