<?php

use Base\{Application, Button, Divider, Input, Label, OrderedList, View, TextArea, Panel};

require './vendor/autoload.php';

View::registerComponent('button', Button::class);
View::registerComponent('div', Divider::class);
View::registerComponent('input', Input::class);
View::registerComponent('label', Label::class);
View::registerComponent('textarea', TextArea::class);
View::registerComponent('list', OrderedList::class);
$view = (new View())
    ->parse('./surfaces.xml')
    ->parse('./ui.xml')
;

$app = new Application($view);
foreach ($view->panels() as $panel) {
    $app->addLayer($panel);
}

$app->handle();