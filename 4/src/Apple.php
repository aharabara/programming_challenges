<?php
namespace App;


class Apple extends Point
{
    /**
     * @return $this
     * @throws \Exception
     */
    public function randMove(): self
    {
        $this->x = random_int(0, 40);
        $this->y = random_int(0, 20);
        return $this;
    }
}