<?php

namespace Base;

class Window extends Square implements FocusableInterface
{

    /** @var string */
    protected $id;

    /** @var DrawableInterface */
    protected $content;

    /**
     * Window constructor.
     * @param string $id
     * @param Surface $surface
     * @param DrawableInterface $content
     * @throws \Exception
     */
    public function __construct(string $id, Surface $surface, DrawableInterface $content)
    {
        $this->id = $id;
        $this->content = $content;
        $this->surface = $surface;
        $this->content->setSurface($surface->resize(-1, -1));
    }

    /**
     * @param int|null $key
     * @throws \Exception
     */
    public function draw(?int $key)
    {
        if (!$this->visible) {
            return;
        }
        parent::draw($key);
        $this->content->draw($key);
    }

    public function setSurface(Surface $surface): Square
    {
        $this->content->setSurface($surface->resize(-1, -1));
        return parent::setSurface($surface);
    }

//    public function setMenu(Menu $menu)
//    {
//
//    }


}