<?php

namespace App\Classes;

use App\Entity\Banker;
use App\Repository\BankerRepository;
use Doctrine\ORM\EntityManagerInterface;

trait FindBankerTrait{
    public static function findBanker(EntityManagerInterface $entityManager){
        $bankers = $entityManager->getRepository(Banker::class)->findAll();
        $bankerInfo = [];
        foreach ($bankers as $banker){
            $requestAmount = $banker->getAccountRequest()->count() + $banker->getRequestDeletes()->count();
            $bankerInfo += [$banker->getId() => $requestAmount];
        }
        $arrayIndex = array_rand(array_keys($bankerInfo,min($bankerInfo)));
        $id = array_keys($bankerInfo,min($bankerInfo));
        $requestedBankerId = count($id) > 1 ? $id[$arrayIndex] : $id;
        $requestedBanker = $entityManager->getRepository(Banker::class)->findOneBy(['id'=>$requestedBankerId]);
        return $requestedBanker;
    }
}