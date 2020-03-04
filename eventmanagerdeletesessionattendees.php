<?php
    session_start();
    if (!isset($_SESSION['eventmanager'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    if(isset($_GET['editIdAttendee']) and $_GET['editIdAttendee'] !="" &&
                $_GET['editIdSession'] and $_GET['editIdSession'] !=""){
        $dbObj = new DB();
        // Removing record from attendee_session table
        $count=$dbObj->getDBH()->prepare("DELETE FROM attendee_session WHERE session =:session AND attendee= :attendee");
        $count->bindParam(":session", $_GET['editIdSession'], PDO::PARAM_INT);
        $count->bindParam(":attendee", $_GET['editIdAttendee'], PDO::PARAM_INT);
        $count->execute();
        header("Location: eventmanagerregattendsession.php");
        die();
    }
?>