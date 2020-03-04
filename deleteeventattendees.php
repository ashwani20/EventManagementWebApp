<?php
    session_start();
    if (!isset($_SESSION['attendee'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    if($_GET['editIdEvent'] and $_GET['editIdEvent'] !=""){
        $dbObj = new DB();
        // Removing record from attendee_event table
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee_event WHERE event =:event AND attendee= :attendee");
        $count->bindParam(":event", $_GET['editIdEvent'], PDO::PARAM_INT);
        $count->bindParam(":attendee", $_SESSION['idattendee'], PDO::PARAM_INT);
        $count->execute();
        header("Location: regattendevent.php");
        die();
    }
?>