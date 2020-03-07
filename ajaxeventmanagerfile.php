<?php

    include_once 'classes/PDO.DB.class.php';
    include_once 'sanitizedatafile.php';

    $dbObj = new DB();
    $request = '';
    
    if(isset($_POST['request'])){
        $request = $_POST['request'];
    }

    $event = santizeNumber($_POST['event']);
    $manager = santizeNumber($_POST['manager']);

    if (isset($event) && !isValidNumber($event)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid/Empty event</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }
    else if (isset($manager) && !isValidNumber($manager)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Invalid/Empty manager</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }

    else if ($request == 'create'){
        try{
            $data = [
                'event' => $event,
                'manager' => $manager
            ];

            $sql = "INSERT INTO manager_event (event, manager) VALUES (:event, :manager)";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            echo json_encode(['code'=>'200', 'location' => "adminbrowseeventmanagers.php"]);
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 

    else if ($request == 'update'){
        try {
            $data = [
                'event' => $event,
                'manager' => $manager
            ];
            
            $sql = "UPDATE IGNORE manager_event 
                    SET event= :event 
                    WHERE manager= :manager";
            // var_dump($data);
            // var_dump($sql);
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "adminbrowseeventmanagers.php"]);
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    }
?>