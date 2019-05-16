<?php

use Base\{Application, View};

chdir(__DIR__);
require './vendor/autoload.php';

$mainView = (new View())
    ->parse('./views/surfaces.xml')
    ->parse('./views/main.xml');

$popUpView = (new View($mainView->surfaces()))
    ->parse('./views/popup.xml');

(new Application($mainView))
    ->addView('main', $mainView)
    ->addView('popup', $popUpView)
    ->handle();