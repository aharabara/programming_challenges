<?php

namespace App;


class Snake extends Point
{
    /** @var Snake */
    protected $child;
    protected $isDead = false;


    /**
     * @return Snake
     */
    public function grow(): Snake
    {
        $newChild = new self($this->symbol, $this->x+1, $this->y);
        if ($this->child) {
            $newChild->swapWith($this->child);
        }
        $this->child = $newChild;
        return $this->child;
    }

    /**
     * @param Snake $snake
     * @return Snake
     */
    protected function swapWith(Snake $snake): Snake
    {
        [$snake->x, $snake->y] = [$this->x, $this->y];
        $this->child = $snake;
        return $this;
    }

    /**
     * @param int|null $key
     * @param int|null $x
     * @param int|null $y
     */
    public function draw(?int $key, ?int $x = null, ?int $y = null): void
    {
        if ($this->isDead) {
            return;
        }
        $oldX = $this->x;
        $oldY = $this->y;

        $this->y = $y ?? $this->y;
        $this->x = $x ?? $this->x;
        if ($key === NCURSES_KEY_DOWN) {
            $this->y++;
        } elseif ($key === NCURSES_KEY_UP) {
            $this->y--;
        } elseif ($key === NCURSES_KEY_RIGHT) {
            $this->x++;
        } elseif ($key === NCURSES_KEY_LEFT) {
            $this->x--;
        }
        parent::draw($key);
        $this->child
            ? $this->child->draw(null, $oldX, $oldY)
            : null;
    }

    /**
     * @param Snake|null $snake
     * @return bool
     */
    public function selfCollision(?Snake $snake = null): bool
    {
        if (!$this->child) {
            return false;
        }
        if ($this->child->selfCollision($snake ?? $this)) {
            return true; // If one Snake child is colliding with head, then instantly send it to top
        }
        return $snake && $snake->collide($this);
    }

    /**
     * @return $this
     */
    public function die(): self
    {
        $this->isDead = true;
        return $this;
    }

}