<?php

namespace ModernGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use ModernGame\Database\Entity\Article;
use ModernGame\Database\Entity\User;
use ModernGame\Service\ServerProvider;

class ArticleRepository extends AbstractRepository
{
    private ServerProvider $serverProvider;

    public function __construct(ManagerRegistry $registry, ServerProvider $serverProvider)
    {
        parent::__construct($registry, Article::class);
        $this->serverProvider = $serverProvider;
    }

    public function getLastArticles(): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('article.id, article.image, article.subhead, article.title, article.text, article.shortText, user.username as author')
            ->from(Article::class, 'article')
            ->leftJoin(User::class, 'user', Join::WITH, 'user.id = article.author')
            ->where('article.serverId = :serverId')
            ->setParameter(':serverId', $this->serverProvider->getCookiesServer()['id'])
            ->orderBy('article.id', "DESC")
            ->setMaxResults(4);

        return $builder->getQuery()->execute();
    }
}
