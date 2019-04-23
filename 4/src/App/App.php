<?php
namespace Snake;

use Base\Application;

class App extends Application
{
    protected $lastValidKey = NCURSES_KEY_RIGHT;
    protected $repeatingKeys = true;
}