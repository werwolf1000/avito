<?php
/**
 * Created by PhpStorm.
 * User: werwolf
 * Date: 009 09.03.19
 * Time: 11:38
 */

namespace app\models;


use ishop\connect\Connect;

class GetPageAvitoModel extends Connect
{

    public $host = 'https://www.avito.ru';
    public $get = [];

    /**
     * @param int $p
     * @return bool|string
     */
    public function GetRequestAvito($p = 1){
        if($p != 1){
            $this->get['p'] = $p;
        }
        //$this->get['radius'] = 0;
        $page = $this->getData('https://www.avito.ru/rossiya/avtomobili');
        return $page;
    }


    /**
     * @param $link
     * @return bool|string
     */
    public function GetRequestAvitoAdvert($link){
        $url = $this->host.$link;
        $page = $this->getData($url);
        return $page;
    }
    /**
     * @param $link
     * @return bool|string
     */
    public function GetRequestAvitoMobileAdvert($link){
        $url = $this->host.$link;
        $page = $this->getDataMobile($url);
        return $page;
    }

    /**
     * @param $url
     * @param string $post
     * @param string $referer
     * @return bool|string
     */
    function getData($url)
    {

        $params = \ishop\App::$app->getProperties();
        if(!empty($this->get)){
            $url .= '?'.http_build_query($this->get);
        }
        // ip и порт прокси
        $ip = $params['proxy']['ip'][array_rand ($params['proxy']['ip'],1)];
        $port = $params['proxy']['port'];
        $proxy = $ip.':'.$port;
        // если требуется авторизация на прокси-сервере
        $proxyauth = $params['proxy']['login'].':'.$params['proxy']['password'];

        $cl = curl_init();
        curl_setopt($cl, CURLOPT_URL,  $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cl, CURLOPT_HEADER,false);
        curl_setopt($cl, CURLOPT_TIMEOUT, 5);
        curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, false);

        // подключение к прокси-серверу
        curl_setopt($cl, CURLOPT_PROXY, $proxy);
        // если требуется авторизация
        curl_setopt($cl, CURLOPT_PROXYUSERPWD, $proxyauth);

        curl_setopt($cl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($cl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.2.15 Version/10.10');
        curl_setopt($cl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'ParserNKO/cookie.txt');
        curl_setopt($cl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'ParserNKO/cookie.txt');
        if(!empty($post)) {
            curl_setopt($cl, CURLOPT_POST, 1); curl_setopt($cl, CURLOPT_POSTFIELDS, $post);}else{curl_setopt($cl, CURLOPT_POST, 0);}
        if(!empty($referer)){curl_setopt($cl, CURLOPT_REFERER, $referer);}else{curl_setopt($cl,CURLOPT_REFERER, 1);}
        $ex=curl_exec($cl);

        if($ex === false)
        {
            throw new \Exception(curl_error($cl));
        }
        curl_close($cl);
        return $ex;
    }

    /**
     * @param $url
     * @param string $post
     * @param string $referer
     * @return bool|string
     */
    function getDataMobile($url)
    {

        $params = \ishop\App::$app->getProperties();

        $url .= '?'.http_build_query($this->get);
        // ip и порт прокси
        $ip = $params['proxy']['ip'][array_rand ($params['proxy']['ip'],1)];
        $port = $params['proxy']['port'];
        $proxy = $ip.':'.$port;
        // если требуется авторизация на прокси-сервере
        $proxyauth = $params['proxy']['login'].':'.$params['proxy']['password'];

        $cl = curl_init();
        curl_setopt($cl, CURLOPT_URL,  $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($cl, CURLOPT_HEADER,false);
        curl_setopt($cl, CURLOPT_TIMEOUT, 5);
        curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, false);

        // подключение к прокси-серверу
        curl_setopt($cl, CURLOPT_PROXY, $proxy);
        // если требуется авторизация
        curl_setopt($cl, CURLOPT_PROXYUSERPWD, $proxyauth);

        curl_setopt($cl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($cl, CURLOPT_USERAGENT, 'Firefox 28/Android: Mozilla/5.0 (Android; Mobile; rv:28.0) Gecko/24.0 Firefox/28.0');
        curl_setopt($cl, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'].'ParserNKO/cookie.txt');
        curl_setopt($cl, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'].'ParserNKO/cookie.txt');
        if(!empty($post)) {
            curl_setopt($cl, CURLOPT_POST, 1); curl_setopt($cl, CURLOPT_POSTFIELDS, $post);}else{curl_setopt($cl, CURLOPT_POST, 0);}
        if(!empty($referer)){curl_setopt($cl, CURLOPT_REFERER, $referer);}else{curl_setopt($cl,CURLOPT_REFERER, 1);}
        $ex=curl_exec($cl);
        if($ex === false)
        {
            throw new \Exception(curl_error($cl));
        }
        curl_close($cl);
        return $ex;
    }
}