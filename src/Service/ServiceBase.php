<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

abstract class ServiceBase {

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Sauvegarde l'entité en base de données
     * 
     * @param $entity
     */
    public function save($entity) {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * Supprime l'entité en base de données
     * 
     * @param $entity
     */
    public function delete($entity) {
        $this->em->remove($entity);
        $this->em->flush();
    }
}