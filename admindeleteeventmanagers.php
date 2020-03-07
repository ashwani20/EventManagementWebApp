<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'sanitizedatafile.php';
    if(isset($_GET['editIdManager']) && !isValidNumber($_GET['editIdManager'])){
        header('location: adminbrowseeventmanagers.php');
    }

    include_once 'classes/PDO.DB.class.php';
    if(isset($_GET['editIdManager']) and $_GET['editIdManager'] !="" &&
                $_GET['editIdEvent'] and $_GET['editIdEvent'] !=""){
        $dbObj = new DB();
        
        // Removing record from manager_event table
        $count=$dbObj->getDBH()->prepare("DELETE FROM manager_event WHERE event =:event AND manager= :manager");
        $count->bindParam(":event", $_GET['editIdEvent'], PDO::PARAM_INT);
        $count->bindParam(":manager", $_GET['editIdManager'], PDO::PARAM_INT);
        $count->execute();
        header("Location: adminbrowseeventmanagers.php");
        die();
    }
?>