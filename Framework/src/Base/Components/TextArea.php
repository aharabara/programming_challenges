<?php

namespace Base;

class TextArea extends Text implements FocusableInterface
{

    /** @var Position */
    protected $cursorPosition;

    protected $minHeight = 10;

    /**
     * @param int|null $key
     * @throws \Exception
     */
    public function draw(?int $key): void
    {
        $this->handleKeyPress($key);
        if ($this->isFocused()){
            Curse::color(Colors::BLACK_YELLOW);
        }else{
            Curse::color(Colors::BLACK_WHITE);
        }
        parent::draw($key);
    }

    /**
     * @param Surface $surface
     * @return Text
     */
    public function setSurface(Surface $surface): Text
    {
        $this->cursorPosition = clone $surface->topLeft();
        return parent::setSurface($surface);
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return TextArea
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param int|null $key
     */
    protected function handleKeyPress(?int $key): void
    {
        switch ($key) {
            case NCURSES_KEY_BACKSPACE:
                $this->text = substr($this->text, 0, -1);
                break;
            default:
                if (ctype_alnum($key) || ctype_space($key) || ctype_punct($key)) {
                    $this->text .= chr($key);
                }
        }
    }
}