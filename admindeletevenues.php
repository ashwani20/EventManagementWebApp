<?php
    session_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'sanitizedatafile.php';
    if(isset($_GET['delId']) && !isValidNumber($_GET['delId'])){
        header('location: adminbrowsevenues.php');
    }

    include_once 'classes/PDO.DB.class.php';

    if(isset($_GET['delId']) and $_GET['delId'] !=""){
        $dbObj = new DB();
        $count=$dbObj->getDBH()->prepare("DELETE FROM venue WHERE idvenue =:id");
        $count->bindParam(":id", $_GET['delId'], PDO::PARAM_INT);
        $count->execute();
        header('location: adminbrowsevenues.php');
        die();
    }
?>