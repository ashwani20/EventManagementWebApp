<?php
    session_start();
    ob_start();
    if (!isset($_SESSION['admin'])){
        header("Location: login.php");
        die();
    } 
    
    include_once 'classes/PDO.DB.class.php';
    $dbObj = new DB();
    
    $data = array();
    
    if(isset($_GET['editIdManager'])){
        try{
            $stmt = $dbObj->getDBH()->prepare(" SELECT name FROM attendee 
                                                WHERE idattendee = :idattendee;");
            $stmt->execute(array('idattendee'=>$_GET['editIdManager']));
            $data = $stmt->fetchALL();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function getAllEvents($dbh){
        $events = array();
        try{
            $stmt = $dbh->prepare(" SELECT idevent, name
                                    FROM event;");
            $stmt->execute();
            $events = $stmt->fetchAll();

            return $events;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $events;
        }
    }

    function getAllManagers($dbh){
        $managers = array();
        try{
            $stmt = $dbh->prepare(" SELECT a.name, a.idattendee 
                                    FROM attendee a
                                    INNER JOIN role as r ON a.role = r.idrole
                                    WHERE r.name = 'event manager';");
            $stmt->execute();
            $managers = $stmt->fetchAll();

            return $managers;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return $managers;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Manager's Events</title>
        <link rel="shortcut icon" href="https://learncodeweb.com/demo/favicon.ico">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    </head>
    </head>
    <body>
        <div class="container">
            <div class="card">
                <div class="card-header"> 
                    <h5 class="my-0 mr-md-auto font-weight-normal" style="display:inline"> 
                        <a class="my-0 mr-md-auto font-weight-normal" href="admin.php">BookMyEvent</a>
                    </h5>
                    
                    <a href="adminbrowseeventmanagers.php" class="float-right btn btn-dark btn-sm"><i class="fa fa-fw fa-globe"></i> Browse Events and Managers</a>
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <h5 class="card-title">Fields with <span class="text-danger">*</span> are mandatory!</h5>
                        <form method="post">
                            <div class="form-group">    
                                <label for = "manager" >Manager name<span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" value = "<?php echo $data[0]['name']; ?>" class="form-control" disabled="disabled">
                            </div>
                            <div class="form-group">
                                <label for = "event" >Event name<span class="text-danger">*</span></label>
                                <select name="event" id="event" class="form-control" required> 
                                    <option value="">Select an event</option>
                                    <?php
                                        $events = getAllEvents($dbObj->getDBH());
                                        if(count($events)>0){
                                            foreach($events as $val){
                                                if ($_GET['editIdEvent'] == $val['idevent']){
                                        ?>
                                            <option value= <?php echo $val['idevent'];?> selected><?php echo $val['name'];?></option>    
                                        <?php
                                            } else {
                                        ?>
                                            <option value= <?php echo $val['idevent'];?> ><?php echo $val['name'];?></option>    
                                        <?php }
                                            }
                                        } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" value="submit" id="submit" class="btn btn-primary"><i class="fa fa-fw fa-plus-circle"></i> Edit Manager's Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </body>

    <?php
        if (isset($_POST['submit'])){
            $data = [
                'event' => $_POST['event'],
                'manager' => $_GET['editIdManager']
            ];
            
            $sql = "UPDATE IGNORE manager_event 
                    SET event= :event 
                    WHERE manager= :manager";
            // var_dump($data);
            // var_dump($sql);
            $stmt= $dbObj->getDBH()->prepare($sql);
            $stmt->execute($data);
            
            header("Location: adminbrowseeventmanagers.php");
            ob_end_flush();
            die();
        }
    ?>
</html>