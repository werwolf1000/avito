<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 25.02.2019
 * Time: 15:20
 */

namespace ishop;


trait TSingletone
{
    private static $instance;

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new self;
        }
        return self::$instance;
    }
}