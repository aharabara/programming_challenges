<?php
namespace Snake\Interfaces;

interface CollidableInterface
{
    /**
     * @param CollidableInterface $entity
     * @return bool
     */
    public function collide(CollidableInterface $entity): bool;
}