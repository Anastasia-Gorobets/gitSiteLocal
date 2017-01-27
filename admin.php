<?php
$db_conf = require "db/database_config.php";
$mysqli = new mysqli($db_conf['host'], $db_conf['username'], $db_conf['password'],$db_conf['db_name']);
if ($mysqli->connect_errno) {
    echo "Error with connect to DB: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $query=$mysqli->query("SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $userdata =$query->fetch_assoc();
    if (($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id']) or ($userdata['user_admin'] == 0)) {
        setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
        setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");
        header("Location: deny_access.php");
    }
    else
    {
        ?>
        <?php
        include "db/DataBase.php";
        $db = new DataBase();
        $depart = $db->getDepartments();
        $position = $db->getPositions();
        $types = $db->getTypes();
        if(!empty($dep)){
            $d = $db->getDepartmentById($dep);}
            else {
                $d = '';
            }
        if(!empty($pos)) {
            $p = $db->getPositionById($pos);
        }else {
            $p = '';
        }
        if(!empty($type)) {
            $t = $db->getTypeById($type);
        }else {
            $t = '';
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="css/site.css">
            <title>Admin</title>
            <script>
                function checkPayment(str){
                    $p = $("#type_id option:selected" ).text();
                    if($p == 'hourly'){
                        $("#count_hour_div").show('slow');
                    }else{
                        $("#count_hour_div").hide('slow');
                    }
                }
            </script>
            <style>
                .error{
                    color:darkred;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <?php
            include "header.php";
            ?>
            <br>
            <div class="jumbotron">
                <a class="show-block" id="addEmplButton" href="#block" data-alt="Hide">Add new employeer</a><br>
                <div id="block">
                    <form role="form" id="addForm" method="post">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
                        </div>
                        <div class="form-group ">
                            <label for="date">Birthday</label>
                            <input class="form-control" required id="date" name="date" placeholder="YYYY-MM-DD" type="text"/>
                        </div>
                        <select class="form-control" id="dep_id" name="deps">
                            <option value="">Choose department</option>
                            <?php
                            foreach ($depart as $dep) {
                                ?>
                                <option value="<?php echo $dep['id'] ?>"><?php echo $dep['title_dep'] ?></option>
                                <?php
                            } ?>
                        </select>
                        <br>

                        <select class="form-control" id="pos_id" name="pos">
                            <option value="">Choose position</option>
                            <?php
                            foreach ($position as $posit){?>
                                <option value="<?php echo $posit['id'] ?>"><?php echo $posit['title_pos'] ?></option>
                            <?php }
                            ?>
                        </select>

                        <br>

                        <select class="form-control" id="type_id" name="type" onchange="checkPayment(this.value)">
                            <option value="">Choose payment</option>
                            <?php
                            foreach ($types as $type) {
                                ?>
                                <option value="<?php echo $type['id'] ?>"><?php echo $type['title_type'] ?></option>';
                            <?php }
                            ?>
                        </select>
                        <br>
                        <div class="form-group" id="count_hour_div" style="display: none">
                            <label for="count_hour">Number of hours</label>
                            <input type="text" class="form-control" name="count_hour" id="count_hour" placeholder="Enter number of hours">
                        </div>
                        <button name="submit" id="submit" value="" type="submit" class="btn btn-large btn-primary btn-block">Add</button>
                    </form>

                    <p id="suc"></p>
                </div>
            </div>
        </div>
        </div>

        <!-- Include Date Range Picker -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <script>
            $().ready(function() {
                var date_input = $('input[name="date"]');
                var container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                date_input.datepicker({
                    format: 'yyyy-mm-dd',
                    container: container,
                    todayHighlight: true,
                    autoclose: true
                });
                $("a.show-block").click(function () {
                    var $t = $(this), alt = $t.data('alt');
                    $t.data('alt', $t.text());
                    $t.text(alt);
                    $($t.attr('href')).stop().toggle('slow');
                    return false;
                });
                $("#addForm").validate({
                    rules:{
                        name:{
                            required: true,
                            minlength: 2,
                            maxlength: 10
                        },
                        date:{
                            required: true
                        },
                        deps:{
                            required:true
                        },
                        pos:{
                            required:true
                        },
                        type:{
                            required:true
                        },
                        count_hour:{
                            required:true
                        }
                    },
                    messages:{
                        name:{
                            required: "This field is required",
                            minlength: "Name must be at least 2 characters",
                            maxlength: "Maximum number of characters - 10"
                        },
                        date:{
                            required: "This field is required"
                        },
                        deps:{
                            required: "This field is required"
                        },
                        pos:{
                            required: "This field is required"
                        },
                        type:{
                            required: "This field is required"
                        },
                        count_hour:{
                            required: "This field is required"
                        }
                    },
                    submitHandler: function(form) {
                        var name = $("#name").val();
                        var birthday = $("#date").val();
                        var dep = $("#dep_id").val();
                        var pos = $("#pos_id").val();
                        var type = $("#type_id").val();
                        var count_hour = $("#count_hour").val();
                        $("#addForm input").val("");
                        $("#addForm select").val("");

                        $(form).ajaxSubmit({
                            url:'addEmpl.php',
                            type:'POST',
                            data: {
                                name:name,
                                birthday:birthday,
                                dep:dep,
                                pos:pos,
                                type:type,
                                count_hour:count_hour

                            },
                           dataType: 'json',
                            success: function(data) {
                                $("#suc").html(data.message);
                            }
                        });
                    }
                });
            });
        </script>

        <script src="http://malsup.github.com/jquery.form.js"></script>
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