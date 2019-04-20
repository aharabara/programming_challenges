<?php

namespace App;

use App\Interfaces\CollidableInterface;
use Base\Point;

class Snake extends Point implements CollidableInterface
{
    /** @var Snake */
    protected $child;

    /** @var bool */
    protected $isDead = false;

    /**
     * @return Snake
     */
    public function grow(): Snake
    {
        $lastItem = $this->getLastChild();
        $newChild = new self($lastItem->symbol, $lastItem->x, $lastItem->y + 1);
        $lastItem->child = $newChild;
        return $this->child;
    }

    /**
     * @return Snake
     */
    protected function getLastChild(): Snake
    {
        if ($this->child) {
            return $this->child->getLastChild();
        }
        return $this;
    }

    /**
     * @param int|null $key
     * @param int|null $x
     * @param int|null $y
     */
    public function draw(?int $key, ?int $x = null, ?int $y = null): void
    {
        $oldX = $this->x;
        $oldY = $this->y;
        if ($this->isDead) {
            parent::draw($key);
            if ($this->child) {
                $this->child->draw(null, $oldX, $oldY);
            }
            return;
        }

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
     * @return bool
     */
    public function selfCollision(): bool
    {
        /** @var Snake[] $children */
        $children = [];
        $point = $this;
        while ($point->child) {
            $point = $point->child;
            $children[] = $point;
        }

        foreach ($children as $child) {
            if ($this->collide($child)) {
                $child->symbol = 'X';
                return true;
            }
        }
        return false;

    }

    /**
     * @return $this
     */
    public function die(): self
    {
        $this->isDead = true;
        if ($this->child) {
            $this->child->die();
        }
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

    /**
     * @param Scene $scene
     * @return bool
     * @throws \Exception
     */
    public function within(Scene $scene): bool
    {
        $surf = $scene->getSurface()->resize(-1, -1);
        $topLeft = $surf->topLeft();
        $bottomRight = $surf->bottomRight();
        return $this->x > $topLeft->getX() && $this->x < $bottomRight->getX()
            && $this->y > $topLeft->getY() && $this->y < $bottomRight->getY();
    }
}