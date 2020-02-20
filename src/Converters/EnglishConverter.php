<?php


namespace Zakayo\Haule\Converters;


class EnglishConverter
{
    public static function convert($number)
    {
        $words = "";
        $length = strlen($number);

//        if ($number[1] != 0)
//            return "true";
//        return "false";

        if ($number == 0) {
            return "zero";
        }

        if ($length == 1) {
            $words .= self::getOnesToWords($number);
        } else if ($length == 2) {
            self::processTens($number, $words);
        } else if ($length == 3) {
            self::processHundreds($number, $words);
        } else if ($length == 4) {
            self::processThousands($number, $words);
        } else if ($length == 5) {
            self::processTenThousands($number, $words);
        } else if ($length == 6) {
            self::processHundredThousands($number, $words);
        } elseif (self::isMillion($number)) {
            self::processMillions($number, $words);
        } elseif (self::isBillion($number)) {
            self::processBillions($number, $words);
        } elseif (self::isTrillion($number)) {
            self::processTrillions($number, $words);
        }

        return $words;
    }

    public static function processTens($number, &$words)
    {
        if ($number[0] == 0 || strlen($number) != 2) {
            return $words .= self::convert($number[1]);
        }

        if ($number[0] == 1)
            $words .= self::getElevenToNineteenToWords($number[1]);
        else
            $words .= self::getTensToWords($number) . " " . self::getOnesToWords($number[1]);

    }

    public static function processHundreds($number, &$words)
    {
        $number = ltrim($number, "0");

        if (strlen($number) != 3) {
            return self::convert($number);
        }

        $words .= self::getHundredsToWords($number[0]);
        if (self::hasAllTrailingZeros($number)) {
            return $words;
        } else {
            $words .= " and ";
        }
        self::processTens(substr($number, 1), $words);
    }

