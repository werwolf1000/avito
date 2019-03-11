<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 27.02.2019
 * Time: 15:04
 */

namespace ishop;


class Router
{
    protected static $routes = [];//Маршуты по умолчанию, таблица
    protected static $route = [];//Текущий маршрут

    public static function add($regexp, $route = []){

        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(){
        return self::$routes;
    }

    public static function getRoute(){
        return self::$routes;
    }

    public static function dispatch($url=''){

        if(self::matchRoute($url)){
            $controller = 'app\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller';
            if(class_exists($controller)){
                $controllerObject = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']).'Action';
                if(method_exists($controllerObject, $action)){
                    $controllerObject->$action();
                }
                else{
                    throw new \Exception('Метод '.$action.' не найден', 404);
                }

            }
            else{
                throw new \Exception('Контроллер '.$controller.' не найден', 404);
            }
        }
        else{
           throw new \Exception('Страница не найдена', 404);
        }

    }

    public static function matchRoute($url){
        foreach(self::$routes as $pattern => $route){
            if(preg_match('#'.$pattern.'#', $url, $matches)){
                foreach($matches as $k => $v){
                    if(is_string($k)){
                        $route[$k] = $v;
                    }
                }
                if(empty($route['action'])){
                    $route['action'] = 'index';
                }
                if(empty($route['prefix'])){
                    $route['prefix'] = '';
                }
                else{
                    $route['prefix'] .= '\\';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    //CamelCase
    protected static function upperCamelCase($name){
        $name = preg_replace('/\-/', ' ', $name);
        $name  = ucwords($name);
        $name = preg_replace('/\s/', '', $name);
        return $name;
    }
    //camelCase
    protected static function lowerCamelCase($name){
        return lcfirst(self::upperCamelCase($name));
    }
}