<?php

    include_once 'classes/PDO.DB.class.php';
    include_once 'sanitizedatafile.php';

    $dbObj = new DB();
    $request = '';
    
    if(isset($_POST['request'])){
        $request = $_POST['request'];
    }

    $name = santizeText($_POST['name']);
    $venuecapacity = santizeNumber($_POST['venuecapacity']);

    if (isset($name) && $name == ""){
        $msg = "<div id = 'errorDiv' class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> User name field is empty</div>";
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    else if (isset($venuecapacity) && !isValidNumber($venuecapacity)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid/Empty capacity</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }

    else if ($request == 'create'){
        try{
            $data = [
                'name' => $name,
                'capacity' => $venuecapacity
            ];

            $sql = "INSERT INTO venue (name, capacity) VALUES (:name, :capacity)";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "adminbrowsevenues.php"]);
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 

    else if ($request == 'update'){
        try {
            $data = [
                'name' => $name,
                'capacity' => $venuecapacity,
                'idvenue' => $_POST['id']
            ];

            $sql = "UPDATE venue SET name=:name, capacity=:capacity WHERE idvenue=:idvenue";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);

            echo json_encode(['code'=>'200', 'location' => "adminbrowsevenues.php"]);
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    }
?>