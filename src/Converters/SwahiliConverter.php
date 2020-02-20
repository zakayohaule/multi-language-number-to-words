<?php


namespace ZakayoHaule\N2W\Converters;


class SwahiliConverter
{
    public static function convert($number)
    {
        $words = "";
        $length = strlen($number);

        if ($number == 0){
            return "sifuri";
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
        $words .= self::getTensToWords($number[0]);
        if ($number[0] != 0 && $number[1] != 0) {
            $words .= " na ";
        }
        $words .= self::getOnesToWords($number[1]);
    }

    public static function processHundreds($number, &$words)
    {
        $words .= self::getHundredsToWords($number[0]);
        if (self::hasAllTrailingZeros($number)) {
            return $words;
        } elseif ($number[1] != 0) {
            $words .= " ";
        } else {
            $words .= " na ";
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
            } elseif ($number[-3] == 0) {
                $words .= " na ";
            } else {
                $words .= " ";
            }
            self::processHundreds(substr($number, 1), $words);
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
            } else if (self::needsSpecialFormat($number) || $number[-3] == 0)
                $words .= " na ";
            else {
                $words .= " ";
            }
            self::processHundreds(substr($number, -3), $words);
        }
    }

    public static function processHundredThousands($number, &$words)
    {
        $number = ltrim($number, "0");
        if (strlen($number) != 6) {
            $words .= self::convert($number);
        } else {
            $words .= self::getHundredThousandsToWords(substr($number, -6, 1));
            if (self::hasAllTrailingZeros($number)) {
                return $words;
            } else {
                $tenThousands = substr($number, -5);
                $words .= " na ";
                self::processTenThousands($tenThousands, $words);
            }
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
            } else if (self::needsSpecialFormat($number) || substr($number, -6, 2) == 0)
                $words .= " na ";
            else
                $words .= ", ";

            $hundredThousands = substr($number, -6);
            self::processHundredThousands($hundredThousands, $words);
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
            } else if (self::needsSpecialFormat($number) || substr($number, -9, 5) == 0) {
                $words .= " na ";
            } else
                $words .= ", ";

            $millions = substr($number, -9);
            self::processMillions($millions, $words);
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
            } elseif (self::needsSpecialFormat($number) || substr($number, -12, 8) == 0)
                $words .= " na ";
            else
                $words .= ", ";

            $millions = substr($number, -12);
            self::processBillions($millions, $words);
        }
    }

    public static function getOnesToWords($aNumber)
    {
        switch ($aNumber) {
            case 1:
                return "moja";
            case 2:
                return "mbili";
            case 3:
                return "tatu";
            case 4:
                return "nne";
            case 5:
                return "tano";
            case 6:
                return "sita";
            case 7:
                return "saba";
            case 8:
                return "nane";
            case 9:
                return "tisa";
        }
    }

    public static function getTensToWords($aNumber)
    {
        switch ($aNumber) {
            case 1:
                return "kumi";
            case 2:
                return "ishirini";
            case 3:
                return "thelathini";
            case 4:
                return "arobaini";
            case 5:
                return "hamsini";
            case 6:
                return "sitini";
            case 7:
                return "sabini";
            case 8:
                return "themanini";
            case 9:
                return "tisini";
        }
    }

    public static function getHundredsToWords($aNumber)
    {
        if ($aNumber == 1)
            return "mia moja";
        if ($aNumber == 0)
            return "";
        else
            return "mia " . self::getOnesToWords($aNumber);
    }

    public static function getThousandsToWords($aNumber)
    {
        if ($aNumber == 0)
            return "";
        return "elfu " . self::getOnesToWords($aNumber);
    }

    public static function getTenThousandsToWords($number)
    {
        /*
         * Trim the number to remove leading 0s
         * */
        $aNumber = ltrim(substr($number, -5, 2), "0");
        if ($aNumber == 0)
            return "";
        else if ($aNumber < 10 || self::hasAllTrailingZeros($number))
            return "elfu " . self::convert($aNumber);
        return self::convert($aNumber) . " elfu";
    }

    public static function getHundredThousandsToWords($aNumber)
    {
        if ($aNumber == 0)
            return "";
        return "laki " . self::getOnesToWords($aNumber);
    }

    public static function getMillionsToWords($number, $digits)
    {
        $aNumber = substr($number, -strlen($number), $digits);
        if ($aNumber == 0)
            return "";
        /*
         * Check for confusing format i.e
         * 11,000,000 => milioni kumi na moja
         * 10,000,001 => kumi milioni na moja
         *
         * Or if there is a better solution, this will be the place to implement it
         * */
        elseif (self::needsSpecialFormat($number))
            return self::convert($aNumber) . " milioni";
        return "milioni " . self::convert($aNumber);
    }

    public static function getBillionsToWords($number, $digits)
    {
        $aNumber = substr($number, -strlen($number), $digits);
        if ($aNumber == 0)
            return "";
        /*
         * Check for confusing format i.e
         * 11,000,000,000 => bilioni kumi na moja
         * 10,000,000,001 => kumi bilioni na moja
         *
         * Or if there is a better solution, this will be the place to implement it
         * */
        elseif (self::needsSpecialFormat($number))
            return self::convert($aNumber) . " bilioni";
        return "bilioni " . self::convert($aNumber);
    }

    public static function getTrillionToWords($number, $digits)
    {
        $aNumber = substr($number, -strlen($number), $digits);
        if ($aNumber == 0)
            return "";
        /*
         * Check for confusing format i.e
         * 11,000,000,000 => bilioni kumi na moja
         * 10,000,000,001 => kumi bilioni na moja
         *
         * Or if there is a better solution, this will be the place to implement it
         * */
        elseif (self::needsSpecialFormat($number))
            return self::convert($aNumber) . " trilioni";
        return "trilioni " . self::convert($aNumber);
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

    public static function needsSpecialFormat($number)
    {
        $length = strlen($number);

        if (self::isMillion($number)) {
            if (substr($number, -6, 4) == 0 && $length > 7)
                if ($length == 8 && $number[1] == 0 && $number[-2] == 0 && $number[-1] != 0)
                    return true;
                elseif ($length == 9 && $number[2] == 0 && substr($number, -2) != 0)
                    return true;
            return false;
        } else if (self::isBillion($number)) {
            if (substr($number, -9, 7) == 0 && $length > 10)
                if ($length == 11 && $number[1] == 0 && $number[-2] == 0 && $number[-1] != 0)
                    return true;
                elseif ($length == 12 && $number[2] == 0 && substr($number, -2) != 0)
                    return true;
            return false;
        } else if (self::isTrillion($number)) {
            if (substr($number, -12, 10) == 0 && $length > 13)
                if ($length == 14 && $number[1] == 0 && $number[-2] == 0 && $number[-1] != 0)
                    return true;
                elseif ($length == 15 && $number[2] == 0 && substr($number, -2) != 0)
                    return true;
            return false;
        }
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