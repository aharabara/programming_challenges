<?php

namespace App;

use Base\OrderedList;

class TaskList extends OrderedList
{

    /**
     * @return $this
     */
    public function load()
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
        if (!is_dir("$home/.config/starlight") && !mkdir("$home/.config") && !is_dir("$home/.config")) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', "$home/.config"));
        }
        if (!is_dir("$home/.config/starlight") && !mkdir("$home/.config/starlight") && !is_dir("$home/.config/starlight")) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', "$home/.config/starlight"));
        }
        file_put_contents("$home/.config/starlight/tasks.ser", serialize($this->items));
        return $this;
    }

}