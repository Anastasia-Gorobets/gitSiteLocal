<?php
function dataProcessing($data){
    $data = htmlentities($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    return $data;
}