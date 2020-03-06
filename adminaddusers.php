<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    
    function getAllRoles($dbh){
        $roles = array();
        try{
            $stmt = $dbh->prepare(" SELECT idrole, name
                                    FROM role
                                    where name <> 'superadmin';");
            $stmt->execute();
            $roles = $stmt->fetchAll();

            return $roles;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $roles;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add User</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
        <script>
            $(document).ready(function() {
                $('#submit').click(function(e){
                    e.preventDefault();
                    var name = $("#username").val();
                    var password = $("#userpwd").val();
                    var role = $("#userrole").val();
                    console.log(name, password);
                    $.ajax({
                        type: "POST",
                        url: "ajaxuserfile.php",
                        dataType: "json",
                        data: {request:'create', name:name, password:password, role:role},
                        success : function(data){
                            console.log(data);
                            if (data['code'] == "200"){
                                window.location.href = data['location'];
                            }
                            else if (data['code'] == "404"){
                                if ($("#errorDiv")){
                                    $("#errorDiv").remove();
                                }
                                $("form").prepend(data['msg']);
                            } 
                        }
                    });
                });
            });
        </script>
    </head>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                    <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                        <a class="my-0 mr-md-auto font-weight-normal" href="admin.php">BookMyEvent</a>
                    </h5>
                    
                    <a href="adminbrowseusers.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Users</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form>
                            <div class="form-group">
                                <label>User Name <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter user name" required>
                            </div>
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" name="userpwd" id="userpwd" class="form-control" placeholder="Enter user password" required>
                            </div>
                            <div class="form-group">
                                <label for = "userrole" >User Role <span class="text-danger">*</span></label>
                                <select name="userrole" id="userrole" class="form-control" required> 
                                    <option value="">Select a role</option>
                                    <?php
                                        $roles = getAllRoles($dbObj->getDBH());
                                        if(count($roles)>0){
                                            foreach($roles as $val){
                                        ?>
                                            <option value= <?php echo $val['idrole'];?>><?php echo $val['name'];?></option>    
                                        <?php
                                            }
                                        } 
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>