<?php
namespace App\Interfaces;

interface CollidableInterface
{
    /**
     * @param CollidableInterface $entity
     * @return bool
     */
    public function collide(CollidableInterface $entity): bool;
}