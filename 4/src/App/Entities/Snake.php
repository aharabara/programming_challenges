<?php

namespace Snake;

use Base\Colors;
use Base\Curse;
use Snake\Interfaces\CollidableInterface;
use Base\Point;
use Base\Position;

class Snake extends Point implements CollidableInterface
{
    /** @var Snake */
    protected $child;

    /** @var bool */
    protected $isDead = false;

    public function __construct(Position $position)
    {
        parent::__construct('#', $position);
    }

    /**
     * @return Snake
     */
    public function grow(): Snake
    {
        $lastItem = $this->getLastChild();
        $newChild = new self((clone $this->position)->decX());
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
        Curse::color(Colors::BLACK_GREEN);
        $oldX = $this->position->getX();
        $oldY = $this->position->getY();
        if ($this->isDead) {
            parent::draw($key);
            if ($this->child) {
                $this->child->draw(null, $oldX, $oldY);
            }
            return;
        }

        $this->position->setY($y ?? $this->position->getY());
        $this->position->setX($x ?? $this->position->getX());
        if ($key === NCURSES_KEY_DOWN) {
            $this->position->incY();
        } elseif ($key === NCURSES_KEY_UP) {
            $this->position->decY();
        } elseif ($key === NCURSES_KEY_RIGHT) {
            $this->position->incX();
        } elseif ($key === NCURSES_KEY_LEFT) {
            $this->position->decX();
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
        return $point->position->getX() === $this->position->getX() && $point->position->getY() === $this->position->getY();
    }

    /**
     * @param Scene $scene
     * @return bool
     * @throws \Exception
     */
    public function within(Scene $scene): bool
    {
        $surf = $scene->surface()->resize(-1, -1);
        $topLeft = $surf->topLeft();
        $bottomRight = $surf->bottomRight();
        return $this->position->getX() > $topLeft->getX() && $this->position->getX() < $bottomRight->getX()
            && $this->position->getY() > $topLeft->getY() && $this->position->getY() < $bottomRight->getY();
    }
}