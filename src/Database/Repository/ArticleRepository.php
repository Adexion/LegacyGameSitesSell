<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Entity\User;

class ArticleRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getLastArticles(): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('article.image, article.title, article.text, article.shortText, user.username as author')
            ->from(Article::class, 'article')
            ->leftJoin(User::class, 'user', Join::WITH, 'user.id = article.author')
            ->orderBy('article.id', "DESC")
            ->setMaxResults(4);

        return $builder->getQuery()->execute();
    }
}
