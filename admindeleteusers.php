<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    include_once 'sanitizedatafile.php';
    if(isset($_GET['delId']) && !isValidNumber($_GET['delId'])){
        header('location: adminbrowseusers.php');
    }
    else if(isset($_GET['delId']) and $_GET['delId'] !=""){
        include_once 'classes/PDO.DB.class.php';
        $dbObj = new DB();

        // Deleting record from manager_event table
        $count=$dbObj->getDBH()->prepare("DELETE FROM manager_event WHERE manager =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();

        // Deleting record from attendee_event table
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee_event WHERE attendee =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();

        // Deleting record from attendee_session table
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee_session WHERE attendee =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();

        // Finally deleting record from attendee table
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee WHERE idattendee =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();
        header('location: adminbrowseusers.php');
        die();
    }
?>