<?php
/**
 * Created by PhpStorm.
 * User: werwolf
 * Date: 021 21.04.19
 * Time: 15:25
 */

include_once dirname(__DIR__) . '/config/init.php';
include_once LIBS.'/function.php';
include_once dirname(__DIR__) . '/config/routes.php';


\ishop\Router::add('^$', ['controller' => 'main', 'action' => 'one']);

$app = new \ishop\App();
