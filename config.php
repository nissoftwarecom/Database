<?php

class database{

    protected $db;
    protected $active_group = 'local';
    protected $link;
    function __construct(){
        $this->db['local'] = array(
            'dsn'   => '',
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'exam',
            'dbprefix' => '',
        );
        $this->db['serverskm'] = array(
            'dsn'   => '',
            'hostname' => '109.235.64.249',
            'username' => 'conserv_skm',
            'password' => 'P@ssw0rd',
            'database' => 'conserv_skm',
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
        ); 
    

    $this->link = mysqli_connect($this->db[$this->active_group]['hostname'], $this->db[$this->active_group]['username'], $this->db[$this->active_group]['password'],$this->db[$this->active_group]['database']);
    /*  if (!$link) {
    die('Could not connect: ' . mysqli_error());
    }
    echo 'Connected successfully';
    mysqli_close($link);*/
    }
    
	function query($query){
        $result=mysqli_query($this->link,$query);
        if($result){
            return $result;
        }else{
            return 0;
        }
    }
    

    function result($result){ 
        $res=array();
        while ($row = $result->fetch_assoc()) {
            $res[]=$row;
        }
        return $res;
    }
	
function con(){
        if (!$this->link) {
            die('Could not connect: ' . mysqli_error());
            }
            echo 'Connected successfully';
    }
function install(){
    $q[]="CREATE TABLE IF NOT EXISTS `smtp` (`id` int(11) NOT NULL AUTO_INCREMENT,`user` varchar(30) NOT NULL,
        `pass` varchar(64) NOT NULL,`smtp` varchar(40) NOT NULL,`crypto` varchar(3) NOT NULL DEFAULT 'ssl',
        `port` int(2) NOT NULL DEFAULT '465',`day_limit` int(3) NOT NULL,`hour_limit` int(11) NOT NULL,
        `day_count` int(11) NOT NULL,`hour_count` int(11) NOT NULL,`health` tinyint(1) NOT NULL DEFAULT '1',
        `total_count` int(3) NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1";
    $q[]="CREATE TABLE IF NOT EXISTS `contact` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(40) NOT NULL,
        `email` varchar(70) NOT NULL,`phone` varchar(15) NOT NULL,`country` varchar(20) NOT NULL,
        UNIQUE KEY `email` (`email`),KEY `id` (`id`)) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";
    $q[]="CREATE TABLE IF NOT EXISTS `log` (`id` int(11) NOT NULL AUTO_INCREMENT,`email` varchar(56) NOT NULL,
        `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";
    $q[]="CREATE TABLE IF NOT EXISTS `blacklist` (`id` int(11) NOT NULL AUTO_INCREMENT,`email` varchar(70) NOT NULL,
        PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1";
    $q[]="create VIEW IF NOT EXISTS `fresh_list` AS SELECT `contact`.`id` AS `id`, `contact`.`name` AS `name`, `contact`.`email` AS `email`, `contact`.`phone` AS `phone`, `contact`.`country` AS `country` FROM `contact` WHERE ( ( NOT( `contact`.`email` IN( SELECT `log`.`email` FROM `log` ) ) ) AND( NOT( `contact`.`email` IN( SELECT `blacklist`.`email` FROM `blacklist` ) ) ) )";
    $q[]="CREATE VIEW `get_onesmtp` AS SELECT `smtp`.`id` AS `id`, `smtp`.`user` AS `user`, `smtp`.`pass` AS `pass`, `smtp`.`smtp` AS `smtp`, `smtp`.`crypto` AS `crypto`, `smtp`.`port` AS `port` FROM `smtp` WHERE ( ( `smtp`.`day_limit` > `smtp`.`day_count` ) AND( `smtp`.`hour_limit` > `smtp`.`hour_count` ) AND(`smtp`.`health` = 1) ) ORDER BY `smtp`.`hour_count` LIMIT 0, 1";
    foreach($q as $v){
        $this->query($v);
    }
}

    function __destruct(){
        mysqli_close($this->link);
        //echo "mysql_close";
    }

}



?>