<?php

use App\StatusList;
use App\Task;
use App\TaskList;
use Base\Application;
use Base\Button;
use Base\Divider;
use Base\Input;
use Base\Label;
use Base\OrderedList;
use Base\Position;
use Base\Surface;
use Base\Terminal;
use Base\Text;
use Base\TextArea;
use Base\Window;

require './vendor/autoload.php';

$leftColumnWidth = 40;
$leftColumnTop = new Surface(new Position(0, 0), new Position($leftColumnWidth, Terminal::height() - 7));
$leftColumnBottom = new Surface(new Position(0, Terminal::height() - 7),  new Position($leftColumnWidth, Terminal::height()));

$rightColumn = new Surface(new Position($leftColumnWidth, 0), new Position(Terminal::width(), Terminal::height()));

$tasks = (new TaskList())->load();

$taskTitle = new Input('');
$taskDescription = new TextArea('', TextArea::DEFAULT_FILL);

$statusList = new StatusList();
$saveBtn = new Button('Save');
$addBtn = new Button('Add item');

$listWindow = new Window('items', 'Tasks', $leftColumnTop,
    $tasks
);

$btnWindow = new Window('buttons', null, $leftColumnBottom,
    $addBtn, $saveBtn
);
$formWindow = new Window('item.form', 'Task form', $rightColumn,
    new Label('Title:'),
    $taskTitle,
    new Divider('='),
    new Label('Description:'),
    $taskDescription,
    new Divider('='),
    new Label('Status:'),
    $statusList
);
$formWindow->setVisibility(false);
$emptyWindow = new Window('empty', 'Task form', $rightColumn, new Text('Task title', Text::CENTER_MIDDLE));

$addBtn->listen(Button::CLICKED, static function () use ($tasks) {
    $task = new Task('Task title');
    $task->setStatus(Task::WAITING);
    $tasks->addItems($task)
        ->selectItem($task);
});

$saveBtn->listen(Button::CLICKED, static function () use ($taskDescription, $statusList, $taskTitle, $tasks) {
    /** @var Task $task */
    $task = $tasks->getSelectedItem();
    if ($statusList->hasSelected()) {
        $task->setStatus($statusList->getSelectedItem()->getValue());
    }
    $task->setDescription($taskDescription->getText())
        ->setText($taskTitle->getText())
        ->save();
});

$tasks->listen(OrderedList::EVENT_SELECTED,
    static function (Task $task) use ($emptyWindow, $taskTitle, $statusList, $taskDescription, $tasks, $formWindow) {
        $emptyWindow->setVisibility(false);
        $formWindow->setVisibility(true);
        $taskDescription->setText($task->getDescription());
        $taskTitle->setText($task->getText());
        $statusList->selectItemByValue($task->getStatus());
    });

$tasks->listen(OrderedList::EVENT_BEFORE_SELECT,
    static function (Task $task) use ($taskTitle, $statusList, $taskDescription, $tasks, $formWindow) {
        if ($statusList->hasSelected()) {
            $task->setStatus($statusList->getSelectedItem()->getValue());
        }
        $task->setDescription($taskDescription->getText())
            ->setText($taskTitle->getText())
            ->save();
    });

$tasks->listen(OrderedList::EVENT_DELETED,
    static function () use ($emptyWindow, $tasks, $taskDescription, $formWindow) {
        $formWindow->setVisibility(false);
        $emptyWindow->setVisibility(true);
        $tasks->save();
    });

$app = (new Application())
    ->addLayer($listWindow)
    ->addLayer($emptyWindow)
    ->addLayer($formWindow)
    ->addLayer($btnWindow);

$app->handle();