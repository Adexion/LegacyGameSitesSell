<?php

namespace ModernGame\Database\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use ModernGame\Database\Entity\Equipment;

class EquipmentRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipment::class);
    }

    public function getSliderImages()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('equipment.sliderImage, equipment.id, equipment.description, equipment.name')
            ->from(Equipment::class, 'equipment')
            ->orderBy('equipment.howManyBuyers', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->execute();
    }

    public function getEquipmentList()
    {
        $equipmentList = $this->createQueryBuilder('equipment')
            ->select('equipment.id', 'equipment.name')
            ->getQuery()->execute();

        $equipments = [];

        foreach ($equipmentList as $eq) {
            $equipments[$eq['name']] = $eq['id'];
        }

        return $equipments;
    }

    public function increaseCounterOfBuying(int $equipmentId)
    {
        /** @var Equipment $equipment */
        $equipment = $this->find($equipmentId);
        $equipment->increaseCounterOfBuying();

        $this->getEntityManager()->flush($equipment);
    }
}
