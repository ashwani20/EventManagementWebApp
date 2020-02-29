<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 

    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    $data = array();
    
    if(isset($_GET['editId'])){
        try{
            $stmt = $dbObj->getDBH()->prepare("SELECT * from attendee where idattendee = :id");
            $stmt->execute(array('id'=>$_GET['editId']));
            $data = $stmt->fetchALL();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    function getAllRoles($dbh){
        $roles = array();
        try{
            // $stmt = $dbh->prepare(" SELECT idrole, name
                                    // FROM role;");
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
        <title>Edit Users</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
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
                        <form method="post">
                            <div class="form-group">
                                <label>User Name <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" value = "<?php echo $data[0]['name']; ?>" class="form-control" placeholder="Enter user name" required>
                            </div>
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <!-- <input type="password" name="userpwd" id="userpwd" value = "<?php echo $data[0]['password']; ?>" class="form-control" placeholder="Enter user password" required> -->
                                <input type="password" name="userpwd" id="userpwd" value = "" class="form-control" placeholder="Enter user password" required>
                            </div>
                            <div class="form-group">
                                <label for = "userrole" >User Role <span class="text-danger">*</span></label>
                                <select name="userrole" id="userrole" class="form-control" required> 
                                    <option value="">Select a role</option>
                                    <?php
                                        $roles = getAllRoles($dbObj->getDBH());
                                        if(count($roles)>0){
                                            foreach($roles as $val){
                                            if ($data[0]['role'] == $val['idrole']){
                                        ?>
                                                <option value= <?php echo $val['idrole'];?> selected><?php echo $val['name'];?></option>
                                        <?php
                                            } else {
                                        ?>
                                            <option value= <?php echo $val['idrole'];?>><?php echo $val['name'];?></option>    
                                        <?php
                                            }
                                        } 
                                    }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Edit User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>

    <?php
        if (isset($_POST['submit'])){
            $data = [
                'name' => $_POST['username'],
                'password' => hash('sha256', $_POST['userpwd']),
                'role' => $_POST['userrole'],
                'id' => $_GET['editId']
            ];
            
            $sql = "UPDATE attendee SET name=:name, password=:password, role=:role WHERE idattendee=:id";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            header("Location: adminbrowseusers.php");
            die();
        }
    ?>
</html>