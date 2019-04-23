<?php

namespace Base;

interface DrawableInterface
{
    /**
     * @param int|null $key
     * @return $this
     */
    public function draw(?int $key);

    /**
     * @param Surface $surface
     * @return $this
     */
    public function setSurface(Surface $surface);

    /** @return bool */
    public function hasSurface(): bool;

    /** @return Surface */
    public function surface(): Surface;


}