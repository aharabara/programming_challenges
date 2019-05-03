<?php

use Snake\App;
use Snake\Apple;
use Snake\DiePopup;
use Snake\Scene;
use Snake\Snake;
use Base\Position;

require './vendor/autoload.php';

$snake = new Snake(new Position(10, 10));
$snake->grow()->grow();
$scene = new Scene();

$apple = new Apple($scene->surface()->resize(-2, -2));

$dieWindow = (new DiePopup())->setVisibility(false);

$app = (new App())
    ->addLayer($scene)
    ->addLayer($dieWindow)
    ->addLayer($apple)
    ->addLayer($snake);

$app->handle(static function (App $app, ?int $key) use ($scene, $dieWindow, $apple, $snake) {
    if ($snake->collide($apple)) {
        $snake->grow();
        $apple->randMove();
    }
    if ($snake->selfCollision() || !$snake->within($scene)) {
        $dieWindow->setVisibility(true);
        $snake->die();
        $app->exit();
        return 0;
    }
});