    public static function processThousands($number, &$words)
    {
        $number = ltrim($number, "0");
        if (strlen($number) != 4) {
            $words .= self::convert($number);
        } else {
            $words .= self::getThousandsToWords($number[0]);
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } elseif ($number[1] != 0) {
                $words .= " ";
                self::processHundreds(substr($number, 1), $words);
            } else {
                $words .= " and ";
                self::processTens(substr($number, 2), $words);
            }
        }
    }

    public static function processTenThousands($number, &$words)
    {
        $number = ltrim($number, "0");
        if (strlen($number) != 5) {
            $words .= self::convert($number);
        } else {
            $words .= self::getTenThousandsToWords($number);
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } elseif ($number[2] != 0) {
                $words .= " ";
                self::processHundreds(substr($number, 2), $words);
            } else {
                $words .= " and ";
                self::processTens(substr($number, 3), $words);
            };
        }
    }

    public static function processHundredThousands($number, &$words)
    {
        $number = ltrim($number, "0");
        if (strlen($number) != 6) {
            $words .= self::convert($number);
        } else {
            $words .= self::getHundredThousandsToWords(substr($number, 0, 3));
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } elseif ($number[3] != 0) {
                $words .= " ";
                self::processHundreds(substr($number, 3), $words);
            } else {
                $words .= " and ";
                self::processTens(substr($number, 4), $words);
            };
        }
    }

    public static function processMillions($number, &$words)
    {
        $number = ltrim($number, "0");
        if (strlen($number) < 7) {
            $words .= self::convert($number);
        } else {
            $digits = 0;
            $length = strlen($number);
            if ($length == 7)
                $digits = 1;
            elseif ($length == 8)
                $digits = 2;
            elseif ($length == 9)
                $digits = 3;

            $words .= self::getMillionsToWords($number, $digits);
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } elseif (self::hasAllZeroesExpectTens($number)) {
                $words .= " and ";
                self::processTens(substr($number, -2), $words);
            } else {
                $words .= " ";
                self::processHundredThousands(substr($number, -6), $words);
            };
        }
    }

    public static function processBillions($number, &$words)
    {
        $number = ltrim($number, "0");
        $length = strlen($number);
        if ($length < 10) {
            $words .= self::convert($number);
        } else {
            $digits = 0;
            if ($length == 10)
                $digits = 1;
            elseif ($length == 11)
                $digits = 2;
            elseif ($length == 12)
                $digits = 3;

            $words .= self::getBillionsToWords($number, $digits);
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } elseif (self::hasAllZeroesExpectTens($number)) {
                $words .= " and ";
                self::processTens(substr($number, -2), $words);
            } else {
                $words .= " ";
                self::processMillions(substr($number, -9), $words);
            };
        }
    }

    public static function processTrillions($number, &$words)
    {
        $number = ltrim($number, "0");
        $length = strlen($number);
        if ($length < 13) {
            $words .= self::convert($number);
        } else {
            $digits = 0;
            if ($length == 13)
                $digits = 1;
            elseif ($length == 14)
                $digits = 2;
            elseif ($length == 15)
                $digits = 3;


            $words .= self::getTrillionToWords($number, $digits);
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } elseif (self::hasAllZeroesExpectTens($number)) {
                $words .= " and ";
                self::processTens(substr($number, -2), $words);
            } else {
                $words .= " ";
                self::processBillions(substr($number, -12), $words);
            };
        }
    }

    public static function getOnesToWords($aNumber)
    {
        switch ($aNumber) {
            case 0:
                return "";
            case 1:
                return "one";
            case 2:
                return "two";
            case 3:
                return "three";
            case 4:
                return "four";
            case 5:
                return "five";
            case 6:
                return "six";
            case 7:
                return "seven";
            case 8:
                return "eight";
            case 9:
                return "nine";
        }
    }

    public static function getTensToWords($number)
    {
        $aNumber = $number[0];
        switch ($aNumber) {
            case 1:
                return self::getElevenToNineteenToWords($number[1]);
            case 2:
                return "twenty";
            case 3:
                return "thirty";
            case 4:
                return "forty";
            case 5:
                return "fifty";
            case 6:
                return "sixty";
            case 7:
                return "seventy";
            case 8:
                return "eighty";
            case 9:
                return "ninety";
        }
    }

    public static function getElevenToNineteenToWords($aNumber)
    {
        switch ($aNumber) {
            case 0:
                return "ten";
            case 1:
                return "eleven";
            case 2:
                return "twelve";
            case 3:
                return "thirteen";
            case 4:
                return "fourteen";
            case 5:
                return "fifteen";
            case 6:
                return "sixteen";
            case 7:
                return "eighteen";
            case 8:
                return "nineteen";
            case 9:
                return "twenty";
        }
    }

    public static function getHundredsToWords($aNumber)
    {
        if ($aNumber == 0)
            return "";
        else
            return self::getOnesToWords($aNumber) . " hundred";
    }

    public static function getThousandsToWords($aNumber)
    {
        if ($aNumber == 0)
            return "";
        return self::getOnesToWords($aNumber) . " thousand";
    }

    public static function getTenThousandsToWords($number)
    {
        /*
         * Trim the number to remove leading 0s
         * */
        $aNumber = substr($number, 0, 2);
        if ($aNumber == 0)
            return "";
        else
            return self::convert($aNumber) . " thousand";
    }

    public static function getHundredThousandsToWords($number)
    {
        if ($number == 0)
            return "";
        return self::convert($number) . " thousand";
    }

    public static function getMillionsToWords($number, $digits)
    {
        $aNumber = substr($number, 0, $digits);
        if ($aNumber == 0)
            return "";
        else {
            return self::convert($aNumber) . " million";
        }
    }

    public static function getBillionsToWords($number, $digits)
    {
        $aNumber = substr($number, 0, $digits);
        if ($aNumber == 0)
            return "";
        else {
            return self::convert($aNumber) . " billion";
        }
    }

    public static function getTrillionToWords($number, $digits)
    {
        $aNumber = substr($number, 0, $digits);
        if ($aNumber == 0)
            return "";
        else {
            return self::convert($aNumber) . " trillion";
        }
    }

    public static function isMillion($number)
    {
        if (strlen($number) > 6 && strlen($number) < 10)
            return true;
        return false;
    }

    public static function isBillion($number)
    {
        if (strlen($number) > 9 && strlen($number) < 13)
            return true;
        return false;
    }

    public static function isTrillion($number)
    {
        if (strlen($number) > 12 && strlen($number) < 16)
            return true;
        return false;
    }

    public static function hasAllTrailingZeros($number)
    {
        //Check if a number has trailing zeros i.e 10,000 or 100,000 or 1,000,000
        if (strlen($number) == 3) {
            if (substr($number, -2) == 0) {
                return true;
            } else {
                return false;
            }
        }
        if (strlen($number) == 4) {
            if (substr($number, -3) == 0) {
                return true;
            } else {
                return false;
            }
        }
        if (strlen($number) == 5) {
            if (substr($number, 2) == 0)
                return true;
            else return false;
        } else if (strlen($number) == 6) {
            if (substr($number, 1) == 0)
                return true;
            return false;
        } else if (strlen($number) > 6 && strlen($number) < 10) {
            if (substr($number, -6) == 0)
                return true;
            return false;
        } else if (strlen($number) > 9 && strlen($number) < 13) {
            if (substr($number, -9) == 0)
                return true;
            return false;
        } else if (strlen($number) > 12 && strlen($number) < 16) {
            if (substr($number, -12) == 0)
                return true;
            return false;
        }

        return false;
    }

    public static function hasAllZeroesExpectTens($number)
    {
//        10,000,000,000
        $length = strlen($number);

        if ($length >= 4 && $length <= 6) {
            if ($number[-3] == 0) {
                return true;
            } else {
                return false;
            }
        } else if (self::isMillion($number)) {
            if (substr($number, -6, 4) == 0)
                return true;
            return false;
        } else if (self::isBillion($number)) {
            if (substr($number, -9, 7) == 0)
                return true;
            return false;
        } else if (self::isBillion($number)) {
            if (substr($number, -12, 10) == 0)
                return true;
            return false;
        } else if(self::isTrillion($number)) {
            if (substr($number, -15, 13) == 0)
                return true;
            return false;
        }

        return false;
    }
}