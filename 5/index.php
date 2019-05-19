<?php

use Base\{Application, View};

chdir(__DIR__);
require './vendor/autoload.php';

$mainView = (new View())
    ->parse('./views/surfaces.xml')
    ->parse('./views/main.xml');

$popUpView = (new View($mainView->surfaces()))
    ->parse('./views/popup.xml');

$loginView = (new View($mainView->surfaces()))
    ->parse('./views/login.xml');

(new Application($mainView))
    ->addView('login', $loginView)
    ->addView('main', $mainView)
    ->addView('popup', $popUpView)
    ->debug(true)
    ->handle();