<?php

use App\Task;
use App\TaskList;
use Base\Application;
use Base\OrderedList;
use Base\Position;
use Base\Surface;
use Base\Terminal;
use Base\TextArea as Text;
use Base\Window;

require './vendor/autoload.php';

$leftColumnWidth = 40;
$leftColumn = new Surface(new Position(0, 0), new Position($leftColumnWidth, Terminal::height()));
$rightColumn = new Surface(
    new Position($leftColumnWidth, 0),
    new Position(Terminal::width(), Terminal::height())
);

$list = (new TaskList())->load();

$formContent = new Text('Task description', Text::DEFAULT_FILL);

$secondList = clone $list;
$listWindow = new Window('items', 'Tasks', $leftColumn, $list);
$formWindow = new Window('item.form', 'Task form', $rightColumn, $formContent, $secondList);

EventBus::listen(OrderedList::EVENT_SELECTED, static function (Task $task) use ($list, $formWindow) {
    $text =
        "Description: {$task->getText()};\n" .
        "Status: {$task->getStatus()}";
    $formWindow->replaceComponent(0, new Text($text, Text::DEFAULT_FILL));
    $list->save();
});

EventBus::listen(OrderedList::EVENT_DELETED, static function () use ($list, $formContent, $formWindow) {
    $formWindow->replaceComponent(0, $formContent);
    $list->save();
});


$app = (new Application())
    ->addLayer($listWindow)
    ->addLayer($formWindow);

$app->handle();