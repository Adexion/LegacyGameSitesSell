<?php

namespace MNGame\Database\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use MNGame\Database\Entity\Article;
use MNGame\Database\Entity\User;
use MNGame\Service\ServerProvider;

class ArticleRepository extends AbstractRepository
{
    private ServerProvider $serverProvider;
    public const ARTICLE_PER_PAGES = 20;

    public function __construct(ManagerRegistry $registry, ServerProvider $serverProvider)
    {
        parent::__construct($registry, Article::class);
        $this->serverProvider = $serverProvider;
    }

    public function getLastArticles(): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('article.id, article.image, article.subhead, article.title, article.text, article.shortText, article.createdAt, user.username as author')
            ->from(Article::class, 'article')
            ->leftJoin(User::class, 'user', Join::WITH, 'user.id = article.author')
            ->where('article.serverId = :serverId')
            ->setParameter(':serverId', $this->serverProvider->getSessionServer()->getId())
            ->orderBy('article.id', "DESC")
            ->setMaxResults(4);

        return $builder->getQuery()->execute();
    }

    public function getArticles(int $page): array
    {
        $builder = $this->getEntityManager()->createQueryBuilder();
        $page = $page < 1 ? 1 : $page;

        $builder
            ->select('article.id, article.image, article.subhead, article.title, article.text, article.shortText, article.createdAt, user.username as author')
            ->from(Article::class, 'article')
            ->leftJoin(User::class, 'user', Join::WITH, 'user.id = article.author')
            ->where('article.serverId = :serverId')
            ->setParameter(':serverId', $this->serverProvider->getSessionServer()->getId())
            ->orderBy('article.id', "DESC")
            ->setMaxResults(self::ARTICLE_PER_PAGES)
            ->setFirstResult(self::ARTICLE_PER_PAGES * ($page - 1));

        return $builder->getQuery()->execute();
    }
}
