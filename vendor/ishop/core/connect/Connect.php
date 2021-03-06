<?php
/**
 * Created by PhpStorm.
 * User: werwolf
 * Date: 009 09.03.19
 * Time: 11:40
 */

namespace ishop\connect;

/**
 * Class Connect
 * @package ishop\connect
 */
abstract class Connect
{
    public $conn;

    use \ishop\TSingletone;

    public function __construct()
    {
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => 'avito',
            'user' => 'werwolf',
            'password' => '2619192',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
        );
        $this->conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $stmt = $this->conn->prepare('set names utf8');
        $stmt->execute();
    }


}