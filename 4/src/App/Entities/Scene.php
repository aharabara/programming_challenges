<?php

namespace Snake;

use Base\Position;
use Base\Square;
use Base\Terminal;

class Scene extends Square
{

    /**
     * Scene constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setDimensions(new Position(0, 0), new Position(Terminal::width(), Terminal::height()));
    }
}