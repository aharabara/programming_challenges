<?php

use Base\{Application, Button, Divider, Input, Label, OrderedList, View, TextArea, Panel};

require './vendor/autoload.php';

$view = (new View())
    ->parse('./views/surfaces.xml')
    ->parse('./views/ui.xml')
;

$app = new Application($view);
foreach ($view->containers() as $container) {
    $app->addLayer($container);
}

$app->handle();