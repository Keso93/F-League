<?php


namespace App\Manager;


use App\Entity\BaseEntityInterface;

interface BaseManagerInterface
{

    public function findAll();

    public function find($entity);

    public function persist($entity);

    public function merge($entity);

    public function delete($entity);


}