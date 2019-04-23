<?php

use App\Task;
use Base\Application;
use Base\OrderedList;
use Base\Position;
use Base\Surface;
use Base\Terminal;
use Base\Text;
use Base\Window;

require './vendor/autoload.php';

$leftColumnWidth = 40;
$leftColumn = new Surface(new Position(0, 0), new Position($leftColumnWidth, Terminal::height()));
$rightColumn = new Surface(
    new Position($leftColumnWidth, 0),
    new Position(Terminal::width(), Terminal::height())
);

$list = (new OrderedList())
    ->addItems(
        new Task('Task add.'),
        new Task('Task delete.'),
        new Task('Tasks saving.'),
        new Task('Tasks loading.'),
        new Task('Tasks edit.'),
        new Task('Single UI component key handling.')
    );

$formContent = new Text('Task description', Text::DEFAULT_FILL);

$secondList = clone $list;
$listWindow = new Window('items', 'Tasks', $leftColumn, $list);
$formWindow = new Window('item.form', 'Task form', $rightColumn, $formContent, $secondList);

EventBus::listen(OrderedList::EVENT_SELECTED, static function (Task $task) use ($formWindow) {

    $text =
        "Description: {$task->getText()};\n" .
        "Status: {$task->getStatus()}";
    $formWindow->replaceComponent(0, new Text($text, Text::DEFAULT_FILL));
});

EventBus::listen(OrderedList::EVENT_DELETED, static function () use ($formContent, $formWindow) {
    $formWindow->replaceComponent(0, $formContent);
});


$app = (new Application())
    ->addLayer($listWindow)
    ->addLayer($formWindow);

$app->handle();