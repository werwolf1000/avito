<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 25.02.2019
 * Time: 15:19
 */

namespace ishop;


class Registry
{
    use TSingletone;

    public static $properties;

    public function setProperties($name, $value){

        self::$properties[$name] = $value;
    }

    public function getProperty($name){

        if(isset($properties[$name])){
            return self::$properties[$name];
        }
        return null;
    }

    public function getProperties(){

        return self::$properties;
    }
}