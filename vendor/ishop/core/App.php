<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 25.02.2019
 * Time: 13:14
 */

namespace ishop;


class App
{
    public static $app;

    /**
     *
     */
    public function __construct(){

        //session_start();

        self::$app = Registry::getInstance();
        $this->getParams();

        new ErrorHandler();

        Router::dispatch();
    }

    /**
     *
     */
    protected function getParams(){

        $params = require_once CONF.'/params.php';

        if(!empty($params)){
            foreach($params as $k => $v){
                    self::$app->setProperties($k, $v);
            }
        }
    }
}