<?php

namespace Base;

class Window extends Square
{

    /** @var bool */
    protected $wasBuild = false;

    /** @var string */
    protected $id;

    /** @var Text */
    protected $text;

    /**
     * Window constructor.
     * @param string $id
     * @param Text $text
     */
    public function __construct(string $id, Text $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    /**
     * @param int|null $key
     * @throws \Exception
     */
    public function draw(?int $key)
    {
        if (!$this->wasBuild) {
            throw new \Exception('Window wasn\'t build.');
        }
        if (!$this->visible) {
            return;
        }
        parent::draw($key);
        $this->text->draw($key);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function build(): self
    {
        if (!$this->surface) {
            throw new \Exception('Window surface wasn\'t set');
        }
        if (!$this->wasBuild) {
            $this->wasBuild = true;
        }
        $this->text->setSurface($this->surface->resize(-1, -1));
        return $this;
    }
}