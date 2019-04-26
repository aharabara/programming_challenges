<?php

namespace App;

use Base\OrderedList;

class TaskList extends OrderedList
{
    /** @var bool */
    protected $itemsAreDeletable = true;

    /**
     * @return $this
     */
    public function load(): self
    {
        $home = getenv('HOME');
        if (is_dir("$home/.config/starlight")) {
            $serializedData = file_get_contents("$home/.config/starlight/tasks.ser");
            $this->items = unserialize($serializedData) ?? [];
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function save(): self
    {
        $home = getenv('HOME');
        $this->createDir("$home/.config")
            ->createDir("$home/.config/starlight");
        file_put_contents("$home/.config/starlight/tasks.ser", serialize($this->items));
        return $this;
    }

    /**
     * @param string $configFolder
     * @return $this
     */
    protected function createDir(string $configFolder): self
    {
        if (!is_dir($configFolder) && !mkdir($configFolder) && !is_dir($configFolder)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $configFolder));
        }
        return $this;
    }

}