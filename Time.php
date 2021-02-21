<?php

namespace AdvancedBan\DataBase;

use AdvancedBan\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\math\Vector3;

class Time {

    const VALID_FORMATS = ["minutes", "hours", "seconds", "days"];

    /**
     * @param String $timeFormat|null
     */
    public static function intToString(String $timeFormat = null){
        $format = str_split($timeFormat);
        $time = null; 
        for($i = 0; $i < count($format); $i++){
            switch($format[$i]){
                case "m":
                $time = "minutes";
                break;
                case "h":
                $time = "hours";
                break;
                case "d":
                $time = "days";
                break;
                case "s":
                $time = "seconds";
                break;
            }
        }
        return $time;
    }

    /**
     * @param String $timeFormat|null
     * @return Int|null
     */
    public static function stringToInt(String $timeFormat = null) : ?Int {
        $format = str_split($timeFormat);
        for($i = 0; $i < count($format); $i++){
            if(is_numeric($format[$i])){
                return $format[$i];
            }
        }
    }

    /**
     * @param Int $time
     * @param String $timeFormat
     * @return Int
     */
    public static function getFormatTime(Int $time, String $timeFormat) : Int {
        $value = null;
        switch(self::intToString($timeFormat)){
            case "minutes":
            $value = time() + ($time * 60);
            break;
            case "hours":
            $value = time() + ($time * 3600);
            break;
            case "days":
            $value = time() + ($time * 86400);
            break;
            case "seconds":
            $value = time() + ceil($time);
            break;
        }
        return $value;
    }
}

?>