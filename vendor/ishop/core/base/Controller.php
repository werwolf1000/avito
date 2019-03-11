<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 27.02.2019
 * Time: 18:02
 */

namespace ishop\base;


abstract class Controller
{
    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $data = [];
    public $meta = [];


    public function __construct($route){
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
    }

    public function set($data){
        $this->data = $data;
    }

    public function setMeta($tite = '', $desc = '', $keyword = ''){
        $this->meta['title'] = $tite;
        $this->meta['desc'] = $desc;
        $this->meta['keyword'] = $keyword;
    }
}