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
}
