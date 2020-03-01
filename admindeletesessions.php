<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    if(isset($_GET['delId']) and $_GET['delId'] !=""){
        $dbObj = new DB();


        // Removing records from attendee_sessions table having event ID similiar to passed event ID
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee_session WHERE session =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();

        // Finally removing record from event table having event ID similiar to passed event ID
        $count=$dbObj->getDBH()->prepare("DELETE FROM session WHERE idsession =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();
        header('location: adminbrowsesessions.php');
        die();
    }
?>