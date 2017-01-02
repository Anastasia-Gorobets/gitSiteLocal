<?php

class DataBase
{
    private $mysqli;
    private $dbConfig;
    private  $perPage=20;
    public function __construct()
    {
        $this->dbConfig = require "db/database_config.php";
        $this->mysqli = mysqli_connect($this->dbConfig['host'], $this->dbConfig['username'], $this->dbConfig['password'], $this->dbConfig['db_name']);
        if (mysqli_connect_errno($this->mysqli)) {
            throw new Exception("Error connection with DB");
        }
    }
        public function getEmployees($where='1',$start, $perPage){
        $sql="SELECT e.name,e.birthday,d.title_dep,p.title_pos,t.title_type,e.salary FROM `employees` AS e INNER JOIN departments AS d ON e.id_dep=d.id
INNER JOIN positions AS p ON e.id_pos=p.id
INNER JOIN payment_types AS t ON e.id_type=t.id  where $where LIMIT $start,$perPage";
            return $this->mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    public  function  getCount($where='1'){
        $sql="SELECT COUNT(*), e.name,e.birthday,d.title_dep,p.title_pos,t.title_type,e.salary FROM `employees` AS e INNER JOIN departments AS d ON e.id_dep=d.id
INNER JOIN positions AS p ON e.id_pos=p.id
INNER JOIN payment_types AS t ON e.id_type=t.id  where $where";
        $res = $this->mysqli->query($sql);
        $res = $res->fetch_assoc();
        return $res['COUNT(*)'];
    }

    public  function  getall($where='1'){
        $sql="SELECT e.name,e.birthday,d.title_dep,p.title_pos,t.title_type,e.salary FROM `employees` AS e INNER JOIN departments AS d ON e.id_dep=d.id
INNER JOIN positions AS p ON e.id_pos=p.id
INNER JOIN payment_types AS t ON e.id_type=t.id  where $where";
        return $this->mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    public  function  getNumPages(){
        $num_pages=ceil($this->getCount()/$this->perPage);
        return $num_pages;
    }
    public function getDepartments()
    {
        $sql='SELECT * FROM departments ';
        return $this->mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    public function getPositions()
    {
        $sql='SELECT * FROM positions ';
        return $this->mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getTypes()
{
    $sql='SELECT * FROM payment_types';
    return $this->mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
}

    public  function  getDepartmentById($id){
        $sql="SELECT title_dep FROM `departments` WHERE id=$id";
        $res=$this->mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['title_dep'];
    }
    public  function  getPositionById($id){
        $sql="SELECT title_pos FROM `positions` WHERE id=$id";
        $res=$this->mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['title_pos'];
    }
    public  function  getTypeById($id){
        $sql="SELECT title_type FROM `payment_types` WHERE id=$id";
        $res=$this->mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['title_type'];
    }

}


