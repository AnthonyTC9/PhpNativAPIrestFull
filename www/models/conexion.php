<?php

class conexion{
    static public function con(){

        $link = new PDO("mysql:host=172.25.0.1;port=3306;dbname=apirest","root","test");

        //$link->exec("set name utf8");

        return $link;
    }
}

?>