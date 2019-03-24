<?php
/**
 * Created by PhpStorm.
 * User: werwolf
 * Date: 009 09.03.19
 * Time: 22:36
 */

namespace app\models;


use ishop\connect\Connect;

class AvitoListLink extends Connect
{
    public function getLinks(){

        $queryBuilder = $this->conn->createQueryBuilder();

        $queryBuilder->select(
            'id',
            'link'
        );
        $queryBuilder->from('avito.avitolistlink');
        $queryBuilder->where('avitolistlink.process = 0');
        $stmt = $queryBuilder->execute();
        return $stmt->fetchAll();
    }

}