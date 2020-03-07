<?php
    session_start();

    include_once 'classes/PDO.DB.class.php';
    include_once 'sanitizedatafile.php';

    $dbObj = new DB();
    $request = '';
    
    if(isset($_POST['request'])){
        $request = $_POST['request'];
    }

    $name = santizeText($_POST['sessionname']);
    $sessioncapacity = santizeNumber($_POST['sessioncapacity']);
    if ($request != 'updateManager'){
        $event = santizeNumber($_POST['event']);
    }
    $sessionstartdate = $_POST['sessionstartdate'];
    $sessionenddate = $_POST['sessionenddate'];

    if (isset($event) && $request != 'updateManager' && !isValidNumber($event)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Empty/Invalid event</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }
    else if (isset($name) && $name == ""){
        $msg = "<div id = 'errorDiv' class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Session field is empty</div>";
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }
    else if ($sessionstartdate > $sessionenddate){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Start date should be less than end date</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    else if (isset($sessioncapacity) && !isValidNumber($sessioncapacity)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Empty/Invalid capacity</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    

    else if ($request == 'create'){
        try{
            $data = [
                'name' => $name,
                'numberallowed' => $sessioncapacity, 
                'event' => $event,
                'startdate' => $sessionstartdate,
                'enddate' => $sessionenddate
            ];

            $sql = "INSERT INTO session (name, numberallowed, event, startdate, enddate) VALUES (:name, :numberallowed, :event, :startdate, :enddate)";
            // var_dump($data);
            // var_dump($sql);
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "adminbrowsesessions.php"]);
            ob_end_flush();
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 

    else if ($request == 'update'){
        try {
            $data = [
                'name' => $name,
                'numberallowed' => $sessioncapacity, 
                'event' => $event,
                'startdate' => $sessionstartdate,
                'enddate' => $sessionenddate,
                'idsession' => $_POST['id']
            ];

            $sql = "UPDATE session 
                    SET name=:name, 
                    numberallowed=:numberallowed, 
                    event= :event, 
                    startdate= :startdate, 
                    enddate= :enddate
                    WHERE idsession= :idsession";
            
            // var_dump($data);
            // var_dump($sql);
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "adminbrowsesessions.php"]);
            ob_end_flush();
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    }
    else if ($request == 'createManager'){
        try{
            $data = [
                'name' => $name,
                'numberallowed' => $sessioncapacity, 
                'event' => $event,
                'startdate' => $sessionstartdate,
                'enddate' => $sessionenddate
            ];

            $sql = "INSERT INTO session (name, numberallowed, event, startdate, enddate) VALUES (:name, :numberallowed, :event, :startdate, :enddate)";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);

            echo json_encode(['code'=>'200', 'location' => "eventmanagerbrowsesessions.php"]);
            ob_end_flush();
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 

    else if ($request == 'updateManager'){
        try {
            $data = [
                'name' => $name,
                'numberallowed' => $sessioncapacity, 
                'startdate' => $sessionstartdate,
                'enddate' => $sessionenddate,
                'idsession' => $_POST['id']
            ];

            $sql = "UPDATE session 
                    SET name=:name, 
                    numberallowed=:numberallowed, 
                    startdate= :startdate, 
                    enddate= :enddate
                    WHERE idsession= :idsession";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "eventmanagerbrowsesessions.php"]);
            ob_end_flush();
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    }

?>