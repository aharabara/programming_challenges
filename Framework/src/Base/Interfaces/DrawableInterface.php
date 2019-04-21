<?php
namespace Base;

interface DrawableInterface
{
    public function draw(?int $key);

    public function setSurface(Surface $surface);

}