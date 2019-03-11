<?php
/**
 * Created by PhpStorm.
 * User: werwolf
 * Date: 009 09.03.19
 * Time: 21:40
 */

namespace app\models;


use ishop\connect\Connect;

class insertDb extends Connect
{
    /**
     * @param $list
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function insertListAvito($list){
        $values = '';
        foreach($list as $k => $advert){
            $values .= '('.$this->conn->quote($advert['href']).','.$this->conn->quote($advert['name']).','.$this->conn->quote($advert['year']).','.$this->conn->quote($advert['region']).'),';
        }
        $values = trim($values,',');
        $sql = "INSERT IGNORE avito.avitolistlink(link, name, year, region) VALUES $values";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function updateProcess($id){

        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder->update('avito.avitolistlink','l');
        $queryBuilder->set('l.process','?');
        $queryBuilder->where('l.id = ?');
        $queryBuilder->setParameter(0,1);
        $queryBuilder->setParameter(1,$id);
        $queryBuilder->execute();
    }


    public function insertAdvert($id, $arr){

        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('avito.avitoadvert');
        $queryBuilder->values([
            '`id_link`' => '?',
            '`condition`' => '?',
            '`owners`' => '?',
            '`mileage`' => '?',
            '`rudder`' => '?',
            '`drive`' => '?',
            '`color`' => '?',
            '`engine_capacity`' => '?',
            '`model`' => '?',
            '`mark`' => '?',
            '`year_of_issue`' => '?',
            '`body_type`' => '?',
            '`engine_type`' => '?',
            '`transmission`' => '?',
            '`engine_power`' => '?',
            '`number_of_doors`' => '?',
            '`vin`' => '?',
            '`addr`' => '?',
            '`price`' => '?'
        ]);

        $queryBuilder->setParameter(0, $id);
        $queryBuilder->setParameter(1, $arr['condition']);
        $queryBuilder->setParameter(2, $arr['owners']);
        $queryBuilder->setParameter(3, $arr['mileage']);
        $queryBuilder->setParameter(4, $arr['rudder']);
        $queryBuilder->setParameter(5, $arr['drive']);
        $queryBuilder->setParameter(6, $arr['color']);
        $queryBuilder->setParameter(7, $arr['engine_capacity']);
        $queryBuilder->setParameter(8, $arr['model']);
        $queryBuilder->setParameter(9, $arr['mark']);
        $queryBuilder->setParameter(10, $arr['year_of_issue']);
        $queryBuilder->setParameter(11, $arr['body_type']);
        $queryBuilder->setParameter(12, $arr['engine_type']);
        $queryBuilder->setParameter(13, $arr['transmission']);
        $queryBuilder->setParameter(14, $arr['engine_power']);
        $queryBuilder->setParameter(15, $arr['number_of_doors']);
        $queryBuilder->setParameter(16, $arr['vin']);
        $queryBuilder->setParameter(17, $arr['addr']);
        $queryBuilder->setParameter(18, $arr['price']);
        $queryBuilder->execute();
        return $this->conn->lastInsertId();
    }

    public function inserAdvertImages($id,$image){
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('avito.advertimages');
        $queryBuilder->values([
            'adver_id' => '?',
            'host' => '?',
            'image' => '?',
            'small' => '?',
            'big' => '?'
        ]);
        $queryBuilder->setParameter(0, $id);
        $queryBuilder->setParameter(1, $image['host']);
        $queryBuilder->setParameter(2, $image['image']);
        $queryBuilder->setParameter(3, $image['small']);
        $queryBuilder->setParameter(4, $image['big']);
        $queryBuilder->execute();

    }

    public function insertLog($step, $process){
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('avito.log');
        $queryBuilder->values([
            'step' => '?',
            'process' => '?'
        ]);
        $queryBuilder->setParameter(0, $step);
        $queryBuilder->setParameter(1, $process);
        $queryBuilder->execute();
    }
}