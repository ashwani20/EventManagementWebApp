<?php

    include_once 'classes/PDO.DB.class.php';
    include_once 'sanitizedatafile.php';

    $dbObj = new DB();
    $request = '';
    
    if(isset($_POST['request'])){
        $request = $_POST['request'];
    }

    $name = santizeText($_POST['name']);
    $password = santizeText($_POST['password']);
    $role = santizeNumber($_POST['role']);

    if (isset($name) && $name == ""){
        $msg = "<div id = 'errorDiv' class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> User name field is empty</div>";
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    else if (isset($password) && $password == ""){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> User password field is empty</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    else if (isset($role) && !isValidNumber($role)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid role entry</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }

    else if ($request == 'create'){
        try{
            $data = [
                'name' => $name,
                'password' => hash('sha256', $password),
                'role' => $role
            ];

            $sql = "INSERT INTO attendee (name, password, role) VALUES (:name, :password, :role)";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "adminbrowseusers.php"]);
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 

    else if ($request == 'update'){
        try {
            $data = [
                'name' => $name,
                'password' => hash('sha256', $password),
                'role' => $role,
                'id' => $_POST['id']
            ];
            
            $sql = "UPDATE attendee SET name=:name, password=:password, role=:role WHERE idattendee=:id";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            echo json_encode(['code'=>'200', 'location' => "adminbrowseusers.php"]);
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    }
?>