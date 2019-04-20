<?php

namespace Base;

class Surface
{
    /**
     * @var Position
     */
    protected $topLeft;

    /**
     * @var Position
     */
    protected $bottomRight;

    /**
     * Surface constructor.
     * @param Position $topLeft
     * @param Position $bottomRight
     * @throws \Exception
     */
    public function __construct(Position $topLeft, Position $bottomRight)
    {
        $this->topLeft = $topLeft;
        $this->bottomRight = $bottomRight;
        if ($this->width() < 0 || $this->height() < 0) {
            throw new \Exception('Incorrect positions for Surface class. Positions should give positive height and width.');
        }
    }

    /**
     * @return Position
     */
    public function topLeft(): Position
    {
        return $this->topLeft;
    }

    /**
     * @return Position
     */
    public function bottomRight(): Position
    {
        return $this->bottomRight;
    }

    /**
     * @return int
     */
    public function width(): int
    {
        return $this->bottomRight->getX() - $this->topLeft->getX();
    }

    /**
     * @return int
     */
    public function height(): int
    {
        return $this->bottomRight->getY() - $this->topLeft->getY();
    }

    /**
     * @param int $x
     * @param int $y
     * @return Surface
     * @throws \Exception
     */
    public function resize(int $x, int $y): Surface
    {
        return new self(
            new Position($this->topLeft->getX() - $x, $this->topLeft->getY() - $y),
            new Position($this->bottomRight->getX() + $x, $this->bottomRight->getY() + $y)
        );
    }
}