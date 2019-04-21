<?php

namespace Base;

use Base\Colors;
use Base\DrawableInterface;
use Base\Surface;

class OrderedList implements DrawableInterface
{
    public const EVENT_SELECTED = 'list.item.selected';
    public const EVENT_DELETED = 'list.item.deleted';

    /** @var array|ListItem[] */
    protected $items = [];

    /** @var int */
    protected $selected;

    /** @var int */
    protected $focused = 0;

    /** @var Surface */
    protected $surface;

    /**
     * OrderedList constructor.
     * @param Surface $surface
     */
    public function __construct(Surface $surface)
    {
        $this->surface = $surface;
        $this->selected = null;
        $this->focused = 0;
    }


    /**
     * @param int|null $pressedKey
     */
    public function draw(?int $pressedKey)
    {
        $topLeft = $this->surface->topLeft();
        $y = $topLeft->getY();
        $x = $topLeft->getX();
        ncurses_move($y, $x);
        $items = array_values($this->items);
        $height = $this->surface->height();
        $width = $this->surface->width();

        if (count($items) > $height) {
            $items = array_slice($items, 0, $height + 1);
        }
        $this->handleKeyPress($pressedKey);

        foreach ($items as $key => $item) {
            ncurses_color_set(Colors::BLACK_WHITE);
            ncurses_move($y++, $x);
            $checked = ' ';
            $symbol = ' ';
            if ($key === $this->focused) {
                ncurses_color_set(Colors::WHITE_BLACK);
            }
            if ($key === $this->selected) {
                $checked = '+';
            }
            $text = $item->getText();
            if (strlen($text) > $width) {
                $text = substr($text, 0, $width - 6); // 6 = 3 fo dots in the end and 3 for "[ ]"
                $symbol = '.';
            }
            ncurses_addstr(str_pad("[$checked]$text", $width, $symbol));
        }
        ncurses_move($y++, $x);
        ncurses_addstr("Current key: $pressedKey");


        if (count($items) > $height) {
            ncurses_addstr(str_pad('\/ \/ \/', $width, ' ', STR_PAD_BOTH));
        }

    }

    /**
     * @param ListItem $item
     * @return $this
     */
    public function addItem(ListItem $item): self
    {
        $this->items[] = $item;
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
     * @param int|null $key
     */
    protected function handleKeyPress(?int $key)
    {
        switch ($key) {
            case NCURSES_KEY_DOWN:
                if ($this->focused < count($this->items) - 1) {
                    $this->focused++;
                }
                break;
            case NCURSES_KEY_UP:
                if ($this->focused > 0) {
                    $this->focused--;
                }
                break;
            case NCURSES_KEY_DC:
                $this->delete($this->focused);
                break;
            case 10:// 10 is for 'Enter' key
                $this->selected = $this->focused;
                break;
        }
    }

    private function delete(int $focused): void
    {
        unset($this->items[$focused]);
        $this->items = array_values($this->items);
    }
}