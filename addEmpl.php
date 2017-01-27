<?php
require "config/settings_config.php";
require "classes/Employee.php";
require "classes/EmployeeHour.php";
require "classes/EmployeeRate.php";
require "classes/userInputsValidation.php";
if(isset($_POST['name'])){
    $name = $_POST['name'];
}
if(isset($_POST['birthday'])){
    $birthday = $_POST['birthday'];
}
if(isset($_POST['dep'])){
    $dep = $_POST['dep'];
}
if(isset($_POST['pos'])){
    $pos = $_POST['pos'];
}
if(isset($_POST['type'])){
    $type = $_POST['type'];
    if($type == '2'){
        if(isset($_POST['count_hour'])){
            $count_hour = $_POST['count_hour'];
        }else {
            $count_hour = 0;
        }
    }
}
$name = userInputsValidation::dataProcessing($name);
$birthday = userInputsValidation::dataProcessing($birthday);
$dep = userInputsValidation::dataProcessing($dep);
$pos = userInputsValidation::dataProcessing($pos);
$type = userInputsValidation::dataProcessing($type);
$db_conf = require "db/database_config.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
if ($mysqli->connect_errno) {
    throw new Exception("Error connection with DB");
}
if ($type == "1"){
   switch ($pos){
        case '1':
            $salary = $salary_junior;
        break;
        case '2':
            $salary = $salary_middle;
            break;
        case '3':
            $salary = $salary_assistant;
            break;
        case '4':
            $salary = $salary_senior;
            break;
    }
}else{
    $empl = new EmployeeHour($cost_h);
    $salary = $empl->getSalary($count_hour);
}
if (!($stmt = $mysqli->prepare("INSERT INTO employees (`name`,`birthday`,`id_dep`,`id_pos`,`id_type`,`salary`) VALUES (?,?,?,?,?,?)"))) {
    throw new Exception("Error  with DB");
}
if (!$stmt->bind_param("ssiiii", $name,$birthday,$dep,$pos,$type,$salary)) {
    throw new Exception("Error with DB");
}
if (!$stmt->execute()) {
    throw new Exception("Error  with DB");
}
$arr = array('message' => 'success');
echo json_encode($arr);
die;


