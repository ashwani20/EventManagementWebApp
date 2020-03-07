<?php
    session_start();
    include_once 'classes/PDO.DB.class.php';
    include_once 'sanitizedatafile.php';

    $dbObj = new DB();
    $request = '';
    
    if(isset($_POST['request'])){
        $request = $_POST['request'];
    }

    $name = santizeText($_POST['name']);
    $eventcapacity = santizeNumber($_POST['eventcapacity']);
    $eventvenue = santizeNumber($_POST['eventvenue']);
    $eventstartdate = $_POST['eventstartdate'];
    $eventenddate = $_POST['eventenddate'];

    if (isset($name) && $name == ""){
        $msg = "<div id = 'errorDiv' class='alert alert-danger'><i class='fa fa-exclamation-triangle'></i> Name field is empty</div>";
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }
    else if ($eventstartdate > $eventenddate){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Start date should be less than end date</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    else if (isset($eventcapacity) && !isValidNumber($eventcapacity)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Empty/Invalid event capacity</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    } 
    else if (isset($eventvenue) && !isValidNumber($eventvenue)){
        $msg = '<div id = "errorDiv" class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Empty/Invalid venue</div>';
        echo json_encode(['code'=>'404', 'msg'=>$msg]);
    }

    else if ($request == 'create'){
        try{
            $data = [
                'name' => $name,
                'datestart' => $eventstartdate,
                'dateend' => $eventenddate,
                'numberallowed' => $eventcapacity, 
                'venue' => $eventvenue
            ];

            $sql = "INSERT INTO event (name, datestart, dateend, numberallowed, venue) VALUES (:name, :datestart, :dateend, :numberallowed, :venue)";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['code'=>'200', 'location' => "adminbrowseevents.php"]);
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
                'datestart' => $eventstartdate,
                'dateend' => $eventenddate,
                'numberallowed' => $eventcapacity, 
                'venue' => $eventvenue,
                'idevent' => $_POST['id']
            ];

            
            $sql = "UPDATE event 
                    SET name=:name, 
                    datestart=:datestart, 
                    dateend=:dateend, 
                    numberallowed=:numberallowed, 
                    venue=:venue 
                    WHERE idevent =:idevent";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            echo json_encode(['code'=>'200', 'location' => "adminbrowseevents.php"]);
            ob_end_flush();
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 

    else if ($request == "createManager"){
        try{
            $data = [
                'name' => $name,
                'datestart' => $eventstartdate,
                'dateend' => $eventenddate,
                'numberallowed' => $eventcapacity, 
                'venue' => $eventvenue
            ];

            $sql = "INSERT INTO event (name, datestart, dateend, numberallowed, venue) VALUES (:name, :datestart, :dateend, :numberallowed, :venue)";
            
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);

            $idevent = $dbObj->getDBH()->lastInsertId();
            $idmanager =  $_SESSION['idattendee'];
            $sql = "INSERT INTO manager_event values(:event, :manager)";
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute(array('event'=>$idevent, 'manager'=>$idmanager));

            echo json_encode(['code'=>'200', 'location' => "eventmanagerbrowseevents.php"]);
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
                'datestart' => $eventstartdate,
                'dateend' => $eventenddate,
                'numberallowed' => $eventcapacity, 
                'venue' => $eventvenue,
                'idevent' => $_POST['id']
            ];

            
            $sql = "UPDATE event 
                    SET name=:name, 
                    datestart=:datestart, 
                    dateend=:dateend, 
                    numberallowed=:numberallowed, 
                    venue=:venue 
                    WHERE idevent =:idevent";

            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            echo json_encode(['code'=>'200', 'location' => "eventmanagerbrowseevents.php"]);
            ob_end_flush();
            die();
        } catch (PDOException $e) {
            echo json_encode(['code'=>'404', 'msg'=>$e->getMessage()]);
        }
    } 
?>