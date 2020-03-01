<?php
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    $request = 0;

    if(isset($_POST['request'])){
        $request = $_POST['request'];
    }

    // Fetch event record by idevent
    if($request == 1){
        $idevent = $_POST['idevent'];

        $stmt = $dbObj->getDBH()->prepare("SELECT 
                                            DATE_FORMAT(datestart, '%Y-%m-%dT%H:%i') as datestart, 
                                            DATE_FORMAT(dateend, '%Y-%m-%dT%H:%i') as dateend,
                                            numberallowed 
                                            FROM event WHERE idevent=:idevent;");
        $stmt->bindValue(':idevent', (int)$idevent, PDO::PARAM_INT);

        $stmt->execute();
        $eventRecord = $stmt->fetch();

        // $response = array();
        // foreach($eventRecord as $record){
        //     $response[] = array(
        //         "datestart" => $record['datestart'],
        //         "dateend" => $record['dateend'],
        //         "numberallowed" => $record['numberallowed']
        //     );
        // }

        // echo json_encode($response);
        echo json_encode($eventRecord);
        exit;
    }
?>