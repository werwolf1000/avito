<?php
/**
 * Created by PhpStorm.
 * User: werwolf
 * Date: 009 09.03.19
 * Time: 13:41
 */

namespace app\models;


class XmlPathParse
{
    public $dom;

    public function __construct($HTMLCode)
    {
        $this->dom = new \DomDocument();
        @$this->dom->loadHTML( $HTMLCode );
    }

    public function countPage(){
        $xpath = new \DomXPath( $this->dom );
        $_res = $xpath->query("//*[@class=\"pagination-page\"]");
        foreach( $_res as $key => $obj ) {
           if($obj->nodeValue == 'Последняя'){
               $href = $obj->getAttribute('href');
               $get = parse_url($href);
               parse_str($get['query'], $get);
               unset($xpath);
               return $get['p'];
           }
        }
        unset($xpath);
        return 3;
    }

    public function getLinks(){
        $xpath = new \DomXPath( $this->dom );
        $arr = [];
        $_res = $xpath->query("//*[@class=\"item_table-wrapper\"]");
        foreach( $_res as $key => $obj ) {

            $arr[$key]['href'] = null;
            $arr[$key]['name'] = null;
            $arr[$key]['year'] = null;
            $arr[$key]['region'] = null;

            $arr[$key]['href'] = $obj->getElementsByTagName('a')->item(0)->getAttribute('href');
            foreach($obj->getElementsByTagName('a')->item(0)->childNodes as $k => $v){
                if($v->nodeName == 'span'){
                    $val = explode(',', $v->nodeValue);
                    $arr[$key]['name'] = trim($val[0]);
                    $arr[$key]['year'] = trim($val[1]);
                }
            }
            $res_p = $xpath->query('div/div[@class="data"]/p/text()', $obj);
            $arr[$key]['region'] = $res_p->item(0)->nodeValue;
        }
        unset($xpath);
        return $arr;
    }


    /**Распарсить объявление
     * @return mixed
     */
    public function getAdvert(){

        $xpath = new \DomXPath( $this->dom );
        $_res = $xpath->query("//*[@class=\"item-params-list-item\"]");
        $arr['condition'] = null;
        $arr['owners'] = null;
        $arr['mileage'] = null;
        $arr['rudder'] = null;
        $arr['drive'] = null;
        $arr['color'] = null;
        $arr['engine_capacity'] = null;
        $arr['model'] = null;
        $arr['mark'] = null;
        $arr['year_of_issue'] = null;
        $arr['body_type'] = null;
        $arr['engine_type'] = null;
        $arr['transmission'] = null;
        $arr['engine_power'] = null;
        $arr['number_of_doors'] = null;
        $arr['vin'] = null;
        $arr['addr'] = null;
        $arr['price'] = null;
        foreach( $_res as $key => $obj ) {
            if(trim($obj->childNodes[1]->nodeValue) == 'Состояние:'){
                $arr['condition'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Владельцев по ПТС:'){
                $arr['owners'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Пробег:'){

                $arr['mileage'] = preg_replace('/\W/', '', $obj->childNodes[2]->nodeValue);

                $arr['mileage'] = intval($arr['mileage']);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Руль:'){
                $arr['rudder'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Привод:'){
                $arr['drive'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Цвет:'){
                $arr['color'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Объём двигателя:'){

                $arr['engine_capacity'] = floatval(trim($obj->childNodes[2]->nodeValue));
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Модель:'){
                $arr['model'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Марка:'){
                $arr['mark'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Год выпуска:'){
                $arr['year_of_issue'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Тип кузова:'){
                $arr['body_type'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Тип двигателя:'){
                $arr['engine_type'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Коробка передач:'){
                $arr['transmission'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Мощность двигателя:'){
                $arr['engine_power'] = trim(preg_replace('/л\.с\./', '', $obj->childNodes[2]->nodeValue));
                $arr['engine_power'] = intval(trim($obj->childNodes[2]->nodeValue));
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'Количество дверей:'){
                $arr['number_of_doors'] = trim($obj->childNodes[2]->nodeValue);
            }
            if(trim($obj->childNodes[1]->nodeValue) == 'VIN или номер кузова:'){
                $arr['vin'] = trim($obj->childNodes[2]->nodeValue);
            }
        }
        $_ad = $xpath->query('//*[@itemprop="streetAddress"]');

        foreach($_ad as $addr){
            $arr['addr'] = $addr->nodeValue;
        }




        $_pr = $xpath->query('//*[@class="item-view-header "]/descendant::span[@itemprop=\'price\']');
        foreach($_pr as $price){
            $arr['price'] = $price->getAttribute('content');
        }
        //$arr['price'] = $_pr->item(0)->getAttribute('content');

        $_imgs = $xpath->query('//*[@class="gallery-list-item-link"]/attribute::style');
        foreach($_imgs as $k => $img){
            $images = explode('/',preg_replace('/(background-image: url\(\/\/)(.*)(\)\;)/', '$2', $img->value));
            $arr['images'][$k]['host'] = $images[0];
            $arr['images'][$k]['small'] = $images[1];
            $arr['images'][$k]['big'] = '640x480';
            $arr['images'][$k]['image'] = $images[2];
        }
        unset($xpath);
        return $arr;
    }

    public function getPhone(){
        $xpath = new \DomXPath( $this->dom );
        $_res = $xpath->query('//*[@data-marker="item-contact-bar/call"]');
        $phone = '';
        foreach( $_res as $key => $obj ) {
            $str = $obj->getAttribute('href');
            $phone = preg_replace('#tel:#','',$str);

        }
        return $phone;
    }


    public function __destruct()
    {
        unset($this->dom);
    }
}