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
            ->setParameter(1, $tags)
            ->setParameter(2, Article::STATUS_ONLINE);

        $result = $qb->getQuery()->getResult();

        foreach ($result as $article) {
            $articles->add($article);
        }
        return $articles;
    }
}
