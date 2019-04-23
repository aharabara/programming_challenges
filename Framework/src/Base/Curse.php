<?php

namespace Base;

class Curse
{
    /**
     * @param int|null $color
     */
    public static function color(?int $color): void
    {
        ncurses_color_set($color ?? Colors::BLACK_WHITE);
    }

    public static function writeAt(string $text, ?int $y = null, ?int $x = null): void
    {
        if($y !== null && $x !== null){
            ncurses_move($y, $x);
        }
        ncurses_addstr($text);
    }
}