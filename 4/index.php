<?php
require './vendor/autoload.php';

use App\Apple;
use App\Point;
use App\Snake;

const CURSOR_INVISIBLE = 0;
const CURSOR_NORMAL = 1;
const CURSOR_VISIBLE = 2;

ncurses_init();
//ncurses_echo();
ncurses_noecho();
ncurses_nl();
//ncurses_nonl();
ncurses_curs_set(CURSOR_INVISIBLE);

/** @var Point[] $layers */
$layers = [];
$snake = new Snake('#', 10, 10);
$snake->grow()->grow();

$apple = new Apple('O', 20, 20);
$layers[] = $apple;
$layers[] = $snake;


function getch_nonblock($timeout)
{
    $read = array(STDIN);
    $null = null;    // stream_select() uses references, thus variables are necessary for the first 3 parameters
    if (stream_select($read, $null, $null, floor($timeout / 1000000), $timeout % 1000000) != 1) {
        return null;
    }
    return ncurses_getch();
}

$key = NCURSES_KEY_RIGHT;
while (true) {
    ncurses_erase();
    $key = getch_nonblock(100000) ?: $key; // use a non blocking getch() instead of $ncurses->getCh()
    foreach ($layers as $item) {
        if ($snake->collide($apple)) {
            $snake->grow();
            $apple->randMove();
        } elseif ($snake->selfCollision()) {
            $snake->die();
            break;
        }
        $item->draw($key);
    }
    ncurses_refresh(0);
    usleep(100000);
}

ncurses_echo();
ncurses_curs_set(CURSOR_VISIBLE);