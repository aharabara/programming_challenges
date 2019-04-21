<?php

use App\Task;
use Base\Application;
use Base\OrderedList;
use Base\Position;
use Base\Surface;
use Base\Terminal;
use Base\Window;

require './vendor/autoload.php';

$leftColumnWidth = 40;
$leftColumn = new Surface(new Position(0, 0), new Position($leftColumnWidth, Terminal::height()));
$rightColumn = new Surface(
    new Position($leftColumnWidth, 0),
    new Position(Terminal::width(), Terminal::height())
);

$list = (new OrderedList($leftColumn->resize(-1, -1)))
    ->addItem(new Task('Task add.'))
    ->addItem(new Task('Task delete.'))
    ->addItem(new Task('Tasks saving.'))
    ->addItem(new Task('Tasks loading.'))
    ->addItem(new Task('Tasks edit.'))
    ->addItem(new Task('Single UI component key handling.'))
    ;

$listWindow = new Window('items', $leftColumn, $list);
$formWindow = new Window('item.form', $rightColumn, clone $list);

EventBus::listen(OrderedList::EVENT_SELECTED, static function () use ($formWindow) {
//    $formWindow->
});


$app = (new Application(NCURSES_KEY_RIGHT))
    ->setRepeatingKeys(false)
    ->setSingleLayerFocus(true)
    ->addLayer($listWindow)
    ->addLayer($formWindow);

$app->handle(static function (Application $app, ?int $key) {

});