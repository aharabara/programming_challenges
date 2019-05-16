<?php

use Base\{Application, Button, Divider, Input, Label, OrderedList, View, TextArea, Panel};

chdir(__DIR__);
require './vendor/autoload.php';

View::registerComponent('button', Button::class);
View::registerComponent('panel', Panel::class);
View::registerComponent('div', Divider::class);
View::registerComponent('input', Input::class);
View::registerComponent('label', Label::class);
View::registerComponent('textarea', TextArea::class);
View::registerComponent('list', OrderedList::class);
$mainView = (new View())
    ->parse('./views/surfaces.xml')
    ->parse('./views/main.xml');

$popUpView = (new View($mainView->surfaces()))
    ->parse('./views/popup.xml');

(new Application($mainView))
    ->addView('main', $mainView)
    ->addView('popup', $popUpView)
    ->handle();