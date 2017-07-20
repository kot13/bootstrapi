<?php
namespace App\Common;

class Helper
{
    /**
     * @param string  $string
     * @param bool    $capitalizeFirstChar
     *
     * @return string
     */
    public static function dashesToCamelCase($string, $capitalizeFirstChar = false)
    {
        return static::replace($string, '-', $capitalizeFirstChar);
    }

    /**
     * @param string $string
     * @param bool   $capitalizeFirstChar
     *
     * @return string
     */
    public static function underscoreToCamelCase($string, $capitalizeFirstChar = false)
    {
        return static::replace($string, '_', $capitalizeFirstChar);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public static function generateRandomString($length = 32)
    {
        $chars      = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789';
        $charsCount = strlen($chars);

        srand((double)microtime() * 1000000);
        $i     = 1;
        $token = '';

        while ($i <= $length) {
            $num = rand() % $charsCount;
            $tmp = substr($chars, $num, 1);
            $token .= $tmp;
            $i++;
        }

        return $token;
    }

    /**
     * @param string $string
     * @param string $symbol
     * @param bool   $capitalizeFirstChar
     *
     * @return mixed|string
     */
    private static function replace($string, $symbol, $capitalizeFirstChar = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace($symbol, ' ', $string)));

        if (!$capitalizeFirstChar) {
            $str = lcfirst($str);
        }

        return $str;
    }
}
