<?php

class EmployeeHour extends Employee
{

    public function getSalary($count_hour){
        $this->salary=$this->cost_hour*$count_hour;
        return $this->salary;
    }

}