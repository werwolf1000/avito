<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 27.02.2019
 * Time: 16:37
 */

namespace app\controllers;

/**
 * Class MainController
 * @package app\controllers
 */
class MainController extends AppController
{

    public $count;
    public $insert_db;

    /**
     * MainController constructor.
     * @param $route
     */
    public function __construct($route)
    {
        parent::__construct($route);
        $this->insert_db = new \app\models\insertDb();
    }

    /**Точка входа
     * @throws \Exception
     */
    public function indexAction(){

        echo "Начало";
        $this->insert_db->insertLog('начало','https://www.avito.ru/moskva/avtomobili?radius=0');
        $model = new \app\models\GetPageAvitoModel();

        $page = $model->GetRequestAvito();

        if(!$page){
            throw new \Exception('Не удалось получить страницу');
        }

        if(!$this->count($page)){
            throw new \Exception('Не удалось получить колличество страниц');
        }

        if(!$this->parseStepOne($page)){
            $this->insert_db->insertLog('конец','новых объявлений нет');
            die("новых объявлений нет");
        }

        for ($p = 2; $p <= $this->count; $p++){
            $page = $model->GetRequestAvito($p);
            if(!$page){
                echo 'Не удалось получить страницу: '.$p;
                $this->insert_db->insertLog('1-й этап','Не удалось получить страницу: '.$p);
                continue;
            }
            $this->parseStepOne($page);
            $this->insert_db->insertLog('список объявлений стр.'.$p,'https://www.avito.ru/moskva/avtomobili?p='.$p.'radius=0');
            sleep(1);
        }

        $this->parseStepTwo();
        $this->insert_db->insertLog('конец','');
    }

    /**
     * @param $page
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    private function parseStepOne($page){
        $xml = new \app\models\XmlPathParse($page);
        $links = $xml->getLinks();
        var_dump($links);
        if(empty($links)){
            $this->insert_db->insertLog('1 этап ','нет ссылок');
            return null;
        }
        $count_rows = $this->insert_db->insertListAvito($links);
        unset($xml);
        return $count_rows;
    }

    private function count($page){
        $xml = new \app\models\XmlPathParse($page);
        $this->count = $xml->countPage();
        $this->insert_db->insertLog('колл. страниц','всего страниц: '.$this->count);
        unset($xml);
        return $this->count;
    }

    /**
     * Второй этап
     */
    private function parseStepTwo(){

        $this->insert_db->insertLog('второй этап','начало');
        $model = new \app\models\GetPageAvitoModel();
        $link_model = new \app\models\AvitoListLink();
        $links = $link_model->getLinks();
        foreach($links as $k => $link){
            $page = $model->GetRequestAvitoAdvert($link['link']);
            $mobile_page = $model->GetRequestAvitoMobileAdvert($link['link']);
            $page = mb_convert_encoding ($page, 'HTML-ENTITIES', 'UTF-8');
            $mobile_page = mb_convert_encoding ($mobile_page, 'HTML-ENTITIES', 'UTF-8');

            $xml = new \app\models\XmlPathParse($page);
            $advert = $xml->getAdvert();
            unset($xml);

            $xml = new \app\models\XmlPathParse($mobile_page);
            $xml->getPhone();
            $advert['phone'] = $xml->getPhone();
            unset($xml);

            $insert_id = $this->insert_db->insertAdvert($link['id'], $advert);
            if($insert_id){
                if(!empty($advert['images'])){
                    foreach($advert['images'] as $image){
                        $this->insert_db->inserAdvertImages($insert_id, $image);
                    }
                }

                $this->insert_db->updateProcess($link['id']);
            }
            $this->insert_db->insertLog('второй этап',$link['link']);
            sleep(1);
        }
    }

}