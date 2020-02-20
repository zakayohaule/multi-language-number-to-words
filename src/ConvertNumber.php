<?php


namespace Zakayo\Haule;


use Zakayo\Haule\Converters\EnglishConverter;
use Zakayo\Haule\Converters\SwahiliConverter;

class ConvertNumber
{
    public static function convert($number, $lang)
    {
        if ($lang == "sw") {
            return SwahiliConverter::convert($number);
        } else if($lang == "en") {
            return EnglishConverter::convert($number);
        }
    }
}