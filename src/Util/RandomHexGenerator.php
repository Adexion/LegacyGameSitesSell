<?php

namespace ModernGame\Util;

class RandomHexGenerator
{
    public static function randHexColor(): string
    {
        return sprintf("#%06s", dechex(rand(0, 256**3-1)));
    }
}
