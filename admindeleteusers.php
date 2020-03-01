<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    if(isset($_GET['delId']) and $_GET['delId'] !=""){
        $dbObj = new DB();
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee WHERE idattendee =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();
        header('location: adminbrowseusers.php');
        die();
    }
?>