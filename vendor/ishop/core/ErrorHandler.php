<?php
/**
 * Created by PhpStorm.
 * User: anchikin@outlook.com
 * Date: 25.02.2019
 * Time: 22:29
 */

namespace ishop;


use ishop\connect\Connect;

class ErrorHandler extends Connect
{

    public function __construct(){
        parent::__construct();
        if(DEBUG){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
        else{
            error_reporting(0);
        }

        set_exception_handler([$this, 'customErrorHandler']);

    }

    /**
     * @param $e
     */
    public function customErrorHandler($e){

        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
        $this->displayError('исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    protected function logErrors($message = '', $file = '', $line = '', $code = ''){
        error_log("[".date('Y-m-d H-i-s')."] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\r\n", 3, ROOT.'/tmp/error.log');

        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder->insert('avito.error');
        $queryBuilder->values([
            'msg' => '?',
            'file' => '?',
            'line' => '?',
            'code' => '?'
        ]);
        $queryBuilder->setParameter(0, $message);
        $queryBuilder->setParameter(1, $file);
        $queryBuilder->setParameter(2, $line);
        $queryBuilder->setParameter(3, $code);
        $queryBuilder->execute();

    }

    protected function displayError($errno, $errstr, $errfile, $errline, $response = 404){
        http_response_code($response);
        if($response == 404 && !DEBUG){
            require WWW.'/errors/404.php';
        }
        elseif(DEBUG){
            require WWW.'/errors/dev.php';
        }else{
            require WWW.'/errors/prod.php';
        }
        exit;
    }

}