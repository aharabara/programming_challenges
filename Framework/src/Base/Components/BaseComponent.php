<?php

namespace Base;

abstract class BaseComponent implements DrawableInterface
{
    /** @var bool */
    protected $focused = false;

    /**
     * @return array|DrawableInterface[]
     */
    public function toComponentsArray(): array
    {
        return [$this];
    }

    /**
     * @return bool
     */
    public function isFocused(): bool
    {
        return $this->focused;
    }

    /**
     * @param bool $focused
     * @return $this|DrawableInterface
     */
    public function setFocused(bool $focused)
    {
        $this->focused = $focused;
        return $this;
    }
}