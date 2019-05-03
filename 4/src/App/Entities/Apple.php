<?php
namespace Snake;

use Base\Colors;
use Base\Curse;
use Snake\Interfaces\CollidableInterface;
use Base\Point;
use Base\Position;
use Base\Surface;

class Apple extends Point implements CollidableInterface
{

    /**
     * Apple constructor.
     * @param Surface $surface
     * @throws \Exception
     */
    public function __construct(Surface $surface)
    {
        $x = random_int($surface->topLeft()->getX(), $surface->bottomRight()->getX());
        $y = random_int($surface->topLeft()->getY(), $surface->bottomRight()->getY());
        parent::__construct('O', new Position($x, $y));
    }


    /**
     * @param int|null $key
     */
    public function draw(?int $key): void
    {
        Curse::color(Colors::BLACK_RED);
        parent::draw($key);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function randMove(): self
    {
        $this->position->setY(random_int(0, $this->surface()->height()));
        $this->position->setX(random_int(0, $this->surface()->width()));
        return $this;
    }

    /**
     * @param CollidableInterface|Point $point
     * @return bool
     */
    public function collide(CollidableInterface $point): bool
    {
        return $point->position->getX() === $this->position->getX() && $point->position->getY() === $this->position->getY();
    }
}