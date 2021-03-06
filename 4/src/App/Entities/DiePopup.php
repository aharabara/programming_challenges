<?php

namespace Snake;

use Base\Colors;
use Base\Terminal;
use Base\Text;
use Base\Panel;

class DiePopup extends Panel
{
    protected $defaultColorPair = Colors::BLACK_YELLOW;

    public function __construct()
    {
        parent::__construct('die_win', 'Game over', Terminal::centered(50, 5), new Text('You died.', Text::CENTER_MIDDLE));
    }
}