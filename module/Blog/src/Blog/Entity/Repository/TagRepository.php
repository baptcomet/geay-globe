<?php

namespace Blog\Entity\Repository;

use Blog\Entity\Article;
use Blog\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findAllActive()
    {
        $tags = new ArrayCollection();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
            ->from('Blog\Entity\Tag', 't')
            ->leftJoin('t.articles', 'a')
            ->where('t.status  = ?1')
            ->andWhere('a.status = ?2')
            ->orderBy('t.title', 'asc')
            ->setParameter(1, Tag::STATUS_ONLINE)
            ->setParameter(2, Article::STATUS_ONLINE);

        $result = $qb->getQuery()->getResult();

        foreach ($result as $tag) {
            $tags->add($tag);
        }
        return $tags;
    }
}
