<?php
    include_once 'classes/PDO.DB.class.php';
    include_once 'sanitizedatafile.php';
    
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
        echo json_encode($eventRecord);
        exit;
    } 
    else if($request == 2){
        $idevent = $_POST['idevent'];

        $stmt = $dbObj->getDBH()->prepare("SELECT idsession, name, numberallowed, event, 
                                            DATE_FORMAT(startdate, '%Y-%m-%dT%H:%i') as startdate, 
                                            DATE_FORMAT(enddate, '%Y-%m-%dT%H:%i') as enddate
                                            FROM session WHERE event=:idevent;");
        $stmt->bindValue(':idevent', (int)$idevent, PDO::PARAM_INT);
        // $stmt->bindValue(':idevent', 1, PDO::PARAM_INT);
        $stmt->execute();
        $eventRecord = $stmt->fetchAll();
        $response = array();

        foreach($eventRecord as $session){
        $response[] = array(
            "idsession" => $session['idsession'],
            "name" => $session['name'],
            "numberallowed" => $session['numberallowed'],
            "event" => $session['event'],
            "startdate" => $session['startdate'],
            "enddate" => $session['enddate'],
        );
        }

        echo json_encode($response);
        exit();
    }

    else if($request == 3){
        $idattendee = $_POST['idattendee'];

        $stmt = $dbObj->getDBH()->prepare(" SELECT e.name, e.idevent
                                            FROM event as e
                                            INNER JOIN attendee_event as m ON e.idevent = m.event
                                            INNER JOIN attendee as a ON a.idattendee = m.attendee
                                            WHERE a.idattendee=:idattendee;");
        $stmt->bindValue(':idattendee', (int)$idattendee, PDO::PARAM_INT);
        $stmt->execute();
        $eventRecord = $stmt->fetchAll();
        $response = array();

        foreach($eventRecord as $event){
        $response[] = array(
            "idevent" => $event['idevent'],
            "name" => $event['name']
        );
        }

        echo json_encode($response);
        exit();
    }
?>