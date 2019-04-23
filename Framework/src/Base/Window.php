<?php

namespace Base;

class Window extends Square implements FocusableInterface
{

    public const VERTICAL = 'layout.vertical';
    public const HORIZONTAL = 'layout.horizontal';

    /** @var string */
    protected $id;

    /** @var DrawableInterface */
    protected $components;

    /** @var string */
    protected $title;

    protected $layout;
    protected $componentMinHeight = 15;

    /**
     * Window constructor.
     * @param string $id
     * @param string $title
     * @param Surface $surface
     * @param DrawableInterface ...$components
     * @throws \Exception
     */
    public function __construct(string $id, string $title, Surface $surface, DrawableInterface ...$components)
    {
        $this->id = $id;
        $this->components = $components;
        $this->surface = $surface;
        $this->title = $title;
        $this->setComponentsSurface();
    }

    /**
     * @param int|null $key
     * @return Window
     * @throws \Exception
     */
    public function draw(?int $key): self
    {
        if (!$this->visible) {
            return $this;
        }
        parent::draw($key);
        $topLeft = $this->surface->topLeft();
        ncurses_move($topLeft->getY(), $topLeft->getX() + 3);
        if ($this->isFocused()) {
            ncurses_color_set(Colors::BLACK_YELLOW);
        }
        ncurses_addstr("| {$this->title} |");
        return $this;
    }

    /**
     * @param Surface $surface
     * @return Square
     * @throws \Exception
     */
    public function setSurface(Surface $surface): Square
    {
        $result = parent::setSurface($surface);
        $this->setComponentsSurface();
        return $result;
    }

    /**
     * @param DrawableInterface ...$components
     * @return Window
     * @throws \Exception
     */
    public function setComponents(DrawableInterface ...$components): Window
    {
        $this->components = $components;
        $this->setComponentsSurface();
        return $this;
    }

    /**
     * @param int $index
     * @param DrawableInterface $component
     * @return Window
     * @throws \Exception
     */
    public function replaceComponent(int $index, DrawableInterface $component): Window
    {
        $this->components[$index] = $component;
        $this->setComponentsSurface();
        return $this;
    }

    /**
     * @return array|DrawableInterface[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function setComponentsSurface(): self
    {
        if (count($this->components) === 1) {
            $this->components[0]->setSurface($this->surface->resize(-1, -1));
            return $this;
        }
        $baseSurf = $this->surface->resize(-1, -1);
        $offsetY = $baseSurf->topLeft()->getY();
        $perComponentHeight = $baseSurf->height() / count($this->components);
        $perComponentHeight = $perComponentHeight > $this->componentMinHeight ? $perComponentHeight : $this->componentMinHeight;
        foreach ($this->components as $key => $component) {
            if (!$component->hasSurface()) {
                $surf = new Surface(
                    new Position($baseSurf->topLeft()->getX(), $offsetY),
                    new Position($baseSurf->bottomRight()->getX(), $offsetY += $perComponentHeight)
                );
                $component->setSurface($surf);
            } else {
                $offsetY += $component->surface()->height();
            }
        }
        return $this;
    }

//    public function setMenu(Menu $menu)
//    {
//
//    }

    /**
     * @return array|DrawableInterface[]
     */
    public function toComponentsArray(): array
    {
        $components = [];
        $components[] = $this;
        array_push($components, ...$this->components ?? []);
        return $components;
    }

    /**
     * @return bool
     */
    public function isFocused(): bool
    {
        foreach ($this->components as $component) {
            if ($component->isFocused()) {
                return true;
            }
        }
        return $this->focused;
    }

}