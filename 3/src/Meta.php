<?php

class Meta
{
    public const META = 'meta';
    public const FREQUENCY = 'frequency';

    /**
     * @var array
     */
    protected $followingWords = [];

    /**
     * @var int
     */
    protected $word = '';

    /**
     * Meta constructor.
     * @param string $word
     */
    public function __construct(string $word)
    {
        $this->word = $word;
    }

    /**
     * @param Meta $meta
     * @return Meta
     */
    public function addFollowingWord(Meta $meta): Meta
    {
        $word = $meta->getWord();
        if (isset($this->followingWords[$word])) {
            $this->followingWords[$word][self::FREQUENCY]++;
        } else {
            $this->followingWords[$word] = [
                self::META => $meta,
                self::FREQUENCY => 1
            ];
        }
        return $meta;
    }

    /**
     * @return Meta|null
     * @throws Exception
     */
    public function getNextRandomMeta(): ?Meta
    {
        if(empty($this->followingWords)){
            return null;
        }
        $randWeight = random_int(1, (int) array_sum(array_column($this->followingWords, self::FREQUENCY)));

        foreach ($this->followingWords as $key => $item) {
            $randWeight -= $item[self::FREQUENCY];
            if ($randWeight <= 0) {
                return $item[self::META];
            }
        }
    }

    /**
     * @return string
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * @return bool
     */
    public function isEndingWord(): bool
    {
        return strpos($this->word, '.') !== FALSE;
    }

}