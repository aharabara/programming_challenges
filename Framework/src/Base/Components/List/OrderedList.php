<?php

namespace Base;

class OrderedList extends BaseComponent
{
    public const EVENT_SELECTED = 'list.item.selected';
    public const EVENT_DELETED = 'list.item.deleted';

    /** @var array|ListItem[] */
    protected $items = [];

    /** @var int */
    protected $selected;

    /** @var int */
    protected $focusedItem = 0;

    /** @var Surface */
    protected $surface;

    /**
     * OrderedList constructor.
     */
    public function __construct()
    {
        $this->selected = null;
        $this->focusedItem = 0;
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
            Curse::color(Colors::BLACK_WHITE);
            $checked = ' ';
            $symbol = ' ';
            if ($key === $this->focusedItem) {
                Curse::color(Colors::WHITE_BLACK);
            }
            if ($key === $this->selected) {
                $checked = '+';
            }
            $text = $item->getText();
            if (strlen($text) > $width) {
                $text = substr($text, 0, $width - 6); // 6 = 3 fo dots in the end and 3 for "[ ]"
                $symbol = '.';
            }
            Curse::writeAt(str_pad("[$checked]$text", $width, $symbol), $y++, $x);
        }
        Curse::color(Colors::BLACK_WHITE);
        Curse::writeAt("Current key: $pressedKey", $y++, $x);

        if (count($items) > $height) {
            ncurses_addstr(str_pad('\/ \/ \/', $width, ' ', STR_PAD_BOTH));
        }

    }

    /**
     * @param ListItem ...$items
     * @return $this
     */
    public function addItems(ListItem ...$items): self
    {
        array_push($this->items, ...$items);
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
    protected function handleKeyPress(?int $key): void
    {
        switch ($key) {
            case NCURSES_KEY_DOWN:
                if ($this->focusedItem < count($this->items) - 1) {
                    $this->focusedItem++;
                }
                break;
            case NCURSES_KEY_UP:
                if ($this->focusedItem > 0) {
                    $this->focusedItem--;
                }
                break;
            case NCURSES_KEY_DC:
                $this->delete($this->focusedItem);
                \EventBus::dispatch(self::EVENT_DELETED, []);
                break;
            case 10:// 10 is for 'Enter' key
                $this->selected = $this->focusedItem;
                \EventBus::dispatch(self::EVENT_SELECTED, [$this->getSelectedItem()]);
                break;
        }
    }

    private function delete(int $focusedItem): void
    {
        unset($this->items[$focusedItem]);
        $this->items = array_values($this->items);
    }

    /**
     * @return ListItem
     */
    public function getSelectedItem(): ListItem
    {
        return $this->items[$this->selected];
    }

    /** @return bool */
    public function hasSurface(): bool
    {
        return !empty($this->surface);
    }

    /** @return Surface */
    public function surface(): Surface
    {
        return $this->surface;
    }
}