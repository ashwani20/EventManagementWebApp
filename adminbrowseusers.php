<?php 
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();

    $name = "";
    if (isset($_POST['search'])){
        $name = $_POST['username'];
    }
    
    function getUserData($name, $dbh){
        $userData = array();
        try{
            $stmt = $dbh->prepare("SELECT a.idattendee, a.name, a.password, r.name as role
                                    FROM role as r 
                                    INNER JOIN attendee as a 
                                    ON r.idrole = a.role 
                                    WHERE a.name like :name;");
           
            $name = "%$name%";
            $stmt->execute(array('name'=>$name));
            $userData = $stmt->fetchAll();
            return $userData;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $userData;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                        <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                            <a class="my-0 mr-md-auto font-weight-normal" href="admin.php">BookMyEvent</a>
                        </h5>
                        <a href="adminaddusers.php" class="float-right btn btn-dark btn-sm">
                            <i class="fa fa-fw fa-plus-circle"></i> Add Users
                        </a>
                </div>

                <div class="card-body">
                    <div class="col-sm-12">
                        <h5 class="card-title"><i class="fa fa-fw fa-search"></i> Find User</h5>
                        <form action="adminbrowseusers.php" method="POST">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>User Name</label>
                                        <input type="text" name="username" id="username" class="form-control" value="" placeholder="Enter user name">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div>
                                            <input type="submit" name="search" value="search" id="search" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></input>
                                            <!-- <a href="" class="btn btn-danger"><i class="fa fa-fw fa-sync"></i> Clear</a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $dbObj = new DB();
                            $userData = getUserData($name, $dbObj->getDBH());
                            if(count($userData)>0){
                                foreach($userData as $val){
                            ?>
                            <tr>
                                <td><?php echo $val['idattendee'];?></td>
                                <td><?php echo $val['name'];?></td>
                                <td><?php echo $val['password'];?></td>
                                <td><?php echo $val['role'];?></td>
                                <?php
                                    if ($val['role'] != 'superadmin'){
                                ?>
                                <td align="center">
                                    <a href="admineditusers.php?editId=<?php echo $val['idattendee'];?>" class="text-primary"><i class="fa fa-fw fa-edit"></i> Edit</a> | 
                                    <a href="admindeleteusers.php?delId=<?php echo$val['idattendee'];?>" class="text-danger" onClick="return confirm('Are you sure to delete this user?');"><i class="fa fa-fw fa-trash"></i> Delete</a>
                                </td>
                                <?php
                                    } else{
                                ?>
                                <td align="center">
                                    No actions
                                </td>
                                <?php
                                }
                                ?>
                            </tr>
                            <?php
                                }
                            } else{
                            ?>
                            <tr><td colspan="6" align="center">No Record(s) Found!</td></tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
            </div> 
        </div>
    </body>
</html>