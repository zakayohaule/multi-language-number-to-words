<?php


namespace ZakayoHaule\N2W;


use ZakayoHaule\N2W\Converters\EnglishConverter;
use ZakayoHaule\N2W\Converters\SwahiliConverter;

class ConvertNumber
{
    public function __construct()
    {
    }

    public static function convert($number, $lang=null)
    {
        if (!in_array($lang, self::getSupportedLanguages())  && $lang != null){
            throw new \Exception("Unsupported language");
        }
        elseif ($lang == null) {
            $lang = self::getDefaultLanguage();
        }

        if ($lang == "sw") {
            return SwahiliConverter::convert($number);
        } else if ($lang == "en") {
            return EnglishConverter::convert($number);
        }
    }

    public static function getDefaultLanguage()
    {
        return config("n2w.default-language") != null ? config("n2w.default-language") : "sw";
    }

    public static function getSupportedLanguages()
    {
        return [
          "sw",
          "en"
        ];
    }
}