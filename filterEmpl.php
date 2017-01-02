<?php
//the number of records per page
if(isset($_GET['num_records'])){
    $perPage=(int)$_GET['num_records'];
}else
    $perPage=20;
$id_dep=(int)$_GET['depId']; //id department
$id_pos=(int)$_GET['id_pos']; //id position
$id_type=(int)$_GET['id_type']; //id type
$sorted=$_GET['sorted']; //sorted
if(isset($_GET['page'])){
    $page=$_GET['page'];
}else{
    $page=1;
}
$where='';
if(!empty($id_dep)) {
    if($where != '') {
        $where .=" AND id_dep='$id_dep'";
    }
    else  $where="id_dep='$id_dep'";
}
if(!empty($id_pos)) {
    if($where != '') {
        $where .=" AND id_pos='$id_pos'";
    }
    else  $where="id_pos='$id_pos'";
}
if(!empty($id_type)) {
    if($where != '') {
        $where .=" AND id_type='$id_type'";
    }
    else  $where="id_type='$id_type'";
}
if(empty($where))$where='1';
if(!empty($sorted)) {
    if ($sorted != "no sorted") {
        $where .= " ORDER BY $sorted";
    }
}
if(isset($_GET['del']))
{
    $del=$_GET['del'];
    if($del=='del')
    $where='1';
}
include "db/DataBase.php";
$db=new DataBase();
//get the page number
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
//calculate the first operator to LIMIT
$start=abs($page*$perPage);
$empl=$db->getEmployees($where,$start,$perPage);
?>
    <?php
    if(count($empl)<=0){
        echo "<h4>Sorry, no matches found</h4>";
    }else {
        ?>
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
            for ($i = 0; $i < count($empl); $i++) {
                echo "<tr>";
                echo "<td>" . $empl[$i]['name'] . "</td>";
                echo "<td>" . $empl[$i]['birthday'] . "</td>";
                echo "<td>" . $empl[$i]['title_dep'] . "</td>";
                echo "<td>" . $empl[$i]['title_pos'] . "</td>";
                echo "<td>" . $empl[$i]['title_type'] . "</td>";
                echo "<td>" . $empl[$i]['salary'] . "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    $emp=$db->getall($where);
    $count_empl=count($emp);
    $num=ceil($count_empl/$perPage);
    $iCurr = (empty($_GET['page']) ? 1 : intval($_GET['page']));
    $iLastPage = $num;
    $iLeftLimit = 2;
    $iRightLimit = 3;
    if(count($empl)>0) {
        if ($iLastPage >= $iLeftLimit + $iRightLimit) {
            makePager($iCurr, $iLastPage, $iLeftLimit, $iRightLimit);
        } else {
            for ($i = 1; $i <= $num; $i++) {
                if ($i - 1 == $iCurr - 1) {
                    echo '<a class="active-page" href="" >'.$i.'</a>';
                } else {
                    echo '<a id="page" href="" onclick="filterUser(this); return false;">'.$i.'</a>';
                }
            }
        }
    }
    function makePager($iCurr, $iEnd, $iLeft, $iRight)
    {
        if ($iCurr > $iLeft && $iCurr < ($iEnd - $iRight)) {
            for ($i = $iCurr - $iLeft; $i <= $iCurr + $iRight; $i++) {
                if ($i-1 == $iCurr-1) {
                    echo '<a class="active-page" href="" >'.$i.'</a>';
                } else {
                    echo '<a id="page" href="" onclick="filterUser(this); return false;">'.$i.'</a>';
                }
            }
        } elseif ($iCurr <= $iLeft) {
            $iSlice = 1 + $iLeft - $iCurr;
            for ($i = 1; $i <= $iCurr + ($iRight + $iSlice); $i++) {
                if ($i-1 == $iCurr-1) {
                    echo '<a class="active-page" href="" >'.$i.'</a>';
                } else {
                    echo '<a id="page" href="" onclick="filterUser(this); return false;">'.$i.'</a>';
                }
            }
        } else {
            $iSlice = $iRight - ($iEnd - $iCurr);
            for ($i = $iCurr - ($iLeft + $iSlice); $i <= $iEnd; $i++) {
                if ($i-1 == $iCurr-1) {
                    echo '<a class="active-page" href="" >'.$i.'</a>';
                } else {
                    echo '<a id="page" href="" onclick="filterUser(this); return false;">'.$i.'</a>';
                }
            }
        }
    }
die;
