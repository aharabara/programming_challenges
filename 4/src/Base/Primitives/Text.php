<?php

namespace Base;

class Text implements DrawableInterface
{

    /** @var string */
    protected $text;

    /** @var Surface */
    protected $surface;

    public const DEFAULT_FILL = 0;
    public const CENTER_TOP = 1;
    public const CENTER_MIDDLE = 2;
    public const CENTER_BOTTOM = 3;
    public const ALIGN_TYPES = [
        self::CENTER_BOTTOM,
        self::CENTER_MIDDLE,
        self::CENTER_TOP,
        self::DEFAULT_FILL
    ];

    /**
     * @var int
     */
    private $align;

    /**
     * Point constructor.
     * @param string $text
     * @param int $align
     * @throws \Exception
     */
    public function __construct(string $text, int $align)
    {
        $this->text = $text;
        if (!in_array($align, self::ALIGN_TYPES, true)) {
            throw new \Exception('Align type is not supported');
        }
        $this->align = $align;
    }

    /**
     * @param int|null $key
     * @throws \Exception
     */
    public function draw(?int $key): void
    {
        if (!$this->surface) {
            throw new \Exception('Text surface not set.');
        }
        switch ($this->align) {
            case self::CENTER_TOP:
                $this->centerTopRender();
                break;
            case self::CENTER_MIDDLE:
                $this->centerMiddleRender();
                break;
            case self::CENTER_BOTTOM:
                $this->centerBottomRender();
                break;
            default:
                $this->defaultRender();
        }
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

    protected function defaultRender(): void
    {
        $pos = $this->surface->topLeft();
        $x = $pos->getX();
        $y = $pos->getY();

        $lines = str_split($this->text, $this->surface->width());
        $renderedLines = array_slice($lines, 0, $this->surface->height());

        ncurses_move($y, $x);
        foreach ($renderedLines as $line) {
            ncurses_move($y++, $x);
            ncurses_addstr($line);
        }
        if (count($renderedLines) !== count($lines)) {
            ncurses_move($y++, $x);
            ncurses_addstr('>more...');
        }
    }

    protected function centerTopRender()
    {
    }

    protected function centerMiddleRender()
    {
        $pos = $this->surface->topLeft();

        $lines = str_split($this->text, $this->surface->width());
        $renderedLines = array_slice($lines, 0, $this->surface->height());

        $y = $pos->getY() + round( $this->surface->height() - count($renderedLines) / 2) / 2;


        foreach ($renderedLines as $line) {
            $x = $pos->getX() + $this->surface->width() / 2 - strlen($line) /2;
            ncurses_move($y++, $x);
            ncurses_addstr($line);
        }
        if (count($renderedLines) !== count($lines)) {
            ncurses_move($y++, $x);
            ncurses_addstr('>more...');
        }
    }

    protected function centerBottomRender()
    {
    }
}