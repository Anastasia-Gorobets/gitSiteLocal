<?php
class Employee{

    public $name;
    public $birthday;
    public $dep;
    public $pos;
    public $salary;
    public $cost_hour;

    function __construct($cost_h)
    {
        $this->cost_hour=$cost_h;
    }



}