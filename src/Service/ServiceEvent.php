<?php

namespace App\Service;

use App\Service\ServiceBase as ServiceServiceBase;
use Doctrine\ORM\EntityManagerInterface;

class ServiceEvent extends ServiceServiceBase{

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }
}