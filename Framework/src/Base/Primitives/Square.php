<?php

namespace Base;

class Square implements DrawableInterface
{

    /** @var Surface */
    protected $surface;

    /** @var string */
    protected $title;

    /** @var string */
    protected $visible = true;

    /** @var string */
    protected $innerSymbol = ' ';

    /** @var string */
    protected $borderSymbol = '=';

    /** @var int */
    protected $defaultColorPair = Colors::BLACK_WHITE;

    /**
     * @param int|null $key
     * @throws \Exception
     */
    public function draw(?int $key)
    {
        if (!$this->visible) {
            return;
        }
        // draw two squares
        ncurses_color_set($this->defaultColorPair);
        $lowerBound = $this->surface->bottomRight()->getY();
        $higherBound = $this->surface->topLeft()->getY();
        $width = $this->surface->width();

        for ($y = $higherBound; $y <= $lowerBound; $y++) {
            ncurses_move($y, $this->surface->topLeft()->getX());
            if ($y === $higherBound || $y === $lowerBound) {
                ncurses_addstr(str_repeat($this->borderSymbol, $width));
            } else {
                ncurses_addstr($this->borderSymbol . str_repeat($this->innerSymbol, $width - 2) . $this->borderSymbol);
            }
        }
    }

    /**
     * @return Surface
     */
    public function getSurface(): Surface
    {
        return $this->surface;
    }

    /**
     * @param Position $topLeft
     * @param Position $bottomRight
     * @return $this
     * @throws \Exception
     */
    public function setDimensions(Position $topLeft, Position $bottomRight): self
    {
        $this->surface = new Surface($topLeft, $bottomRight);
        return $this;
    }

    /**
     * @param Surface $surface
     * @return $this
     */
    public function setSurface(Surface $surface): self
    {
        $this->surface = $surface;
        return $this;
    }

    /**
     * @param string $innerSymbol
     * @return $this
     */
    public function setInnerSymbol(string $innerSymbol): self
    {
        $this->innerSymbol = $innerSymbol;
        return $this;
    }

    /**
     * @param string $borderSymbol
     * @return $this
     */
    public function setBorderSymbol(string $borderSymbol): self
    {
        $this->borderSymbol = $borderSymbol;
        return $this;
    }

    /**
     * @param int $defaultColorPair
     * @return $this
     * @throws \Exception
     */
    public function setDefaultColorPair(int $defaultColorPair): self
    {
        if (!in_array($defaultColorPair, [Colors::BLACK_WHITE, Colors::BLACK_YELLOW], true)) {
            throw new \Exception('Invalid Color for ' . __CLASS__ . '::' . __METHOD__);
        }
        $this->defaultColorPair = $defaultColorPair;
        return $this;
    }

    /**
     * @param string $visible
     * @return $this
     */
    public function setVisible(string $visible): self
    {
        $this->visible = $visible;
        return $this;
    }
}