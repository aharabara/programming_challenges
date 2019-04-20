<?php
namespace App;

use App\Interfaces\CollidableInterface;
use \Base\Point;
use Base\Surface;

class Apple extends Point implements CollidableInterface
{

    /**
     * Apple constructor.
     * @param string $symbol
     * @param Surface $surface
     * @throws \Exception
     */
    public function __construct(string $symbol, Surface $surface)
    {
        $x = random_int($surface->topLeft()->getX(), $surface->bottomRight()->getX());
        $y = random_int($surface->topLeft()->getY(), $surface->bottomRight()->getY());
        parent::__construct($symbol, $x, $y);
    }


    /**
     * @return $this
     * @throws \Exception
     */
    public function randMove(): self
    {
        $this->x = random_int(0, 40);
        $this->y = random_int(0, 20);
        return $this;
    }

    /**
     * @param CollidableInterface|Point $point
     * @return bool
     */
    public function collide(CollidableInterface $point): bool
    {
        return $point->x === $this->x && $point->y === $this->y;
    }
}