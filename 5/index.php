<?php

use Base\Application;

require './vendor/autoload.php';

$app = (new Application(NCURSES_KEY_RIGHT))
    ->setRepeatingKeys(true)
//    ->addLayer($snake)
;

$app->handle(static function (Application $app, ?int $key) {

});