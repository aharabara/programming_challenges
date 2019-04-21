<?php

namespace App;

use Base\HasMeta;
use Base\ListItem;
use Base\Meta;

class Task extends ListItem
{
    use HasMeta;

    public const WAITING = 'waiting';
    public const IN_PROGRESS = 'in_progress';
    public const DONE = 'done';
    public const FAILED = 'failed';
    public const OLD = 'old';

    public function __construct(string $text)
    {
        parent::__construct($text);
        $this->addMeta(new Meta('status', self::WAITING));
        $this->addMeta(new Meta('description', 'Task description...'));
    }

    /**
     * @param string $status
     * @return Task
     */
    public function setStatus(string $status): Task
    {
        $allowedStates = [
            self::OLD,
            self::FAILED,
            self::DONE,
            self::IN_PROGRESS,
            self::WAITING,
        ];
        if (!in_array($status, $allowedStates, true)) {
            throw new \UnexpectedValueException('Wrong Task state.'); // todo change to WrongTaskStatusException()
        }
        $this->getMeta('status')
            ->setContent($status);
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getMeta('status')->getContent();
    }
}