<?php

namespace Base;


class Application
{

    public const CURSOR_INVISIBLE = 0;
    public const CURSOR_NORMAL = 1;
    public const CURSOR_VISIBLE = 2;

    /** @var int|null $lastValidKey */
    protected $lastValidKey;

    /** @var int */
    protected $maxWidth;

    /** @var int */
    protected $maxHeight;

    /** @var array */
    protected $layers = [];

    /** @var bool */
    protected $repeatingKeys = false;

    /** @var bool */
    protected $singleLayerFocus;
    /**
     * @var int
     */
    protected $currentWindowIndex;

    public function __construct(?int $startingKey = null)
    {
        ncurses_init();
        if (ncurses_has_colors()) {
            ncurses_start_color();
            $this->initColorPairs();
        }
        //ncurses_echo();
        ncurses_noecho();
        ncurses_nl();
        //ncurses_nonl();
        ncurses_curs_set(self::CURSOR_INVISIBLE);

        $this->lastValidKey = $startingKey;
    }

    /**
     * @param int|null $timeout
     * @return int|null
     */
    public function getNonBlockCh(?int $timeout = null): ?int
    {
        $read = array(STDIN);
        $null = null;    // stream_select() uses references, thus variables are necessary for the first 3 parameters
        if (stream_select($read, $null, $null, floor($timeout / 1000000), $timeout % 1000000) != 1) {
            $key = null;
        } else {
            $key = ncurses_getch();
        }
        if ($this->repeatingKeys) {
            $key = $key ?? $this->getLastValidKey();
            $this->lastValidKey = $key ?? $this->lastValidKey;
        }
        return $key;
    }

    /**
     * @return int
     */
    public function getCh(): int
    {
        return ncurses_getch();
    }

    /**
     * @param int $micros
     * @return Application
     */
    public function refresh(int $micros): self
    {
        ncurses_refresh(0);
        usleep($micros);
        ncurses_erase();
        return $this;
    }

    /**
     * @param \Closure $callback
     */
    public function handle(\Closure $callback): void
    {
        $this->currentWindowIndex = 0;
        while (true) {
            $key = $this->getNonBlockCh(100000); // use a non blocking getch() instead of $ncurses->getCh()
            $callback($this, $key);
            foreach ($this->layers as $layer) {
                ncurses_color_set(Colors::BLACK_WHITE);
                $layer->draw($key);
            }
            $this->refresh(100000);
        }
    }

    /**
     * @return Application
     */
    public function exit(): self
    {
        ncurses_echo();
        ncurses_curs_set(self::CURSOR_VISIBLE);
        ncurses_end();
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLastValidKey(): ?int
    {
        return $this->lastValidKey;
    }

    /**
     * @param DrawableInterface $layer
     * @return $this
     */
    public function addLayer(DrawableInterface $layer): self
    {
        $this->layers[] = $layer;
        return $this;
    }

    /**
     * @return array|DrawableInterface[]
     */
    public function getLayers(): array
    {
        return $this->layers;
    }

    protected function initColorPairs(): void
    {
        ncurses_init_pair(Colors::BLACK_WHITE, NCURSES_COLOR_WHITE, NCURSES_COLOR_BLACK);
        ncurses_init_pair(Colors::WHITE_BLACK, NCURSES_COLOR_BLACK, NCURSES_COLOR_WHITE);
        ncurses_init_pair(Colors::BLACK_YELLOW, NCURSES_COLOR_YELLOW, NCURSES_COLOR_BLACK);
    }

    /**
     * @param bool $repeatingKeys
     * @return Application
     */
    public function setRepeatingKeys(bool $repeatingKeys): self
    {
        $this->repeatingKeys = $repeatingKeys;
        return $this;
    }

    /**
     * @param bool $singleLayerFocus
     * @return Application
     */
    public function setSingleLayerFocus(bool $singleLayerFocus): self
    {
        $this->singleLayerFocus = $singleLayerFocus;
        return $this;
    }
}