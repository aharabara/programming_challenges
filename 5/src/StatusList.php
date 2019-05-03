<?php

namespace App;

use Base\ListItem;
use Base\OrderedList;

class StatusList extends OrderedList
{
    protected static $statuses = [
        Task::WAITING => 'Waiting',
        Task::OLD => 'Old',
        Task::FAILED => 'Failed',
        Task::DONE => 'Done',
        Task::IN_PROGRESS => 'In progress'
    ];

    /**
     * StatusList constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $items = [];
        array_walk(self::$statuses, static function ($item, $key) use(&$items) {
            $items[] = new ListItem($key, $item);
        });
        $this->addItems(...$items);
    }
}