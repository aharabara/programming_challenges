<?php

namespace App;

use Base\Application;
use Base\Input;
use Base\ListItem;
use Base\OrderedList;
use Base\TextArea;
use Base\BaseController;

class TaskController extends BaseController
{
    /** @var OrderedList */
    protected $taskList;
    /** @var OrderedList */
    protected $taskStatus;
    /** @var TextArea */
    protected $taskDescription;
    /** @var Input */
    protected $taskTitle;
    /** @var Application */
    private $app;

    /**
     * TaskController constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $view = $app->view('main');
        $this->taskList = $view->component('task-list');
        $this->taskDescription = $view->component('task.description');
        $this->taskStatus = $view->component('task.status');
        $this->taskTitle = $view->component('task.title');
    }

    /**
     * @param OrderedList $list
     */
    public function taskStatuses(OrderedList $list): void
    {
        $list->addItems(
            new ListItem(Task::WAITING, 'Waiting'),
            new ListItem(Task::OLD, 'Old'),
            new ListItem(Task::FAILED, 'Failed'),
            new ListItem(Task::DONE, 'Done'),
            new ListItem(Task::IN_PROGRESS, 'In progress')
        );
    }


    /**
     * @param OrderedList $list
     */
    public function load(OrderedList $list): void
    {
        $home = getenv('HOME');
        if (is_dir("$home/.config/starlight")) {
            $serializedData = file_get_contents("$home/.config/starlight/tasks.ser");
            $list->setItems(unserialize($serializedData) ?? []);
        }
    }

    public function save()
    {
        $task = $this->taskList->getSelectedItem();
        if ($task) {
            $this->updateTask($task);
        }
        $home = getenv('HOME');
        $this->createDir("$home/.config")
            ->createDir("$home/.config/starlight");
        file_put_contents("$home/.config/starlight/tasks.ser", serialize($this->taskList->getItems()));
    }

    /**
     * @param string $configFolder
     * @return TaskController
     */
    protected function createDir(string $configFolder)
    {
        if (!is_dir($configFolder) && !mkdir($configFolder) && !is_dir($configFolder)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $configFolder));
        }
        return $this;
    }

    public function addItem(): void
    {
        $task = $this->taskList->getSelectedItem();
        if($task){
            $this->updateTask($task);
        }
        $newTask = new Task('Task title');
        $newTask->setStatus(Task::WAITING);
        $this->taskList
            ->addItems($newTask)
            ->selectItem($newTask);
    }

    public function taskSelect(Task $task): void
    {
        $this->taskTitle->setText($task->getText());
        $this->taskDescription->setText($task->getDescription());
        $this->taskStatus->selectItemByValue($task->getStatus());
    }

    public function beforeTaskSelect(Task $task): void
    {
        $this->updateTask($task);
    }

    /**
     * @param Task $task
     */
    protected function updateTask(Task $task): void
    {
        $task->setText($this->taskTitle->getText());
        $task->setDescription($this->taskDescription->getText());
        $task->setStatus($this->taskStatus->getSelectedItem()->getValue() ?? Task::WAITING);
    }

    public function deleteTask(): void
    {
        $this->taskList->delete($this->taskList->getFocusedItem());
        $this->app->switchTo('main');
    }

    public function confirmDelete(): void
    {
        $this->app->switchTo('popup');
    }

    public function closePopUp(): void
    {
        $this->app->switchTo('main');
    }
}