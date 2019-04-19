<?php


class Parser
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $meta = [];

    public function __construct(string $text)
    {
        $this->text = str_replace("\n", '', $text);
    }

    /**
     * @throws Exception
     */
    public function getText()
    {
        /** @var Meta|null $previousMeta */
        $previousMeta = null;
        foreach (explode(' ', $this->text) as $word) {
            if(!isset($this->meta[$word])){
                $this->meta[$word] = new Meta($word);
            }
            if($previousMeta){
                $previousMeta->addFollowingWord($this->meta[$word]);
            }
            $previousMeta = $this->meta[$word];
        }

        /** @var Meta $meta */
        $meta = $this->meta[array_rand($this->meta)];
        print ucfirst($meta->getWord()). ' ';
        $previousMeta = $meta;
        while ($meta = $meta->getNextRandomMeta()){
            if($previousMeta->isEndingWord()){
                print ucfirst($meta->getWord()). ' ';
            }else{
                print "{$meta->getWord()} ";
            }
            $previousMeta = $meta;
        }
    }
}