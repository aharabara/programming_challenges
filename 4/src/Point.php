<?php

namespace App;

class Point
{

    /** @var string */
    protected $symbol;

    /** @var int */
    protected $x;

    /** @var int */
    protected $y;


    /**
     * Point constructor.
     * @param string $symbol
     * @param int $x
     * @param int $y
     */
    public function __construct(string $symbol, int $x, int $y)
    {
        if (strlen($symbol) > 1) {
            throw new \UnexpectedValueException('Point can contain only one symbol.');
        }
        $this->symbol = $symbol;
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param int|null $key
     */
    public function draw(?int $key): void
    {
        ncurses_move($this->y, $this->x);
        ncurses_addstr($this->symbol);
    }

    /**
     * @param Point $point
     * @return bool
     */
    public function collide(Point $point): bool
    {
        return $point->x === $this->x && $point->y === $this->y;
    }
}