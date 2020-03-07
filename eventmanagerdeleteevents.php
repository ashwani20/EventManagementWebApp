<?php
    session_start();
    if (!isset($_SESSION['eventmanager'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'sanitizedatafile.php';
    if(isset($_GET['delIdEvent']) && !isValidNumber($_GET['delIdEvent'])){
        header('location: eventmanagerbrowseevents.php');
    }

    include_once 'classes/PDO.DB.class.php';
    if(isset($_SESSION['idattendee']) and $_SESSION['idattendee'] !="" &&
                $_GET['delIdEvent'] and $_GET['delIdEvent'] !=""){
        $dbObj = new DB();
        
        // Removing record from manager_event table
        $count=$dbObj->getDBH()->prepare("DELETE FROM manager_event WHERE event =:event AND manager= :manager");
        $count->bindParam(":event", $_GET['delIdEvent'], PDO::PARAM_INT);
        $count->bindParam(":manager", $_SESSION['idattendee'], PDO::PARAM_INT);
        $count->execute();
        
        // Removing record from event table
        $count=$dbObj->getDBH()->prepare("DELETE FROM event WHERE idevent =:event");
        $count->bindParam(":event", $_GET['delIdEvent'], PDO::PARAM_INT);
        $count->execute();
        $idManager = $_SESSION['idattendee'];
        header("Location: eventmanagerbrowseevents.php");
        die();
    }
?>