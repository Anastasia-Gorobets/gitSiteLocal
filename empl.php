<?php
$db_conf=require "db/database_config.php";
include "db/DataBase.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
if ($mysqli->connect_errno) {
    throw new Exception("Error connection with DB");}
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $query=$mysqli->query("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userdata =$query->fetch_assoc();
    if(($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id']))
    {
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
        header("Location: deny_access.php");
    }
    else
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
            <link rel="stylesheet" href="css/site.css">
            <title>Employees</title>
            <style>
                #page {
                    border:1px solid gray;
                    border-radius:1px;
                    background:white;
                    padding: 0 8px;
                    margin: 3px;
                }
                .active-page{
                    border:1px solid gray;
                    border-radius:1px;
                    background-color: lightskyblue;
                    color:white;
                    padding: 0 8px;
                    margin: 3px;
                }
            </style>
        </head>
    <body>
    <div class="container">

        <script>
            function  filterUser (str) {
                var num_records=$("#num_records").val();
                var depId = $("#dep_id").val();
                var id_pos = $("#id_pos").val();
                var id_type = $("#id_type").val();
                var sorted = $("input[name=sorted]:checked").val();
                var d=$('input[name=del]:checked').val();
                var del="";
                if(d!= undefined){
                     del=d;
                }else del="";
                var page=str.innerHTML;
                setTimeout(
                    function()
                    {
                        $("#del").prop('checked', false);
                    }, 500);
                $.ajax({
                    url:'filterEmpl.php',
                    type:'GET',
                    dataType:'html',
                    data: {
                        num_records:num_records,
                        depId:depId,
                        id_pos:id_pos,
                        id_type:id_type,
                        sorted:sorted,
                        del:del,
                        page:page
                    },
                    success: function(data) {
                        $("#usersHint").html(data);
                    }
                });
            }
            function  setDefaults() {
                $("#formEmpl select").val("");
                $("#formEmpl #num_records").val(20);
            }
        </script>
        <body>
        <div class="container">
        <?php
        include "header.php";
        ?>
        <?php
        $db=new DataBase();
        $depart=$db->getDepartments();
        $position=$db->getPositions();
        $types=$db->getTypes();
        ?>
        <br>
        <div class="container">
            <form class="form-inline" id="formEmpl">
                <p>Records numbers for printing:</p>
                <select class="form-control" id="num_records" name="nums" onchange="filterUser(this.value)">
                    <option  value="20">Choose number</option>
                    <option  value="20">20</option>
                    <option  value="40">40</option>
                    <option  value="50">50</option>
                    <option  value="100">100</option>
                </select>
                <br><br>
                <select class="form-control" id="dep_id" name="deps" onchange="filterUser(this.value)">
                    <option  value="">Choose department</option>
                    <?php
                    for($i=0;$i<count($depart);$i++){?>
                        "'.$depart[$i]['id'].'"
                     <option  value="<?php echo $depart[$i]['id']?>"><?php echo $depart[$i]['title_dep'] ?></option>
                  <?php  }
                    ?>
                </select>
                <select class="form-control" id="id_pos" name="pos" onchange="filterUser(this.value)">
                    <option  value="">Choose position</option>
                    <?php
                    for($i=0;$i<count($position);$i++){?>
                          <option  value="<?php echo $position[$i]['id']?>" ><?php echo $position[$i]['title_pos']?></option>
                 <?php   }
                    ?>
                </select>

                <select class="form-control" id="id_type" name="type" onchange="filterUser(this.value)">
                    <option value="">Choose payment</option>
                    <?php
                    for($i=0;$i<count($types);$i++){?>
                          <option value="<?php echo $types[$i]['id']?>" ><?php echo $types[$i]['title_type']?></option>';
                   <?php }
                    ?>
                </select>
                <br>
                <input type="radio" name="sorted" checked id="sorted" value="no sorted" onchange="filterUser(this.value)"> No sorted<br>
                <input type="radio" name="sorted" id="sorted" value="salary asc" onchange="filterUser(this.value)" > Salary ascending<br>
                <input type="radio" name="sorted" id="sorted" value="salary desc" onchange="filterUser(this.value)" > Salary Descending<br>
                <input type="radio" name="sorted" id="sorted" value="birthday asc" onchange="filterUser(this.value)" > Date of Birth ascending<br>
                <input type="radio" name="sorted" id="sorted" value="birthday desc" onchange="filterUser(this.value)" > Date of Birth descending<br>
                <br><input type="radio" name="del" id="del" value="del" onchange=" setDefaults(); filterUser(this.value)"> Delete filters<br>
            </form>
        </div>
        <?php
        $perPage=20;
        if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
        $start=abs($page*$perPage);
        $where='1';?>
        <?php
        $empl=$db->getEmployees($where,$start,$perPage);
        ?>
        <div class="container">
            <?php
            if(count($empl)<=0){?>
                <h4>Sorry, no matches found</h4>
            <?php }else {
                ?>
               <div id=usersHint>
                   <table class="table table-striped">
                       <thead>
                       <tr>
                           <th>Name</th>
                           <th>Birthday</th>
                           <th>Department</th>
                           <th>Position</th>
                           <th>Payment type</th>
                           <th>Salary</th>
                       </tr>
                       </thead>
                       <tbody>
                       <?php
                       for ($i = 0; $i < count($empl); $i++) {?>
                           <tr>
                          <td> <?php echo $empl[$i]['name'] ?> </td>
                           <td><?php echo $empl[$i]['birthday'] ?></td>
                          <td><?php echo $empl[$i]['title_dep'] ?></td>
                           <td><?php echo $empl[$i]['title_pos'] ?></td>
                           <td><?php echo $empl[$i]['title_type'] ?></td>
                          <td><?php echo $empl[$i]['salary'] ?></td>
                          </tr>
                      <?php }
                       ?>
                       </tbody>
                   </table>
            <br>
                <?php
                $all=$db->getall();
                $count_empl=count($all);
                $perPage=20;
                $num=ceil($count_empl/$perPage);
                $iCurr = (empty($_GET['page']) ? 1 : intval($_GET['page']));
                $iLastPage = $db->getNumPages();
                $iLeftLimit = 2;
                $iRightLimit = 3;
                function makePager($iCurr, $iEnd, $iLeft, $iRight)
                {
                    if ($iCurr > $iLeft && $iCurr < ($iEnd - $iRight)) {
                        for ($i = $iCurr - $iLeft; $i <= $iCurr + $iRight; $i++) {
                            if ($i-1 == $iCurr-1) {?>
                                <a class="active-page" href="" ><?php echo $i?></a>
                            <?php } else {?>
                                <a id="page" href="" onclick="filterUser(this); return false;"><?php echo $i?></a>
                            <?php }
                        }
                    } elseif ($iCurr <= $iLeft) {
                        $iSlice = 1 + $iLeft - $iCurr;
                        for ($i = 1; $i <= $iCurr + ($iRight + $iSlice); $i++) {
                            if ($i-1 == $iCurr-1) {?>
                                <a class="active-page" href="" ><?php echo $i?></a>
                            <?php } else {?>
                                <a id="page" href="" onclick="filterUser(this); return false;"><?php echo $i?></a>
                            <?php   }
                        }
                    } else {
                        $iSlice = $iRight - ($iEnd - $iCurr);
                        for ($i = $iCurr - ($iLeft + $iSlice); $i <= $iEnd; $i++) {
                            if ($i-1 == $iCurr-1) {?>
                                <a class="active-page" href="" ><?php echo $i?></a>
                            <?php } else {?>
                                <a id="page" href="" onclick="filterUser(this); return false;"><?php echo $i?></a>
                            <?php   }
                        }
                    }
                }
                if(count($empl)>0) {
                    if ($iLastPage >= $iLeftLimit + $iRightLimit) {
                        makePager($iCurr, $iLastPage, $iLeftLimit, $iRightLimit);
                    } else {

                        for ($i = 1; $i <= $num; $i++) {
                            if ($i - 1 == $iCurr - 1) {?>
                                <a class="active-page" href="" ><?php echo $i?></a>
                            <?php } else {?>
                                <a id="page" href="" onclick="filterUser(this); return false;"><?php echo $i?></a>
                            <?php   }
                        }
                    }
                }?>
               </div>
                </div>
           <?php }
            ?>
        </body>
        </html>
        <?php
    }
}
else
{
   header("Location: deny_access.php");
}
?>