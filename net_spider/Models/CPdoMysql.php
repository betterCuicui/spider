<?php
class CPdoMysql
{
    private $pdo;
    public function __construct(){
        $config = require $_SERVER['DOCUMENT_ROOT'].'/Models/CDbConfig.php';
        $dsn = $config['mysql']['dsn'];
        $username = $config['mysql']['username'];
        $password = $config['mysql']['password'];

        try{
            $this->pdo = new PDO($dsn,$username,$password);
            $this->pdo->exec("set names utf8");
        }catch (Exception $e){
            SeasLog::info("connect mysql catch exception, info : ".$e->__toString());
        }
    }

    public function operate_by_sql($sql,$sql_param = array()){
        if(empty($sql))
        return array();
        $sth = $this->pdo->prepare($sql);
        $sth->execute($sql_param);
        return $sth->fetch();
    }
}