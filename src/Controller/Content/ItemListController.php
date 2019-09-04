<?php

namespace ModernGame\Controller\Content;

use ModernGame\Database\Entity\UserItem;
use Symfony\Component\HttpFoundation\JsonResponse;

class ItemListController
{
    public function getItemList()
    {
        return new JsonResponse([
            'itemList' => $this->getDoctrine()
                ->getRepository(UserItem::class)->findBy(['userId' => $this->getUser()->getId()])
        ]);
    }
}
