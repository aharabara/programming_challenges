<?php

use App\Apple;
use App\Scene;
use App\Snake;
use Base\Application;
use Base\Colors;
use Base\Position;
use Base\Square;
use Base\Terminal;
use Base\Text;
use Base\Window;

require './vendor/autoload.php';

$snake = new Snake('#', 10, 10);
$snake->grow()->grow();


$scene = (new Scene('background'))
    ->setDimensions(new Position(0, 0), new Position(Terminal::width(), Terminal::height()));

$apple = new Apple('O', $scene->getSurface()->resize(-1, -1));
//print_r(Terminal::centered(10, 10));
//die();
$dieWindow = (new Window('die_win', new Text(str_repeat('You died.', 10), Text::CENTER_MIDDLE)))
    ->setVisible(false)
    ->setSurface(Terminal::centered(50, 5))
    ->setDefaultColorPair(Colors::BLACK_YELLOW)
    ->build();

$app = (new Application(NCURSES_KEY_RIGHT))
    ->setRepeatingKeys(true)
    ->addLayer($scene)
    ->addLayer($dieWindow)
    ->addLayer($apple)
    ->addLayer($snake);

$app->handle(static function (Application $app, ?int $key) use ($scene, $dieWindow, $apple, $snake) {
    if ($snake->collide($apple)) {
        $snake->grow();
        $apple->randMove();
    }
    if ($snake->selfCollision() || !$snake->within($scene)) {
        $dieWindow->setVisible(true);
        $snake->die();
        $app->exit();
        return 0;
    }
});